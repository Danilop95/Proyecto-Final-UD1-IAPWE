### Proyecto-Final-UD1-IAPWE

---

## **Introducción**
Este proyecto representa una página web interactiva que funciona como un currículum vitae dinámico. Implementa funcionalidades clave como autenticación segura, internacionalización, almacenamiento de datos en una base de datos MySQL y cuestionarios interactivos. Todo el sistema está encapsulado en un entorno Docker para garantizar portabilidad y facilidad de despliegue.

Esta documentación incluye una explicación detallada de la lógica de cada archivo y pasos claros para el despliegue en múltiples plataformas.

---

## **Lógica de los Archivos**

### **1. `cuestionario.php`**
Este archivo maneja la lógica del cuestionario interactivo. 

**Flujo de trabajo**:
1. **Validación del Usuario**:
   - Comprueba si el usuario ha iniciado sesión. Si no es así, redirige a `login.php`.
2. **Evaluación del Cuestionario**:
   - Calcula la puntuación comparando las respuestas del usuario con un conjunto de respuestas correctas definidas en `$correctAnswers`.
3. **Almacenamiento de Resultados**:
   - Guarda la puntuación en la base de datos MySQL asociada al usuario actual.
4. **Redirección**:
   - Redirige a `ver_notas.php` para que el usuario pueda visualizar su historial.

---

### **2. `db.php`**
Archivo que configura la conexión a la base de datos mediante PDO.

**Flujo de trabajo**:
1. **Carga de Variables de Entorno**:
   - Recupera las credenciales de la base de datos desde el entorno (`MYSQL_HOST`, `MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`).
2. **Establecimiento de Conexión**:
   - Configura PDO con opciones para manejar errores, establecer el modo de recuperación de datos y optimizar la ejecución.
3. **Manejo de Errores**:
   - Si la conexión falla, muestra un mensaje de error claro para facilitar la depuración.

---

### **3. `error.php`**
Página simple que muestra un mensaje cuando el usuario excede el número permitido de intentos de inicio de sesión.

**Flujo de trabajo**:
1. Muestra un mensaje explicativo al usuario.
2. Incluye un enlace para volver al formulario de inicio de sesión.

---

### **4. `index.php`**
Archivo principal que redirige automáticamente a `login.php`.

**Flujo de trabajo**:
1. **Inicia Sesión**:
   - Llama a `session_start()` para gestionar sesiones.
2. **Redirige**:
   - Dirige inmediatamente a `login.php` para garantizar que el usuario pase primero por el proceso de autenticación.

---

### **5. `inicio.php`**
Página principal tras el inicio de sesión.

**Flujo de trabajo**:
1. **Validación del Usuario**:
   - Comprueba si el usuario ha iniciado sesión. Si no es así, redirige a `login.php`.
2. **Internacionalización**:
   - Permite cambiar dinámicamente entre español e inglés, cargando el archivo de idioma correspondiente.
3. **Contenido Dinámico**:
   - Muestra información del usuario y secciones interactivas como hobbies, información personal y contacto.

---

### **6. `login.php`**
Gestor de autenticación de usuarios.

**Flujo de trabajo**:
1. **Validación de Credenciales**:
   - Verifica el usuario y la contraseña ingresados contra la base de datos.
2. **Gestión de Intentos Fallidos**:
   - Incrementa un contador de intentos fallidos almacenado en la sesión. Redirige a `error.php` después de 3 intentos.
3. **Inicio de Sesión**:
   - Si las credenciales son correctas, almacena el ID del usuario en la sesión y redirige a `inicio.php`.

---

### **7. `logout.php`**
Archivo encargado de cerrar la sesión.

**Flujo de trabajo**:
1. Destruye la sesión activa.
2. Redirige a `login.php`.

---

### **8. `ver_notas.php`**
Muestra el historial de calificaciones del usuario.

**Flujo de trabajo**:
1. **Validación del Usuario**:
   - Comprueba si el usuario ha iniciado sesión. Si no, redirige a `login.php`.
2. **Recuperación de Datos**:
   - Consulta la base de datos para obtener las calificaciones del usuario.
3. **Presentación**:
   - Muestra las calificaciones en una tabla, ordenadas por fecha.

---

## **Estructura del Proyecto**

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
```

### **Descripción de los Archivos Clave**
- **`Dockerfile`**: Configura PHP y Apache con las extensiones necesarias.
- **`docker-compose.yml`**: Orquesta los servicios (PHP-Apache, MySQL, PHPMyAdmin).
- **`mysql-init/init.sql`**: Inicializa la base de datos con las tablas requeridas.
- **`reset.sh`**: Script para reiniciar los contenedores y limpiar datos de MySQL.

---

## **Pasos para el Despliegue**

### **1. Clonar el Repositorio**
```bash
git clone https://github.com/tu-repositorio/proyecto-cv.git
cd proyecto-cv
```

### **2. Configurar Variables de Entorno**
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

### **3. Construir y Levantar los Contenedores**
Ejecuta:
```bash
docker-compose up -d
```

### **4. Acceder a la Aplicación**
- Página principal: [http://localhost:8080](http://localhost:8080)
- PHPMyAdmin: [http://localhost:8081](http://localhost:8081)

---

## **Despliegue por Sistema Operativo**

### **Windows**
1. Instalar [Docker Desktop](https://www.docker.com/products/docker-desktop/).
2. Ejecutar los pasos estándar desde PowerShell o WSL.

### **macOS**
1. Instalar Docker Desktop.
2. Configurar y levantar los servicios desde Terminal.

### **Ubuntu**
1. Instalar Docker:
   ```bash
   sudo apt update
   sudo apt install docker.io docker-compose
   ```
2. Clonar el repositorio y seguir los pasos estándar.

### **GitHub Workspaces**
1. Iniciar un workspace desde el repositorio.
2. Configurar Docker y levantar los contenedores.

---

## **Notas Importantes**
- **Estilo Integrado**:
  - Los estilos CSS están incluidos en los archivos PHP. Esto simplifica la gestión en proyectos pequeños, aunque no es la mejor práctica para proyectos más complejos.
  
> ⚠️ **Advertencia**  
> Meto el estilo dentro de los archivos directamente sin separar a .css lo mejorare.

---

## **Conclusión**
El **Proyecto-Final-UD1-IAPWE** combina diseño funcional, lógica robusta y un entorno de despliegue moderno para ilustrar las mejores prácticas en desarrollo web. Con un entorno Dockerizado, facilita la portabilidad y la réplica en múltiples plataformas, manteniendo un enfoque claro y bien documentado.