
<?php
function getCargaById($id) {
    // Incluye la conexión a la base de datos
    require_once __DIR__ . '/../conexion.php'; // Ajusta el path si es necesario
    global $conn; // Asegúrate de que $conn esté disponible
    $conn->set_charset('utf8mb4');

    // Prepara la consulta
    $stmt = $conn->prepare("SELECT * FROM catalogo_disenos WHERE id = ?");
    if (!$stmt) {
        return false;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Obtiene el resultado
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    } else {
        $stmt->close();
        return false;
    }
}
?>
