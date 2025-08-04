<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion.php';


$conn->set_charset('utf8mb4');

$sql = "SELECT c.id, c.SAP, c.NOMBRE
        FROM cargas c
        INNER JOIN (
            SELECT MAX(id) as max_id
            FROM cargas
            WHERE NOMBRE IS NOT NULL AND NOMBRE <> ''
            GROUP BY NOMBRE
        ) AS sub ON c.id = sub.max_id
        ORDER BY c.id DESC";
$result = $conn->query($sql);

$materiales = [];



if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materiales[] = $row;
    }
}

echo json_encode($materiales, JSON_UNESCAPED_UNICODE);

$conn->close();
?>

