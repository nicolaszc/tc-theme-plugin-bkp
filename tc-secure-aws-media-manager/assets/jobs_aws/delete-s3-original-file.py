import json
import boto3

s3_client = boto3.client('s3')
BUCKET_NAME = 'tcc-s3-cdn'

def lambda_handler(event, context):
    print("Evento recibido:", json.dumps(event))
    
    try:
        detail = event.get('detail', {})
        
        if detail.get('status') != 'COMPLETE':
            print("El trabajo no está completo. Ignorando.")
            return {'statusCode': 200, 'body': 'Ignorado'}
        
        # Obtenemos la ruta relativa del archivo desde el userMetadata
        user_metadata = detail.get('userMetadata', {})
        original_file = user_metadata.get('original_file', '')
        
        if original_file:
            # Limpiamos cualquier barra inicial por seguridad
            key = original_file.lstrip('/')
            
            print(f"Eliminando archivo original: s3://{BUCKET_NAME}/{key}")
            s3_client.delete_object(Bucket=BUCKET_NAME, Key=key)
            print("¡Archivo original eliminado con éxito!")
        else:
            print("No se encontró 'original_file' en el userMetadata.")
            
        return {'statusCode': 200, 'body': 'Limpieza completada'}
        
    except Exception as e:
        print(f"Error al eliminar el archivo: {str(e)}")
        raise e
