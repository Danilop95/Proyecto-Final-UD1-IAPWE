# Proyecto-Final-UD1-IAPWE

## Índice

1. [Introducción](#introducción)
2. [Estructura de la Base de Datos](#estructura-de-la-base-de-datos)
3. [Lógica de los Archivos](#lógica-de-los-archivos)
   - [1. cuestionario.php](#1-cuestionariophp)
   - [2. db.php](#2-dbphp)
   - [3. error.php](#3-errorphp)
   - [4. index.php](#4-indexphp)
   - [5. inicio.php](#5-iniciophp)
   - [6. login.php](#6-loginphp)
   - [7. logout.php](#7-logoutphp)
   - [8. ver_notas.php](#8-ver_notasphp)
4. [Estructura del Proyecto](#estructura-del-proyecto)
5. [Pasos para el Despliegue](#pasos-para-el-despliegue)
   - [1. Clonar el Repositorio](#1-clonar-el-repositorio)
   - [2. Configurar Variables de Entorno](#2-configurar-variables-de-entorno)
   - [3. Construir y Levantar los Contenedores](#3-construir-y-levantar-los-contenedores)
   - [4. Acceder a la Aplicación](#4-acceder-a-la-aplicación)
6. [Despliegue por Sistema Operativo](#despliegue-por-sistema-operativo)
   - [Windows](#windows)
   - [macOS](#macos)
   - [Ubuntu](#ubuntu)
   - [GitHub Workspaces](#github-workspaces)
7. [Notas Importantes](#notas-importantes)
8. [Mejoras Futuras](#mejoras-futuras)

---

## Introducción

Este proyecto es una página web interactiva que funciona como un currículum vitae dinámico y educativo. Incluye funcionalidades clave como autenticación segura, internacionalización, almacenamiento de datos en una base de datos MySQL y cuestionarios interactivos. Todo el sistema está encapsulado en un entorno Docker para garantizar portabilidad y facilidad de despliegue.

Esta documentación proporciona una explicación detallada de la lógica de cada archivo, la estructura del proyecto y los pasos necesarios para su despliegue en diferentes plataformas.

---

## Estructura de la Base de Datos

La base de datos `cv_web` contiene las siguientes tablas:

### Tabla `usuarios`

| Campo    | Tipo          | Atributos     | Descripción                        |
|----------|---------------|---------------|------------------------------------|
| id       | INT           | PRIMARY KEY   | Identificador único del usuario    |
| usuario  | VARCHAR(50)   | UNIQUE, NOT NULL | Nombre de usuario                 |
| password | VARCHAR(255)  | NOT NULL      | Contraseña del usuario             |

### Tabla `notas`

| Campo      | Tipo          | Atributos     | Descripción                                    |
|------------|---------------|---------------|------------------------------------------------|
| id         | INT           | PRIMARY KEY   | Identificador único de la nota                 |
| id_usuario | INT           | FOREIGN KEY   | Referencia al ID del usuario                   |
| nota       | INT           | NOT NULL      | Puntuación obtenida en el cuestionario         |
| fecha      | DATETIME      | DEFAULT CURRENT_TIMESTAMP | Fecha y hora del registro           |

---

## Lógica de los Archivos

### 1. cuestionario.php

Este archivo maneja la lógica del cuestionario interactivo.

**Flujo de trabajo**:

1. **Validación del Usuario**:
   - Comprueba si el usuario ha iniciado sesión. Si no es así, redirige a `login.php`.

2. **Evaluación del Cuestionario**:
   - Calcula la puntuación comparando las respuestas del usuario con las respuestas correctas definidas en `$correctAnswers`.

3. **Almacenamiento de Resultados**:
   - Guarda la puntuación en la base de datos MySQL asociada al usuario actual.

4. **Redirección**:
   - Redirige a `ver_notas.php` para que el usuario pueda visualizar su historial.

---

### 2. db.php

Archivo que configura la conexión a la base de datos mediante PDO.

**Flujo de trabajo**:

1. **Carga de Variables de Entorno**:
   - Recupera las credenciales de la base de datos desde el entorno (`MYSQL_HOST`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`).

2. **Establecimiento de Conexión**:
   - Configura PDO con opciones para manejar errores y establece el charset a UTF8.

3. **Manejo de Errores**:
   - Si la conexión falla, muestra un mensaje de error claro para facilitar la depuración.

---

### 3. error.php

Página simple que muestra un mensaje cuando el usuario excede el número permitido de intentos de inicio de sesión.

**Flujo de trabajo**:

1. **Mensaje de Error**:
   - Informa al usuario que ha superado el número máximo de intentos permitidos.

2. **Enlace de Retorno**:
   - Proporciona un enlace para volver al formulario de inicio de sesión.

---

### 4. index.php

Archivo principal que redirige automáticamente a `login.php`.

**Flujo de trabajo**:

1. **Inicio de Sesión**:
   - Llama a `session_start()` para gestionar sesiones.

2. **Redirección**:
   - Redirige a `login.php` para que el usuario inicie sesión.

---

### 5. inicio.php

Página principal tras el inicio de sesión.

**Flujo de trabajo**:

1. **Validación del Usuario**:
   - Comprueba si el usuario ha iniciado sesión. Si no es así, redirige a `login.php`.

2. **Internacionalización**:
   - Permite cambiar entre español e inglés, cargando el archivo de idioma correspondiente desde la carpeta `lang/`.

3. **Contenido Dinámico**:
   - Muestra información personal, hobbies y detalles de contacto en secciones desplegables.

---

### 6. login.php

Gestor de autenticación de usuarios.

**Flujo de trabajo**:

1. **Validación de Credenciales**:
   - Verifica el usuario y la contraseña ingresados contra la base de datos.

2. **Gestión de Intentos Fallidos**:
   - Incrementa un contador de intentos fallidos almacenado en la sesión.
   - Redirige a `error.php` después de 3 intentos fallidos.

3. **Inicio de Sesión**:
   - Si las credenciales son correctas, almacena el ID del usuario en la sesión y redirige a `inicio.php`.

---

### 7. logout.php

Archivo encargado de cerrar la sesión.

**Flujo de trabajo**:

1. **Destruir Sesión**:
   - Utiliza `session_destroy()` para eliminar todos los datos de la sesión.

2. **Redirección**:
   - Redirige al usuario a `login.php`.

---

### 8. ver_notas.php

Muestra el historial de calificaciones del usuario.

**Flujo de trabajo**:

1. **Validación del Usuario**:
   - Verifica si el usuario ha iniciado sesión. Si no, redirige a `login.php`.

2. **Recuperación de Datos**:
   - Consulta la base de datos para obtener las calificaciones del usuario.

3. **Presentación**:
   - Muestra las calificaciones en una tabla ordenada por fecha.

---

## Estructura del Proyecto

```
.
├── Dockerfile
├── docker-compose.yml
├── mysql-init/
│   └── init.sql
├── mysql_data/
├── php.ini
├── reset.sh
└── src/
    ├── cuestionario.php
    ├── db.php
    ├── error.php
    ├── index.php
    ├── inicio.php
    ├── login.php
    ├── logout.php
    ├── ver_notas.php
    ├── imgs/
    │   └── foto.jpg
    └── lang/
        ├── en.php
        └── es.php
```

### Descripción de los Archivos Clave

- **Dockerfile**: Configura el entorno PHP y Apache con las extensiones necesarias.

- **docker-compose.yml**: Orquesta los servicios de PHP-Apache, MySQL y PHPMyAdmin.

- **mysql-init/init.sql**: Script SQL para inicializar la base de datos con las tablas requeridas.

- **reset.sh**: Script para reiniciar los contenedores y limpiar datos de MySQL.

---

## Pasos para el Despliegue

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-repositorio/proyecto-cv.git
cd proyecto-cv
```

### 2. Configurar Variables de Entorno

Crea un archivo `.env` en la raíz del proyecto con el siguiente contenido:

```plaintext
PHP_APACHE_PORT=8080
MYSQL_HOST=mysql
MYSQL_USER=root
MYSQL_PASSWORD=root_password
MYSQL_DATABASE=cv_web
MYSQL_PORT=3306
PHPMYADMIN_PORT=8081
PHP_ENV=development
MYSQL_ROOT_PASSWORD=root_password
```

### 3. Construir y Levantar los Contenedores

Ejecuta:

```bash
docker-compose up -d
```

### 4. Acceder a la Aplicación

- **Página principal**: [http://localhost:8080](http://localhost:8080)
- **PHPMyAdmin**: [http://localhost:8081](http://localhost:8081)

---

## Despliegue por Sistema Operativo

### Windows

1. **Instalar Docker Desktop**: [Descargar Docker Desktop](https://www.docker.com/products/docker-desktop/).

2. **Ejecutar los Pasos Estándar**: Abre PowerShell o CMD y sigue los pasos de despliegue.

### macOS

1. **Instalar Docker Desktop**: [Descargar Docker Desktop para Mac](https://www.docker.com/products/docker-desktop/).

2. **Configurar y Levantar los Servicios**: Utiliza la Terminal para seguir los pasos de despliegue.

### Ubuntu

1. **Instalar Docker y Docker Compose**:

   ```bash
   sudo apt update
   sudo apt install docker.io docker-compose
   ```

2. **Clonar el Repositorio y Seguir los Pasos Estándar**.

### GitHub Workspaces

1. **Iniciar un Workspace**: Desde el repositorio, inicia un nuevo workspace.

2. **Configurar Docker y Levantar los Contenedores**.

---

## Notas Importantes

- **Internacionalización**:
  - Los archivos de idioma se encuentran en la carpeta `lang/` y permiten cambiar el idioma de la aplicación dinámicamente.

- **Estilo Integrado**:
  - Los estilos CSS están incluidos directamente en los archivos PHP. Esto simplifica la gestión en proyectos pequeños, pero se recomienda separarlos en archivos `.css` para una mejor organización.

- **Seguridad**:
  - Las contraseñas se almacenan en texto plano. Es fundamental implementar un hash seguro para las contraseñas utilizando funciones como `password_hash()` en PHP.

---

## Mejoras Futuras

- **Separación de Estilos**:
  - Mover los estilos CSS a archivos separados para mejorar la mantenibilidad y escalabilidad del proyecto.

- **Implementación de Hash en Contraseñas**:
  - Asegurar las contraseñas almacenadas en la base de datos utilizando técnicas de hash y salting.

- **Validación y Sanitización de Datos**:
  - Implementar validaciones más robustas y sanitización de entradas para mejorar la seguridad.

> ⚠️ **Advertencia**
>
> Aunque los estilos están integrados en los archivos PHP para simplificar, mejoraré esto separando los estilos en archivos CSS independientes.

