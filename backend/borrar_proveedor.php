
<?php
// Establece el encabezado de la respuesta como JSON
header('Content-Type: application/json');

// Obtiene y decodifica el JSON recibido en la petición
$input = json_decode(file_get_contents('php://input'), true);

// Verifica que se haya recibido un ID válido en el JSON
if (!isset($input['id']) || !is_numeric($input['id'])) {
    // Si el ID no es válido, responde con un mensaje de error y termina la ejecución
    echo json_encode([
        'success' => false,
        'message' => 'ID inválido.'
    ]);
    exit;
}

// Convierte el ID recibido a un entero para mayor seguridad
$id = intval($input['id']);

// Incluye el archivo de conexión a la base de datos
include '../conexion.php'; 

// Crea una nueva conexión a la base de datos usando los datos de conexión
$conn = new mysqli($host, $user, $pass, $db);

// Verifica si la conexión fue exitosa
if ($conn->connect_error) {
    // Si hay un error de conexión, responde con un mensaje de error y termina la ejecución
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos.'
    ]);
    exit;
}

// Prepara la consulta SQL para eliminar la cápsula con el ID proporcionado
$stmt = $conn->prepare("DELETE FROM proveedor WHERE id = ?");
if (!$stmt) {
    // Si la preparación de la consulta falla, responde con un mensaje de error y termina la ejecución
    echo json_encode([
        'success' => false,
        'message' => 'Error en la consulta SQL.'
    ]);
    $conn->close();
    exit;
}

// Asocia el parámetro ID a la consulta preparada
$stmt->bind_param("i", $id);

// Ejecuta la consulta SQL
if ($stmt->execute()) {
    // Verifica si alguna fila fue afectada (es decir, si se eliminó una cápsula)
    if ($stmt->affected_rows > 0) {
        // Si se eliminó correctamente, responde con éxito
        echo json_encode([
            'success' => true
        ]);
    } else {
        // Si no se encontró la cápsula con ese ID, responde con un mensaje de error
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró la cápsula con ese ID.'
        ]);
    }
} else {
    // Si la ejecución de la consulta falla, responde con un mensaje de error
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo borrar la cápsula.'
    ]);
}

// Cierra la consulta preparada y la conexión a la base de datos
$stmt->close();
$conn->close();
?>
