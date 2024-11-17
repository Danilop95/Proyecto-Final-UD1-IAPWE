<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cargar idioma seleccionado
$idioma = isset($_GET['lang']) ? $_GET['lang'] : 'es';
$langFile = __DIR__ . "/lang/{$idioma}.php";
if (!file_exists($langFile)) {
    $langFile = __DIR__ . "/lang/es.php";
}
$traducciones = include($langFile);
?>

<!DOCTYPE html>
<html lang="<?php echo $idioma; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $traducciones['inicio_titulo']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMW7Ad4e+jDgl9jjSpkFy4IWf7aHNKxkFtMQDuv" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Header superior */
        header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #222;
            color: #fff;
        }

        header .right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        header .right a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        header .right a:hover {
            color: #FFD700;
        }

        header .right button {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        /* Navegación inferior */
        .navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
        }

        .navigation .left,
        .navigation .center,
        .navigation .right {
            display: flex;
            align-items: center;
        }

        .navigation .left a,
        .navigation .center a,
        .navigation .right button {
            margin: 0 10px;
            text-decoration: none;
            color: white;
            font-size: 16px;
            background-color: #007BFF;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navigation .left a:hover,
        .navigation .center a:hover,
        .navigation .right button:hover {
            background-color: #0056b3;
        }

        .navigation .right button {
            border: none;
            cursor: pointer;
        }

        /* Contenido principal */
        .container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 2.5rem;
        }

        /* Imagen de perfil */
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin: 20px auto;
            border: 3px solid #007BFF;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .section {
            margin: 20px 0;
        }

        .section h2 {
            cursor: pointer;
            color: #007BFF;
        }

        .section-content {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleSection(id) {
            const section = document.getElementById(id);
            section.style.display = section.style.display === "none" || section.style.display === "" ? "block" : "none";
        }

        function toggleTheme() {
            const body = document.body;
            body.style.backgroundColor = body.style.backgroundColor === 'black' ? '#f4f4f9' : 'black';
            body.style.color = body.style.color === 'white' ? '#333' : 'white';
        }
    </script>
</head>
<body>
<header>
    <div class="right">
        <a href="?lang=es">ES</a>
        <a href="?lang=en">EN</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</header>
<div class="navigation">
    <div class="left">
        <a href="inicio.php"><i class="fas fa-home"></i></a>
    </div>
    <div class="center">
        <a href="inicio.php"><?php echo $traducciones['menu_inicio']; ?></a>
        <a href="cuestionario.php"><?php echo $traducciones['menu_cuestionario']; ?></a>
    </div>
    <div class="right">
        <button onclick="toggleTheme()"><i class="fas fa-adjust"></i> Tema</button>
    </div>
</div>
<div class="container">
    <h1><?php echo $traducciones['titulo_bienvenida']; ?></h1>
    <div class="profile-pic">
        <img src="/imgs/foto.jpg" alt="Foto de perfil de Daniel Puente García">
    </div>
    <div class="section">
        <h2 onclick="toggleSection('info')"><?php echo $traducciones['info_titulo']; ?></h2>
        <div id="info" class="section-content">
            <p><?php echo $traducciones['info_contenido']; ?></p>
        </div>
    </div>
    <div class="section">
        <h2 onclick="toggleSection('hobbies')"><?php echo $traducciones['hobbies_titulo']; ?></h2>
        <div id="hobbies" class="section-content">
            <p><?php echo $traducciones['hobbies_contenido']; ?></p>
        </div>
    </div>
    <div class="section">
        <h2 onclick="toggleSection('contact')"><?php echo $traducciones['contacto_titulo']; ?></h2>
        <div id="contact" class="section-content">
            <p><?php echo $traducciones['contacto_contenido']; ?></p>
        </div>
    </div>
</div>
</body>
</html>
