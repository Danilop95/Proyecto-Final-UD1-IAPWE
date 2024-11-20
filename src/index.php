<?php
// Iniciar sesión
session_start();

// Redirigir al archivo de login (Por no quitar el login)
header("Location: ./login.php");
exit;
