
<?php
// Establece el encabezado de la respuesta como JSON
header('Content-Type: application/json');

// Incluye el archivo de conexión a la base de datos
require_once '../conexion.php';

try {
    // Prepara la consulta SQL para obtener solo las cápsulas activas (estado = 1), ordenadas alfabéticamente por nombre
    $sql = "SELECT id, nombre FROM proveedor WHERE estado = 1 ORDER BY nombre ASC";
    $result = $conn->query($sql);

    // Inicializa un arreglo para almacenar las cápsulas obtenidas
    $capsulas = [];

    // Si la consulta fue exitosa, recorre los resultados y los agrega al arreglo
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $capsulas[] = $row;
        }
        // Devuelve el arreglo de cápsulas en formato JSON
        echo json_encode($capsulas);
    } else {
        // Si ocurre un error en la consulta, responde con código 500 y un mensaje de error
        http_response_code(500);
        echo json_encode(['error' => 'Error en la consulta']);
    }
} catch (Exception $e) {
    // Si ocurre una excepción, responde con código 500 y un mensaje de error genérico
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener cápsulas']);
}
?>
