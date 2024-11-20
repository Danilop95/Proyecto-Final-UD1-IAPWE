#!/bin/bash

# Script para reiniciar el entorno de Docker de forma segura y optimizada

set -e  # Terminar el script en caso de error
set -o pipefail  # Detectar errores en los pipes

# Variables
MYSQL_DATA_DIR="./mysql_data"
COMPOSE_FILE="docker-compose.yml"

# Comprobaciones previas
if [ ! -f "$COMPOSE_FILE" ]; then
    echo "Error: No se encontró el archivo docker-compose.yml en el directorio actual."
    exit 1
fi

if [ ! -d "$MYSQL_DATA_DIR" ]; then
    echo "Advertencia: No se encontró el directorio mysql_data. Creándolo..."
    mkdir -p "$MYSQL_DATA_DIR"
fi

# Detener y eliminar contenedores
echo "Deteniendo y eliminando contenedores..."
docker-compose down -v --remove-orphans

# Limpiar datos de MySQL
echo "Eliminando datos de MySQL..."
if sudo rm -rf "${MYSQL_DATA_DIR:?}/*"; then
    echo "Datos de MySQL eliminados correctamente."
else
    echo "Error: No se pudieron eliminar los datos de MySQL."
    exit 1
fi

# Reconstruir contenedores
echo "Reconstruyendo y levantando contenedores..."
docker-compose up --build -d

# Verificar estado de los servicios
echo "Verificando estado de los contenedores..."
docker-compose ps

echo "Entorno reiniciado correctamente."
