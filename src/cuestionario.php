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
        'pregunta1' => '2005',
        'pregunta2' => 'Renault',
        'pregunta3' => 'España',
        'pregunta4' => 'Aston Martin',
        'pregunta5' => '33',
        'pregunta6' => '2',
        'pregunta7' => 'Minardi',
        'pregunta8' => '2003',
        'pregunta9' => 'F1',
        'pregunta10' => 'Fernando Alonso Díaz'
    ];

    // Revisar respuestas
    foreach ($correctAnswers as $key => $value) {
        if (isset($_POST[$key]) && trim($_POST[$key]) === $value) {
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
    <title>Cuestionario sobre Fernando Alonso</title>
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
        .question-group input[type="radio"] {
            width: auto;
            margin-right: 10px;
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
        <h1>Cuestionario sobre Fernando Alonso</h1>
        <form method="POST" action="cuestionario.php">
            <!-- Pregunta 1 -->
            <div class="question-group">
                <p>1. ¿En qué año ganó Fernando Alonso su primer campeonato mundial de Fórmula 1?</p>
                <input type="radio" name="pregunta1" value="2005" id="2005"><label for="2005">2005</label><br>
                <input type="radio" name="pregunta1" value="2006" id="2006"><label for="2006">2006</label>
            </div>

            <!-- Pregunta 2 -->
            <div class="question-group">
                <p>2. ¿Con qué equipo ganó Fernando Alonso sus dos campeonatos mundiales?</p>
                <select name="pregunta2">
                    <option value="Ferrari">Ferrari</option>
                    <option value="Renault">Renault</option>
                    <option value="McLaren">McLaren</option>
                </select>
            </div>

            <!-- Pregunta 3 -->
            <div class="question-group">
                <p>3. ¿De qué país es Fernando Alonso?</p>
                <input type="text" name="pregunta3" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 4 -->
            <div class="question-group">
                <p>4. ¿Con qué equipo compite Fernando Alonso en 2023?</p>
                <input type="radio" name="pregunta4" value="Aston Martin" id="aston"><label for="aston">Aston Martin</label><br>
                <input type="radio" name="pregunta4" value="Mercedes" id="mercedes"><label for="mercedes">Mercedes</label>
            </div>

            <!-- Pregunta 5 -->
            <div class="question-group">
                <p>5. ¿Cuál es el famoso número de victorias que sus fans esperan que alcance?</p>
                <input type="number" name="pregunta5" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 6 -->
            <div class="question-group">
                <p>6. ¿Cuántos campeonatos mundiales ha ganado Fernando Alonso?</p>
                <input type="radio" name="pregunta6" value="2" id="2"><label for="2">2</label><br>
                <input type="radio" name="pregunta6" value="1" id="1"><label for="1">1</label>
            </div>

            <!-- Pregunta 7 -->
            <div class="question-group">
                <p>7. ¿Con qué equipo debutó Fernando Alonso en la Fórmula 1?</p>
                <input type="text" name="pregunta7" placeholder="Escribe tu respuesta aquí">
            </div>

            <!-- Pregunta 8 -->
            <div class="question-group">
                <p>8. ¿En qué año logró su primera victoria en Fórmula 1?</p>
                <input type="radio" name="pregunta8" value="2003" id="2003"><label for="2003">2003</label><br>
                <input type="radio" name="pregunta8" value="2004" id="2004"><label for="2004">2004</label>
            </div>

            <!-- Pregunta 9 -->
            <div class="question-group">
                <p>9. ¿En qué categoría compite Fernando Alonso actualmente?</p>
                <select name="pregunta9">
                    <option value="F1">F1</option>
                    <option value="IndyCar">IndyCar</option>
                    <option value="WEC">WEC</option>
                </select>
            </div>

            <!-- Pregunta 10 -->
            <div class="question-group">
                <p>10. ¿Cuál es el nombre completo de Fernando Alonso?</p>
                <input type="text" name="pregunta10" placeholder="Escribe tu respuesta aquí">
            </div>

            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
