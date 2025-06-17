
<?php
// Establece el encabezado para indicar que la respuesta será en formato JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
require_once '../conexion.php';

// Decodifica el JSON recibido en el cuerpo de la petición
$data = json_decode(file_get_contents('php://input'), true);

// Obtiene y limpia el nombre de la cápsula desde los datos recibidos
$nombre = isset($data['nombre']) ? trim($data['nombre']) : '';

// Valida que el nombre no esté vacío
if ($nombre === '') {
    // Si el nombre está vacío, retorna un mensaje de error y termina la ejecución
    echo json_encode(['success' => false, 'message' => 'El nombre es obligatorio.']);
    exit;
}

// Prepara una consulta SQL para verificar si ya existe una cápsula con el mismo nombre
$stmt = $conn->prepare("SELECT id FROM capsulas WHERE nombre = ?");

// Asocia el parámetro recibido ($nombre) a la consulta preparada
$stmt->bind_param('s', $nombre);

// Ejecuta la consulta
$stmt->execute();

// Almacena el resultado de la consulta para poder verificar el número de filas
$stmt->store_result();

// Verifica si ya existe una cápsula con ese nombre
if ($stmt->num_rows > 0) {
    // Si ya existe, retorna un mensaje de error y termina la ejecución
    echo json_encode(['success' => false, 'message' => 'Ya existe una cápsula con ese nombre.']);
    exit;
}

// Cierra la consulta anterior
$stmt->close();

// Prepara una nueva consulta SQL para insertar la nueva cápsula
$stmt = $conn->prepare("INSERT INTO capsulas (nombre, estado) VALUES (?, 1)");

// Asocia el parámetro recibido ($nombre) a la consulta de inserción
$stmt->bind_param('s', $nombre);

// Ejecuta la consulta de inserción
if ($stmt->execute()) {
    // Si la inserción fue exitosa, retorna un mensaje de éxito
    echo json_encode(['success' => true]);
} else {
    // Si hubo un error al guardar, retorna un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos.']);
}

// Cierra la consulta y la conexión a la base de datos
$stmt->close();
$conn->close();
?>
