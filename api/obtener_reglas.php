
<?php
// Incluye el archivo de conexión a la base de datos
include '../conexion.php';

// Asegura que la conexión use utf8mb4 (mejor que utf8)
$conn->set_charset('utf8mb4');

// Establece el encabezado antes de cualquier salida
header('Content-Type: application/json; charset=utf-8');

// Define la consulta SQL para obtener todas las reglas de dependencia
$sql = "SELECT id, nombre_regla, campo_destino, condiciones, valores_permitidos, es_activa, fecha_creacion 
        FROM reglas_dependencia";

// Ejecuta la consulta SQL y almacena el resultado
$result = $conn->query($sql);

// Inicializa un arreglo para almacenar las reglas obtenidas
$reglas = [];

// Verifica si la consulta fue exitosa y si existen filas en el resultado
if ($result && $result->num_rows > 0) {
    // Itera sobre cada fila del resultado
    while ($row = $result->fetch_assoc()) {
        // No es necesario utf8_encode si la conexión es utf8mb4
        $reglas[] = [
            "id" => $row["id"], 
            "nombre_regla" => $row["nombre_regla"],
            "campo_destino" => $row["campo_destino"],
            "condiciones" => $row["condiciones"],
            "valores_permitidos" => $row["valores_permitidos"],
            "es_activa" => $row["es_activa"],
            "fecha_creacion" => $row["fecha_creacion"]
        ];
    }
}

// Convierte el arreglo de reglas a formato JSON y lo imprime como respuesta
echo json_encode($reglas, JSON_UNESCAPED_UNICODE);

// Cierra la conexión a la base de datos
$conn->close();
?>
