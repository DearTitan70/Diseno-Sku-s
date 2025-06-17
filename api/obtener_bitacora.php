
<?php
// Incluye el archivo de conexión a la base de datos
include '../conexion.php';
$conn->set_charset('utf8mb4');

// Define la consulta SQL para obtener todas las reglas de dependencia
$sql = "SELECT usuario, fecha, registro_id, campo, valor_anterior, valor_nuevo
        FROM bitacora_cambios";

// Ejecuta la consulta SQL y almacena el resultado
$result = $conn->query($sql);

// Inicializa un arreglo para almacenar las reglas obtenidas
$cambios = [];

// Verifica si la consulta fue exitosa y si existen filas en el resultado
if ($result->num_rows > 0) {
    // Itera sobre cada fila del resultado
    while ($row = $result->fetch_assoc()) {
        // Agrega cada regla como un arreglo asociativo al arreglo principal
        $cambios[] = [
            "usuario" => $row["usuario"],
            "fecha" => $row["fecha"],
            "registro_id" => $row["registro_id"],
            "campo" => $row["campo"],
            "valor_anterior" => $row["valor_anterior"],
            "valor_nuevo" => $row["valor_nuevo"],
        ];
    }
}

// Convierte el arreglo de reglas a formato JSON y lo imprime como respuesta
echo json_encode($cambios);

// Cierra la conexión a la base de datos
$conn->close();
?>
