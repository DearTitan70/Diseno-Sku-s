<?php
$host = "";
$user = "";
$pass = "";
$db   = "";

// Procedural fallback + OOP para mejor control
$conn = mysqli_connect($host, $user, $pass, $db);
$conn->set_charset('utf8mb4');

// Manejo de error
if (!$conn) {
    error_log("ERROR de conexión: " . mysqli_connect_error());
    die("Error de conexión a la base de datos.");
}
