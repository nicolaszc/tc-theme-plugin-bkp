import hashlib
import json
import os
import urllib.error
import urllib.request

import boto3

BUCKET_NAME = os.environ.get("BUCKET_NAME", "tcc-s3-cdn")
CLOUDFRONT_DOMAIN = os.environ.get("CLOUDFRONT_DOMAIN", "https://cdn.topclassclub.com").rstrip("/")
PREVIEW_TOKEN = os.environ.get("TOPCLASS_PREVIEW_API_TOKEN", "")
INTRO_OUTRO_OVERHEAD_MS = 16_000
PREVIEW_LENGTH_SECONDS = 10
PREVIEW_START_SECONDS = 5
PREVIEW_WIDTH = 360
PREVIEW_HEIGHT = 480

s3_client = boto3.client("s3")


def _output_duration_ms(detail):
    durations = []
    for group in detail.get("outputGroupDetails", []):
        for output in group.get("outputDetails", []):
            value = output.get("durationInMs")
            if value:
                durations.append(int(value))
    if not durations:
        raise ValueError("El evento COMPLETE no trae durationInMs en sus salidas.")
    return max(durations)


def _timecode(seconds):
    total_seconds = int(seconds)
    hours, remainder = divmod(total_seconds, 3600)
    minutes, secs = divmod(remainder, 60)
    return f"{hours:02d}:{minutes:02d}:{secs:02d}:00"


def _preview_paths(original_key):
    parts = original_key.lstrip("/").split("/")
    if len(parts) < 3 or parts[1] != "private":
        raise ValueError(f"La ruta original no tiene el formato <site>/private/...: {original_key}")

    parts[1] = "public"
    source_name = parts[-1]
    stem = os.path.splitext(source_name)[0]
    public_directory = "/".join(parts[:-1])
    preview_key = f"{public_directory}/{stem}_preview.mp4"
    destination = f"s3://{BUCKET_NAME}/{public_directory}/{stem}"
    site = parts[0]
    return preview_key, destination, site


def _site_rest_url(site):
    base_urls = {
        "girls": os.environ.get("WP_GIRLS_BASE_URL", "https://girls.topclassclub.com"),
        "trans": os.environ.get("WP_TRANS_BASE_URL", "https://trans.topclassclub.com"),
    }
    base_url = base_urls.get(site)
    if not base_url:
        raise ValueError(f"No existe una URL WordPress configurada para el sitio {site!r}.")
    return base_url.rstrip("/") + "/wp-json/topclass/v1/video-preview"


def _publish_preview(product_id, preview_url, duration_ms, site):
    if not PREVIEW_TOKEN:
        raise RuntimeError("Falta la variable TOPCLASS_PREVIEW_API_TOKEN en la Lambda.")

    payload = json.dumps({
        "product_id": int(product_id),
        "preview_url": preview_url,
        "duration_ms": int(duration_ms),
    }).encode("utf-8")
    request = urllib.request.Request(
        _site_rest_url(site),
        data=payload,
        method="POST",
        headers={
            "Content-Type": "application/json",
            "X-Topclass-Preview-Token": PREVIEW_TOKEN,
        },
    )

    try:
        with urllib.request.urlopen(request, timeout=20) as response:
            response_body = response.read().decode("utf-8")
            if response.status < 200 or response.status >= 300:
                raise RuntimeError(f"WordPress respondió HTTP {response.status}: {response_body}")
            print("Preview guardado en WordPress:", response_body)
    except urllib.error.HTTPError as error:
        body = error.read().decode("utf-8", errors="replace")
        raise RuntimeError(f"WordPress respondió HTTP {error.code}: {body}") from error


def _preview_job_settings(bucket, key, preview_destination, source_duration_ms):
    source_duration_seconds = max(0, int(source_duration_ms / 1000))
    clip_length = min(PREVIEW_LENGTH_SECONDS, source_duration_seconds)
    if clip_length < 1:
        raise ValueError("El video original no alcanza la duración mínima para el preview.")

    # Para videos largos usa los segundos 5–15; en videos cortos toma los últimos 10 s disponibles.
    clip_start = min(PREVIEW_START_SECONDS, max(0, source_duration_seconds - clip_length))
    clip_end = clip_start + clip_length

    settings = {
        "TimecodeConfig": {"Source": "ZEROBASED"},
        "OutputGroups": [
            {
                "Name": "Preview MP4",
                "OutputGroupSettings": {
                    "Type": "FILE_GROUP_SETTINGS",
                    "FileGroupSettings": {"Destination": preview_destination},
                },
                "Outputs": [
                    {
                        "ContainerSettings": {
                            "Container": "MP4",
                            "Mp4Settings": {
                                "CslgAtom": "INCLUDE",
                                "FreeSpaceBox": "EXCLUDE",
                                "MoovPlacement": "PROGRESSIVE_DOWNLOAD",
                            },
                        },
                        "VideoDescription": {
                            "Width": PREVIEW_WIDTH,
                            "Height": PREVIEW_HEIGHT,
                            "ScalingBehavior": "FILL",
                            "Sharpness": 50,
                            "CodecSettings": {
                                "Codec": "H_264",
                                "H264Settings": {
                                    "InterlaceMode": "PROGRESSIVE",
                                    "RateControlMode": "QVBR",
                                    "QvbrSettings": {"QvbrQualityLevel": 5},
                                    "MaxBitrate": 600000,
                                    "FramerateControl": "INITIALIZE_FROM_SOURCE",
                                    "GopSize": 60,
                                    "GopSizeUnits": "FRAMES",
                                    "CodecProfile": "MAIN",
                                    "CodecLevel": "AUTO",
                                    "SceneChangeDetect": "TRANSITION_DETECTION",
                                },
                            },
                        },
                        "NameModifier": "_preview",
                    }
                ],
            }
        ],
        "Inputs": [
            {
                "FileInput": f"s3://{bucket}/{key}",
                "TimecodeSource": "ZEROBASED",
                "VideoSelector": {},
                "InputClippings": [
                    {
                        "StartTimecode": _timecode(clip_start),
                        "EndTimecode": _timecode(clip_end),
                    }
                ],
            }
        ],
    }
    return settings


def _start_preview_job(event, detail, user_metadata, original_key):
    product_id = str(user_metadata.get("product_id", "")).strip()
    if not product_id.isdigit():
        raise ValueError("Falta product_id en userMetadata; se conserva el original para no perder el vínculo.")

    final_duration_ms = _output_duration_ms(detail)
    source_duration_ms = max(0, final_duration_ms - INTRO_OUTRO_OVERHEAD_MS)
    preview_key, destination, site = _preview_paths(original_key)

    endpoint_url = os.environ["MEDIACONVERT_ENDPOINT"]
    role_arn = os.environ["MEDIACONVERT_ROLE_ARN"]
    region = event.get("region") or os.environ["AWS_REGION"]
    queue = detail.get("queue") or os.environ.get("MEDIACONVERT_QUEUE_ARN")
    if not queue:
        raise RuntimeError("El evento no trae queue y falta MEDIACONVERT_QUEUE_ARN.")

    client_mc = boto3.client("mediaconvert", region_name=region, endpoint_url=endpoint_url)
    source_job_id = str(detail.get("jobId", ""))
    client_token = "preview-" + hashlib.sha256(source_job_id.encode("utf-8")).hexdigest()[:48]
    response = client_mc.create_job(
        Queue=queue,
        Role=role_arn,
        Settings=_preview_job_settings(BUCKET_NAME, original_key, destination, source_duration_ms),
        UserMetadata={
            "processing_stage": "preview",
            "original_file": original_key,
            "product_id": product_id,
            "final_duration_ms": str(final_duration_ms),
            "preview_key": preview_key,
            "source_job_id": source_job_id,
        },
        ClientRequestToken=client_token,
        BillingTagsSource="JOB",
        AccelerationSettings={"Mode": "DISABLED"},
        StatusUpdateInterval="SECONDS_60",
        Priority=0,
    )
    job_id = response["Job"]["Id"]
    print(f"Job de preview creado: {job_id}; salida s3://{BUCKET_NAME}/{preview_key}")
    return {"statusCode": 200, "body": f"Preview job creado: {job_id}"}


def _finish_preview(user_metadata, original_key):
    product_id = str(user_metadata.get("product_id", "")).strip()
    final_duration_ms = int(user_metadata.get("final_duration_ms", "0"))
    preview_key = user_metadata.get("preview_key", "")
    if not product_id.isdigit() or not final_duration_ms or not preview_key:
        raise ValueError("El evento del preview no trae product_id, duración o preview_key.")

    _, _, site = _preview_paths(original_key)
    preview_url = f"{CLOUDFRONT_DOMAIN}/{preview_key.lstrip('/')}"
    _publish_preview(product_id, preview_url, final_duration_ms, site)

    # Solo se elimina el original después de que WordPress confirme que guardó el post meta.
    s3_client.delete_object(Bucket=BUCKET_NAME, Key=original_key)
    print(f"Preview guardado y original eliminado: s3://{BUCKET_NAME}/{original_key}")
    return {"statusCode": 200, "body": "Preview publicado y original eliminado"}


def lambda_handler(event, context):
    print("Evento recibido:", json.dumps(event))
    detail = event.get("detail", {})
    if detail.get("status") != "COMPLETE":
        print("El trabajo no está completo. Ignorando.")
        return {"statusCode": 200, "body": "Ignorado"}

    user_metadata = detail.get("userMetadata", {})
    original_key = str(user_metadata.get("original_file", "")).lstrip("/")
    if not original_key:
        print("No se encontró original_file en userMetadata. Ignorando.")
        return {"statusCode": 200, "body": "Ignorado: falta original_file"}

    stage = user_metadata.get("processing_stage", "source")
    if stage == "preview":
        return _finish_preview(user_metadata, original_key)
    if stage == "source":
        return _start_preview_job(event, detail, user_metadata, original_key)

    print(f"Etapa desconocida {stage!r}. Ignorando.")
    return {"statusCode": 200, "body": "Ignorado: etapa desconocida"}
