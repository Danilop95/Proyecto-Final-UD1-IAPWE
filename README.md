# Proyecto-Final-UD1-IAPWE
### Documentación Extendida del Proyecto

---

## **Introducción**
Este proyecto es una página web diseñada como un currículum vitae (CV) interactivo que incluye:

- Formulario de inicio de sesión con validación.
- Gestión de idiomas (español e inglés).
- Cuestionario interactivo con almacenamiento de resultados.
- Base de datos para almacenar usuarios y calificaciones.
- Configuración y despliegue mediante Docker, proporcionando un entorno modular, portátil y profesional.

A continuación, se detalla la configuración, despliegue y lógica del proyecto con un tutorial paso a paso.

---

## **Configuración de Docker**

Docker se utiliza para gestionar el servidor web (PHP y Apache), la base de datos MySQL y PHPMyAdmin. Todo el entorno está definido en un archivo `docker-compose.yml`.

### **Requisitos Previos**
1. **Instalar Docker**:
   - [Guía oficial de instalación](https://docs.docker.com/get-docker/).
2. **Instalar Docker Compose**:
   - [Guía oficial de instalación](https://docs.docker.com/compose/install/).

---

### **Estructura del Proyecto**

```plaintext
.
├── Dockerfile
├── docker-compose.yml
├── mysql-init/
│   └── init.sql
├── mysql_data/
├── php.ini
├── reset.sh
└── src/
    ├── db.php
    ├── login.php
    ├── logout.php
    ├── inicio.php
    ├── cuestionario.php
    ├── ver_notas.php
    ├── imgs/
    │   └── foto.jpg
    ├── lang/
    │   ├── en.php
    │   └── es.php
    └── Despliegue-BD.sql
```

---

### **Detalles de Configuración**

#### **`docker-compose.yml`**
Define los contenedores necesarios para el proyecto.

```yaml
version: '3.8'

services:
  php-apache:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: php-apache
    volumes:
      - ./src:/var/www/html
    ports:
      - "8080:80"
    environment:
      MYSQL_HOST: db
      MYSQL_USER: root
      MYSQL_PASSWORD: root_password
      MYSQL_DATABASE: cv_web
    depends_on:
      - db

  db:
    image: mysql:5.7
    container_name: mysql
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: cv_web
    ports:
      - "3306:3306"
    volumes:
      - ./mysql_data:/var/lib/mysql
      - ./mysql-init:/docker-entrypoint-initdb.d
    command: --default-authentication-plugin=mysql_native_password

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: phpmyadmin
    environment:
      PMA_HOST: db
      MYSQL_ROOT_PASSWORD: root_password
    ports:
      - "8081:80"
```

#### **`Dockerfile`**
Configura PHP con Apache.

```dockerfile
FROM php:7.4-apache

COPY php.ini /usr/local/etc/php/
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
```

#### **`mysql-init/init.sql`**
Este script crea la base de datos y sus tablas iniciales.

```sql
CREATE DATABASE IF NOT EXISTS cv_web;
USE cv_web;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nota DECIMAL(5, 2) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);
```

---

## **Tutorial: Cómo Desplegar la Página**

1. **Clonar el Repositorio**
   - Copiar el proyecto a tu máquina local:
     ```bash
     git clone https://github.com/tu-repositorio/proyecto-cv.git
     cd proyecto-cv
     ```

2. **Construir y Levantar los Contenedores**
   - Ejecuta el comando:
     ```bash
     docker-compose up -d
     ```
   - Este comando construye la imagen `php-apache`, inicia MySQL y despliega la base de datos definida en `init.sql`.

3. **Acceder a la Aplicación**
   - Abrir un navegador e ingresar la dirección:
     - Página principal: [http://localhost:8080](http://localhost:8080)
     - PHPMyAdmin: [http://localhost:8081](http://localhost:8081)

4. **Verificar la Base de Datos**
   - Accede a PHPMyAdmin con:
     - Usuario: `root`
     - Contraseña: `root_password`
   - Verifica que las tablas `usuarios` y `notas` están creadas.

5. **Opcional: Reiniciar el Entorno**
   - Usa el script `reset.sh` para limpiar datos y reiniciar contenedores:
     ```bash
     ./reset.sh
     ```

---

## **Lógica de la Web**

### **Inicio de Sesión (`login.php`)**
1. Valida las credenciales ingresadas contra la tabla `usuarios` en MySQL.
2. Si las credenciales son correctas, inicia sesión y redirige a `inicio.php`.
3. Después de 3 intentos fallidos, redirige a `error.php`.

#### Código Clave:
```php
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
$stmt->execute(['usuario' => $usuario]);
$result = $stmt->fetch();

if ($result && password_verify($password, $result['password'])) {
    $_SESSION['user_id'] = $result['id'];
    header("Location: inicio.php");
    exit;
}
```

---

### **Página Principal (`inicio.php`)**
- Muestra información del CV, hobbies e idiomas.
- Cambia de idioma dinámicamente según la selección.

#### Código Clave:
```php
$idioma = isset($_GET['lang']) ? $_GET['lang'] : 'es';
$langFile = __DIR__ . "/lang/{$idioma}.php";
if (!file_exists($langFile)) {
    $langFile = __DIR__ . "/lang/es.php";
}
$traducciones = include($langFile);
```

---

### **Cuestionario (`cuestionario.php`)**
- Permite a los usuarios responder preguntas.
- Las calificaciones se guardan en la tabla `notas`.

#### Código Clave:
```php
$stmt = $pdo->prepare("INSERT INTO notas (id_usuario, nota) VALUES (:id_usuario, :nota)");
$stmt->execute(['id_usuario' => $_SESSION['user_id'], 'nota' => $calificacion]);
```

---

### **Historial de Notas (`ver_notas.php`)**
- Recupera las calificaciones del usuario desde la tabla `notas`.
- Ordena las notas por fecha.

#### Código Clave:
```php
$stmt = $pdo->prepare("SELECT * FROM notas WHERE id_usuario = :id_usuario ORDER BY fecha DESC");
$stmt->execute(['id_usuario' => $_SESSION['user_id']]);
$notas = $stmt->fetchAll();
```

---

## **Detalles de la Conexión a la Base de Datos**

### Archivo `db.php`
Este archivo maneja la conexión a MySQL utilizando PDO.

```php
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new PDO($dsn, $user, $pass, $options);
```

---

## **Explicación Adicional de Conceptos Clave**

### **Diferencias entre Docker y XAMPP**
1. **Portabilidad**:
   - Docker encapsula todo el entorno, haciéndolo replicable en cualquier sistema.
   - XAMPP depende del sistema operativo y no es portable.

2. **Aislamiento**:
   - Docker ejecuta cada servicio en contenedores separados.
   - XAMPP ejecuta todos los servicios en un único entorno compartido.

3. **Actualización**:
   - Docker permite actualizar imágenes sin afectar los datos existentes.
   - XAMPP requiere actualizar manualmente cada componente.

---

