
<?php
session_start(); // Inicia la sesión para manejar variables de sesión

// Definición de constantes para la conexión a la base de datos
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'services_cargamasiva'); 
define('DB_PASSWORD', 'S1ST3NFDS-');
define('DB_NAME', 'services_cargamasiva');

// Establece la conexión con la base de datos
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica si la conexión fue exitosa
if ($conn === false) {
    error_log("ERROR: Could not connect. " . mysqli_connect_error());
    $_SESSION['login_error'] = 'Error interno del servidor. Intente más tarde.';
    header("location: login.php");
    exit;
}

// Verifica si la solicitud es de tipo POST (formulario enviado)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtiene y limpia los datos enviados por el usuario
    $username_input = trim($_POST["username"]); 
    $password_input = $_POST["password"]; 

    // Valida que ambos campos no estén vacíos
    if (empty($username_input) || empty($password_input)) {
        $_SESSION['login_error'] = 'Por favor, ingrese usuario y contraseña.';
        header("location: ../sections/login.php");
        exit;
    }

    // Prepara la consulta SQL para buscar el usuario y su rol
    $sql = "SELECT u.id, u.username, u.password, u.nombre, u.apellido, u.role_id, r.name as role_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.username = ?";

    // Prepara la sentencia SQL para evitar inyección de SQL
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Asocia el parámetro de usuario a la consulta
        mysqli_stmt_bind_param($stmt, "s", $param_username); 
        $param_username = $username_input;

        // Ejecuta la consulta
        if (mysqli_stmt_execute($stmt)) {
            // Almacena el resultado de la consulta
            mysqli_stmt_store_result($stmt);

            // Verifica si se encontró exactamente un usuario con ese nombre
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Asocia las variables a los resultados de la consulta
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password_from_db, $nombre, $apellido, $role_id, $user_role_name);

                // Obtiene los valores de la consulta
                if (mysqli_stmt_fetch($stmt)) {
                    // Verifica que la contraseña ingresada coincida con la almacenada (hash)
                    if (password_verify($password_input, $hashed_password_from_db)) {

                        // Credenciales correctas: guarda información del usuario en la sesión
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['nombre'] = $nombre;
                        $_SESSION['apellido'] = $apellido;
                        $_SESSION['user_role_id'] = $role_id;       
                        $_SESSION['user_role_name'] = $user_role_name;

                        // Redirige al usuario a la página principal
                        header("location: ../sections/index.php");
                        exit; 
                    } else {
                        // Contraseña incorrecta
                        $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
                        header("location: ../sections/login.php");
                        exit;
                    }
                }
            } else {
                // Usuario no encontrado
                $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
                header("location: ../sections/login.php");
                exit;
            }
        } else {
            // Error al ejecutar la consulta
            error_log("ERROR: Could not execute query: " . mysqli_stmt_error($stmt));
            $_SESSION['login_error'] = 'Error interno del servidor. Intente más tarde.';
            header("location: ../sections/login.php");
            exit;
        }

        // Cierra la sentencia preparada
        mysqli_stmt_close($stmt);
    } else {
        // Error al preparar la consulta
        error_log("ERROR: Could not prepare query: " . mysqli_error($conn));
        $_SESSION['login_error'] = 'Error interno del servidor. Intente más tarde.';
        header("location: ../sections/login.php");
        exit;
    }

    // Cierra la conexión a la base de datos
    mysqli_close($conn);

} else {
    // Si la solicitud no es POST, redirige al formulario de login
    header("location: ../sections/login.php");
    exit;
}
?>
