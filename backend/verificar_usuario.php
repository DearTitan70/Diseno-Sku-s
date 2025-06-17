
<?php
// ===============================
// Archivo: verificar_usuario.php
// Descripción: Verifica si un usuario existe en la base de datos por su ID.
// ===============================

// 1. Incluir el archivo de conexión a la base de datos
include '../conexion.php';

// 2. Configurar el encabezado para devolver la respuesta en formato JSON y con codificación UTF-8
header('Content-Type: application/json; charset=utf-8');

// 3. Obtener el ID del usuario desde la petición GET (si no existe, se asigna 0)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 4. Inicializar el array de respuesta con el valor por defecto (usuario no existe)
$response = ['existe' => false];

try {
    // 5. Preparar la consulta SQL usando prepared statements para evitar inyección SQL
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    // 6. Asociar el parámetro recibido ($id) a la consulta preparada
    $stmt->bind_param("i", $id);
    // 7. Ejecutar la consulta
    $stmt->execute();
    // 8. Obtener el resultado de la consulta
    $result = $stmt->get_result();
    
    // 9. Verificar si se encontró algún registro con ese ID
    if ($result->num_rows > 0) {
        $response['existe'] = true; // El usuario existe
    }
    
    // 10. Cerrar el statement y la conexión a la base de datos
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    // 11. Si ocurre algún error, agregar el mensaje de error al array de respuesta
    $response['error'] = $e->getMessage();
}

// 12. Devolver la respuesta como un objeto JSON
echo json_encode($response);
?>
