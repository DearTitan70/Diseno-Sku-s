
<?php
// ==========================
// INICIO DEL PROCESO DE LOGOUT
// ==========================

// 1. Iniciar la sesión para poder manipularla
session_start();

// 2. Eliminar todas las variables de sesión
// Esto limpia el array $_SESSION, eliminando cualquier dato almacenado en la sesión actual
$_SESSION = array();

// 3. Eliminar la cookie de sesión en el navegador del usuario
// Esto asegura que la sesión se destruya completamente, incluso si el usuario abre el sitio en otra pestaña o navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params(); // Obtener los parámetros actuales de la cookie de sesión
    setcookie(
        session_name(), // Nombre de la cookie de sesión
        '',             // Valor vacío para eliminarla
        time() - 42000, // Tiempo en el pasado para forzar su expiración
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruir la sesión en el servidor
// Esto elimina el archivo de sesión en el servidor
session_destroy();

// 5. Redirigir al usuario a la página de inicio de sesión o página principal
// Cambia la ruta si tu estructura de carpetas es diferente
header("location: ../sections/index.php");

// 6. Detener la ejecución del script después de la redirección
exit;

// ==========================
// FIN DEL PROCESO DE LOGOUT
// ==========================

?>

<!-- 
    El siguiente HTML solo se mostraría si por alguna razón falla la redirección.
    Normalmente, el usuario nunca verá este contenido porque el script termina con 'exit'.
-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrando Sesión...</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9f3e5;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <p>Cerrando sesión. Redirigiendo...</p>
</body>
</html>
