<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Calcular la puntuación del cuestionario
    $score = 0;

    // Respuestas correctas
    $correctAnswers = [
        'pregunta1' => 'clave primaria',
        'pregunta2' => 'php',
        'pregunta3' => 'SELECT',
        'pregunta4' => 'POST',
        'pregunta5' => 'HTML',
        'pregunta6' => '10',
        'pregunta7' => 'CSS',
        'pregunta8' => 'foreign key',
        'pregunta9' => 'Docker',
        'pregunta10' => 'MySQL'
    ];

    // Revisar respuestas
    foreach ($correctAnswers as $key => $value) {
        if (isset($_POST[$key]) && trim(strtolower($_POST[$key])) === strtolower($value)) {
            $score++;
        }
    }

    // Guardar la puntuación en la base de datos
    $stmt = $pdo->prepare("INSERT INTO notas (id_usuario, nota) VALUES (:id_usuario, :nota)");
    $stmt->execute([
        'id_usuario' => $_SESSION['user_id'],
        'nota' => $score
    ]);

    // Redirigir al historial de notas
    header("Location: ver_notas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario de la Asignatura</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('https://www.momentogp.com/wp-content/uploads/2018/08/859201_fernando-alonso-wallpaper.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #FFD700;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .question-group {
            margin-bottom: 20px;
        }
        .question-group p {
            margin-bottom: 10px;
            font-size: 18px;
        }
        .question-group input[type="radio"],
        .question-group input[type="text"],
        .question-group input[type="number"],
        .question-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #FFD700;
            color: black;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #E6B800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cuestionario de la Asignatura</h1>
        <form method="POST" action="cuestionario.php">
            <!-- Pregunta 1 -->
            <div class="question-group">
                <p>1. ¿Cómo se llama el identificador único en una tabla de base de datos?</p>
                <input type="text" name="pregunta1" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 2 -->
            <div class="question-group">
                <p>2. ¿Qué lenguaje se usa principalmente para desarrollar páginas dinámicas en el backend?</p>
                <input type="text" name="pregunta2" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 3 -->
            <div class="question-group">
                <p>3. ¿Qué comando SQL se usa para seleccionar datos de una tabla?</p>
                <input type="text" name="pregunta3" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 4 -->
            <div class="question-group">
                <p>4. ¿Qué método HTTP se utiliza para enviar datos de un formulario?</p>
                <select name="pregunta4">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                </select>
            </div>

            <!-- Pregunta 5 -->
            <div class="question-group">
                <p>5. ¿Cuál es el lenguaje estándar para el diseño de páginas web?</p>
                <input type="text" name="pregunta5" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 6 -->
            <div class="question-group">
                <p>6. ¿Cuántos registros devolverá el comando <code>LIMIT 10</code>?</p>
                <input type="number" name="pregunta6" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 7 -->
            <div class="question-group">
                <p>7. ¿Qué lenguaje se utiliza para estilizar las páginas web?</p>
                <input type="text" name="pregunta7" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 8 -->
            <div class="question-group">
                <p>8. ¿Qué clave sirve para relacionar tablas en una base de datos?</p>
                <input type="text" name="pregunta8" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 9 -->
            <div class="question-group">
                <p>9. ¿Qué tecnología usamos en este proyecto para desplegar el entorno?</p>
                <input type="text" name="pregunta9" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 10 -->
            <div class="question-group">
                <p>10. ¿Qué motor de bases de datos utilizamos en este proyecto?</p>
                <input type="text" name="pregunta10" placeholder="Escribe tu respuesta aquí">
            </div>

            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
