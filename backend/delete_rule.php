
<?php
// ===============================
// Configuración de la base de datos
// ===============================
$servername = "localhost";
$username = "services_cargamasiva";
$password = "S1ST3NFDS-"; 
$dbname = "services_cargamasiva";   

// =====================================================
// Validar que se ha recibido el ID del usuario a eliminar
// =====================================================
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Convertir el ID recibido a entero para evitar inyecciones y errores de tipo
    $userId = intval($_GET['id']);

    // ==========================================
    // Crear conexión a la base de datos MySQL
    // ==========================================
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ===================================================
    // Verificar si la conexión fue exitosa
    // ===================================================
    if ($conn->connect_error) {
        // Si hay error de conexión, terminar el script y mostrar mensaje
        die("Connection failed: " . $conn->connect_error);
    }

    // ===================================================
    // Preparar la consulta SQL para eliminar la regla
    // Usar consultas preparadas para mayor seguridad
    // ===================================================
    $sql = "DELETE FROM reglas_dependencia WHERE id = ?";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // ===================================================
    // Verificar si la preparación de la consulta fue exitosa
    // ===================================================
    if ($stmt === false) {
        // Si falla la preparación, terminar el script y mostrar mensaje de error
        die("Error preparing statement: " . $conn->error);
    }

    // ===================================================
    // Vincular el parámetro ID a la consulta preparada
    // "i" indica que el parámetro es un entero
    // ===================================================
    $stmt->bind_param("i", $userId);

    // ===================================================
    // Ejecutar la consulta preparada
    // ===================================================
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, redirigir con mensaje de éxito
        header("Location: ../sections/visualizar_reglas.php?status=success&message=Regla eliminada correctamente"); 
        exit(); 
    } else {
        // Si hubo un error al eliminar, redirigir con mensaje de error
        header("Location: ../sections/visualizar_reglas.php?status=error&message=Error al eliminar la regla: " . $stmt->error); 
        exit();
    }

    // ===================================================
    // Cerrar la consulta y la conexión a la base de datos
    // ===================================================
    $stmt->close();
    $conn->close();

} else {
    // ===================================================
    // Si no se proporcionó un ID válido, redirigir con error
    // ===================================================
    header("Location: visualizar_reglas.html?status=error&message=ID de usuario no proporcionado"); 
    exit();
}
?>
