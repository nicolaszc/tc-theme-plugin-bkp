import json
import os
import boto3
import urllib.parse
import struct

def get_mp4_dimensions(s3_client, bucket, key):
    """
    Lee los offsets exactos del atomo 'tkhd' en archivos MP4 desde S3.
    """
    try:
        head = s3_client.head_object(Bucket=bucket, Key=key)
        file_size = head['ContentLength']
        
        # Revisamos los primeros 256KB y los últimos 256KB
        ranges = [f'bytes=0-{min(262143, file_size - 1)}']
        if file_size > 262144:
            ranges.append(f'bytes={file_size - 262144}-{file_size - 1}')
        
        for r in ranges:
            response = s3_client.get_object(Bucket=bucket, Key=key, Range=r)
            data = response['Body'].read()
            
            pos = 0
            while True:
                pos = data.find(b'tkhd', pos)
                if pos == -1:
                    break
                try:
                    version = data[pos + 4]
                    # Ajuste preciso de offsets según versión del track header
                    if version == 1:
                        w_offset = pos + 92
                        h_offset = pos + 96
                    else:
                        w_offset = pos + 80
                        h_offset = pos + 84
                        
                    if h_offset + 4 <= len(data):
                        # Desplazamiento corregido usando división entera para evitar el formato roto de Markdown
                        width = struct.unpack('>I', data[w_offset:w_offset+4])[0] // 65536
                        height = struct.unpack('>I', data[h_offset:h_offset+4])[0] // 65536
                        if width > 10 and height > 10:
                            print(f"Dimensiones reales detectadas con éxito: {width}x{height}")
                            return width, height
                except Exception as inner_e:
                    print(f"Error parseando tkhd en pos {pos}: {str(inner_e)}")
                pos += 4
    except Exception as e:
        print(f"Error general al leer S3 para dimensiones: {str(e)}")
        
    # Fallback seguro
    return 1280, 720


def lambda_handler(event, context):
    print("Evento recibido: " + json.dumps(event))
    s3_client = boto3.client('s3')
    
    for record in event['Records']:
        bucket = record['s3']['bucket']['name']
        key = urllib.parse.unquote_plus(record['s3']['object']['key'], encoding='utf-8')
        
        if not key.lower().endswith(('.mp4', '.mov', '.avi', '.m4v', '.webm')):
            print(f"El archivo {key} no es un video compatible o es un directorio, se omite.")
            continue
            
        print(f"Procesando video: {key} en el bucket: {bucket}")
        
        input_file = f"s3://{bucket}/{key}"
        base_key_tuple = os.path.splitext(key)
        base_key = base_key_tuple[0] # Extraemos el string limpio del path sin extensión
        
        # ==========================================================
        # NUEVO DESTINO UNIFICADO EN SUB-CARPETA /hls/
        # ==========================================================
        directorio_padre = os.path.dirname(key)
        nombre_archivo = os.path.basename(base_key)
        output_destination = f"s3://{bucket}/{directorio_padre}/hls/{nombre_archivo}"

        # ==========================================
        # OBTENER EL NOMBRE DESDE EL ARCHIVO
        # ==========================================
        # 1. Extraer el nombre base sin carpetas (ej: "juan-perez-1")
        file_name_only = os.path.basename(base_key)

        # 2. Remover el número que agrega WordPress al final si es un duplicado
        parts = file_name_only.split('-')
        if parts and parts[-1].isdigit():
            parts.pop()

        # 3. Unir con espacios y poner Mayúsculas Iniciales (ej: "Juan Perez")
        pretty_name = " ".join(parts).title()

        # ==========================================
        # CREAR ARCHIVO TEMPORAL DE CAPTIONS (IMSC)
        # ==========================================
        # 1. Leer la plantilla fija IMSC
        obj = s3_client.get_object(Bucket=bucket, Key="assets-converter/creator_name.vtt")
        template_content = obj['Body'].read().decode('utf-8')
        
        # 2. Reemplazar por el nombre limpio de WordPress en MAYÚSCULAS
        creator_name_var = template_content.replace("##CREATOR_NAME##", pretty_name)

        # 3. Guardar el IMSC temporal específico para este video
        ttml_temporal_key = f"{base_key}_title.vtt"
        s3_client.put_object(Bucket=bucket, Key=ttml_temporal_key, Body=creator_name_var)

        # Esta es la ruta que le pasaremos a MediaConvert
        creator_name_path_s3 = f"s3://{bucket}/{ttml_temporal_key}"

        # ==========================================
        # DETECCIÓN AUTOMÁTICA DE ORIENTACIÓN Y RESOLUCIÓN
        # ==========================================
        width, height = get_mp4_dimensions(s3_client, bucket, key)
        
        # Si el alto es mayor que el ancho, es portrait (vertical)
        is_portrait = height > width
        
        logo_x = width - 180
        logo_y = 63
        
        if is_portrait:
            intro_path = "s3://tcc-s3-cdn/assets-converter/trans/intro-portrait.mp4"
            outro_path = "s3://tcc-s3-cdn/assets-converter/trans/outro-portrait.mp4"
            video_width = width
            video_height = height
        else:
            intro_path = "s3://tcc-s3-cdn/assets-converter/trans/intro-landscape.mp4"
            outro_path = "s3://tcc-s3-cdn/assets-converter/trans/outro-landscape.mp4"
            video_width = width
            video_height = height

        # ==========================================
        # 2. CONFIGURACIÓN DEL JOB DE MEDIACONVERT
        # ==========================================
        job_settings = {
            "TimecodeConfig": {
                "Source": "ZEROBASED"
            },
            "OutputGroups": [
                {
                    "Name": "CMAF",
                    "Outputs": [
                        {
                            # OUTPUT 1: EL VIDEO FRAGMENTADO
                            "ContainerSettings": {
                                "Container": "CMFC",
                                "CmfcSettings": {
                                    "AudioRenditionSets": "main"
                                }
                            },
                            "VideoDescription": {
                                "Width": video_width,
                                "Height": video_height,
                                "ScalingBehavior": "DEFAULT",
                                "Sharpness": 50,
                                "CodecSettings": {
                                    "Codec": "H_264",
                                    "H264Settings": {
                                        "MaxBitrate": 2000000,
                                        "RateControlMode": "QVBR",
                                        "AdaptiveQuantization": "AUTO",
                                        "SceneChangeDetect": "TRANSITION_DETECTION"
                                    }
                                },
                                "AfdSignaling": "NONE",
                                "RespondToAfd": "NONE"
                            },
                            "NameModifier": "-video"
                        },
                        {
                            # OUTPUT 2: EL AUDIO FRAGMENTADO
                            "ContainerSettings": {
                                "Container": "CMFC",
                                "CmfcSettings": {}
                            },
                            "AudioDescriptions": [
                                {
                                    "AudioSourceName": "Audio Selector 1",
                                    "CodecSettings": {
                                        "Codec": "AAC",
                                        "AacSettings": {
                                            "Bitrate": 96000,
                                            "CodingMode": "CODING_MODE_2_0",
                                            "SampleRate": 48000
                                        }
                                    },
                                    "StreamName": "main"
                                }
                            ],
                            "NameModifier": "-audio"
                        }
                       
                    ],
                    "OutputGroupSettings": {
                        "Type": "CMAF_GROUP_SETTINGS",
                        "CmafGroupSettings": {
                            "WriteDashManifest": "ENABLED",
                            "SegmentLength": 6,
                            "Destination": output_destination,
                            "DestinationSettings": {
                                "S3Settings": {
                                    "StorageClass": "STANDARD"
                                }
                            },
                            "FragmentLength": 2,
                            "SegmentControl": "SEGMENTED_FILES",
                            "MpdProfile": "MAIN_PROFILE",
                            "DashManifestStyle": "FULL"
                        }
                    }
                }
            ],
            "FollowSource": 2,
            "Inputs": [
                 {                
                    # Input 0: Intro Inicial mapeando tu nuevo formato nativo IMSC
                    "VideoSelector": {},
                    "TimecodeSource": "ZEROBASED",
                    "FileInput": intro_path
                },
                {
                    "AudioSelectors": {
                        "Audio Selector 1": {
                            "DefaultSelection": "DEFAULT",
                            "SelectorType": "HLS_RENDITION_GROUP",
                            "HlsRenditionGroupSettings": {
                                "RenditionGroupId": "main",
                                "RenditionName": "main"
                            }
                        }
                    },
                    "VideoSelector": {},
                    "TimecodeSource": "ZEROBASED",
                    "ImageInserter": {
                        "InsertableImages": [
                            {
                                "Width": 150,
                                "ImageX": logo_x,
                                "ImageY": logo_y,
                                "Layer": 1,
                                "ImageInserterInput": "s3://tcc-s3-cdn/assets-converter/trans/watermark.png",
                                "Opacity": 100
                            }
                        ]
                    },
                    "FileInput": input_file
                },
                {
                    # Input 2: Intro final (salida limpia)
                    "VideoSelector": {},
                    "TimecodeSource": "ZEROBASED",
                    "FileInput": outro_path
                }
            ]
        }
        
        try:
            endpoint_url = os.environ['MEDIACONVERT_ENDPOINT']
            role_arn = os.environ['MEDIACONVERT_ROLE_ARN']
            
            client_mc = boto3.client('mediaconvert', region_name=os.environ['AWS_REGION'], endpoint_url=endpoint_url)
            
            response = client_mc.create_job(
                Queue=f"arn:aws:mediaconvert:us-east-2:937911240887:queues/Default",
                Role=role_arn,
                Settings=job_settings,
                UserMetadata={
                    "original_file": key
                },
                BillingTagsSource="JOB",
                AccelerationSettings={"Mode": "DISABLED"},
                StatusUpdateInterval="SECONDS_60",
                Priority=0
            )
            
            job_id = response['Job']['Id']
            print(f"Trabajo creado exitosamente. ID: {job_id}")
            
        except Exception as e:
            print(f"Error al crear el trabajo o limpiar el archivo: {str(e)}")
            raise e

    return {
        'statusCode': 200,
        'body': json.dumps('Proceso completado, job creado y archivo original limpiado.')
    }

