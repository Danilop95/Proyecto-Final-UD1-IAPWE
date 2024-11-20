<?php
try {
    $pdo = new PDO(
        "mysql:host=" . getenv('MYSQL_HOST') . ";dbname=" . getenv('MYSQL_DATABASE') . ";charset=utf8mb4",
        getenv('MYSQL_USER'),
        getenv('MYSQL_PASSWORD')
    );
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
