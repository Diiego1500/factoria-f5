# Prueba Técnica DevOps

## Descripción General

Este proyecto gestiona imágenes, permitiendo su creación, visualización, edición y eliminación. Cada imagen incluye un título, fecha de creación y descripción.

El desarrollo abarca la implementación web y las estrategias de CI/CD para construir y desplegar imágenes o artefactos en una instancia EC2 de AWS, almacenando los archivos en buckets S3.

## Estrategia de Implementación

El proyecto cuenta con dos pipelines de despliegue:

1. **Build and Deploy Docker Image**: Se ejecuta automáticamente al hacer push al repositorio. (Crea y despliegua el contenedor)
2. **Deploy Docker Container to EC2**: Despliega artefactos previamente creados y recibe como parámetro la versión del artefacto.

### Parámetros válidos para `Deploy Docker Container to EC2`
- `v1`
- `v2`
- `v3`
- `latest`

## Framework y Almacenamiento de Imágenes

Este proyecto utiliza el framework **Symfony**. Las imágenes se guardan en el bucket **image-manager-factoria-f5**, que activa una función Lambda para redimensionarlas. Posteriormente, las imágenes redimensionadas se almacenan en el bucket **image-manager-factoria-f5-thumbnails**, lo que mejora los tiempos de respuesta.

## Funcionamiento del Sistema

- La galería principal muestra las miniaturas almacenadas en **image-manager-factoria-f5-thumbnails**.
- Al hacer clic en una miniatura, se accede a la imagen original en **image-manager-factoria-f5**.
- Si una imagen se reemplaza o elimina, se borra de ambos buckets para optimizar el espacio de almacenamiento.

### Stack Tecnológico

1. [Symfony](https://symfony.com/doc/current/setup.html "Symfony") – Para la construcción del sitio web.
2. [Docker](https://www.docker.com/ "Docker") – Para virtualización y contenerización.
3. [GitHub](https://github.com/ "GitHub") y [GitHub Actions](https://docs.github.com/es/actions "GitHubActions") – Para repositorio y CI/CD.
4. [AWS](https://aws.amazon.com/es/ "AWS") – Uso de EC2, VPC, S3 y Lambda.
5. [Prometheus](https://prometheus.io/ "Prometheus") – Para la recolección de métricas.

### ¿Cómo instalarlo y probarlo en local?

Solo necesitas tener Docker instalado, clonar este repositorio y ejecutar:

`docker compose up -d`

Este comando inicializará los servicios. Ten en cuenta que el proyecto tiene dependencias que se instalan después de construir la imagen, por lo que el servicio no se levantará de inmediato. Para seguir la trazabilidad de los logs, ejecuta:

`docker logs -f image_management`

Una vez Apache esté en funcionamiento, podrás acceder a la aplicación en http://localhost (puerto 80).

### ¿Cómo visualizar el proyecto en local?

Asegúrate de que los siguientes puertos estén disponibles en tu equipo:

- **3306**: Puerto de MariaDB, donde escucha el contenedor de la base de datos.
- **80**: Puerto para acceder a la aplicación desde `localhost` (se recomienda usar Google Chrome o un navegador que no fuerce HTTPS por defecto).
- **9090**: Puerto para Prometheus; accede a Prometheus mediante `http://localhost:9090`.
- **9100**: Puerto utilizado por Node Exporter, que exporta las métricas para Prometheus.

Si alguno de estos puertos está en uso, puedes modificarlos en el archivo `docker-compose.yml` y ejecutar nuevamente el siguiente comando para iniciar los servicios:

`docker compose up -d`
