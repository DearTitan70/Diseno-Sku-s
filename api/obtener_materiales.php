<?php
include '../conexion.php';

$sql = 'SELECT NOMBRE FROM catalogo_disenos';

$result = $conn->query($sql);

$materiales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materiales[] = [
            "material" => $row["NOMBRE"]
        ];
    }
}

echo json_encode($materiales);
$conn->close();
?>
