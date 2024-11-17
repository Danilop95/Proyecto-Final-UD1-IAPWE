<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM notas WHERE id_usuario = :id_usuario ORDER BY fecha DESC");
$stmt->execute(['id_usuario' => $_SESSION['user_id']]);
$notas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Notas</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('https://www.momentogp.com/wp-content/uploads/2018/08/859201_fernando-alonso-wallpaper.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            max-width: 700px;
            width: 90%;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }
        h1 {
            text-align: center;
            color: #FFD700;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            text-align: left;
            padding: 10px;
            border: 1px solid #FFD700;
        }
        th {
            background-color: #FFD700;
            color: black;
        }
        td {
            background-color: rgba(255, 255, 255, 0.1);
        }
        a {
            display: inline-block;
            text-align: center;
            padding: 10px 15px;
            background-color: #FFD700;
            color: black;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #E6B800;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Historial de Notas</h1>
        <?php if (count($notas) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notas as $nota): ?>
                    <tr>
                        <td><?php echo date("d-m-Y H:i", strtotime($nota['fecha'])); ?></td>
                        <td><?php echo $nota['nota']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px;">No hay notas registradas.</p>
        <?php endif; ?>
        <a href="inicio.php">Volver al inicio</a>
    </div>
</body>
</html>
