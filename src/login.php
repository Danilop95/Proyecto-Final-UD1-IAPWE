<?php
session_start();
require_once 'db.php';

$_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta para buscar al usuario
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE usuario = :usuario');
    $stmt->execute(['usuario' => $usuario]);
    $user = $stmt->fetch();

    // Verificación de contraseña sin encriptación
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login_attempts'] = 0;
        header("Location: inicio.php");
        exit;
    } else {
        $_SESSION['login_attempts']++;
        $intentos_restantes = 3 - $_SESSION['login_attempts'];
        $error_message = "Credenciales incorrectas. Intentos restantes: $intentos_restantes";

        if ($_SESSION['login_attempts'] >= 3) {
            header("Location: error.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMW7Ad4e+jDgl9jjSpkFy4IWf7aHNKxkFtMQDuv" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #222;
            color: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .login-container h1 {
            margin-bottom: 20px;
            color: #FFD700;
        }

        .login-container p {
            color: red;
            margin-bottom: 15px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .login-container input {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .login-container button {
            padding: 10px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if ($error_message): ?>
            <p><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" required>
            
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>
