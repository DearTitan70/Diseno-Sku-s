
<?php
// Establece el encabezado de respuesta como JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
require_once '../conexion.php'; 

// Decodifica el JSON recibido en el cuerpo de la petición
$input = json_decode(file_get_contents('php://input'), true);

// Verifica si se recibió el JSON y si es válido
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Datos no recibidos o JSON inválido.']);
    exit;
}

// Extrae y valida los datos recibidos del JSON
$id = isset($input['id']) ? intval($input['id']) : 0;
$nombre_regla = trim($input['nombre_regla'] ?? '');
$campo_destino = trim($input['campo_destino'] ?? '');
$es_activa = isset($input['es_activa']) ? intval($input['es_activa']) : 0;
$condiciones = $input['condiciones'] ?? null;
$valores_permitidos = $input['valores_permitidos'] ?? null;

// Verifica que los campos obligatorios no estén vacíos
// y que las condiciones y valores permitidos sean válidos
if (!$id || !$nombre_regla || !$campo_destino || $condiciones === null) {
    echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios.']);
    exit;
}

// Codifica las condiciones y valores permitidos a formato JSON para almacenarlos en la base de datos
$condiciones_json = json_encode($condiciones, JSON_UNESCAPED_UNICODE);
$valores_permitidos_json = json_encode($valores_permitidos, JSON_UNESCAPED_UNICODE);

// Prepara la consulta SQL para actualizar la regla en la base de datos
$query = "
    UPDATE reglas_dependencia
    SET nombre_regla = ?, 
        campo_destino = ?, 
        es_activa = ?, 
        condiciones = ?, 
        valores_permitidos = ?
    WHERE id = ?
";

// Prepara la consulta utilizando prepared statements para evitar inyecciones SQL
$stmt = $conn->prepare($query);

// Verifica si la preparación de la consulta fue exitosa
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta: ' . $conn->error]);
    exit;
}

// Vincula los parámetros a la consulta preparada
$stmt->bind_param(
    "ssissi", 
    $nombre_regla, 
    $campo_destino, 
    $es_activa, 
    $condiciones_json, 
    $valores_permitidos_json, 
    $id
);

// Ejecuta la consulta y verifica si la actualización fue exitosa
if ($stmt->execute()) {
    // Verifica si se actualizó alguna fila
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró la regla o no hubo cambios.']);
    }
} else {
    // Maneja errores de ejecución de la consulta
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $stmt->error]);
}

// Cierra la consulta y la conexión a la base de datos
$stmt->close();
$conn->close();
?>
