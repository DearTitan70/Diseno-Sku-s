<?php
include '../conexion.php';

// First query to get users
$sql = "SELECT id, nombre, estado, created_at
        FROM proveedor";

$result = $conn->query($sql);

$capsulas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $capsulas[] = [
            "id" => $row["id"], 
            "nombre" => $row["nombre"],
            "estado" => $row["estado"],
            "created_at" => $row["created_at"]
        ];
    }
}

echo json_encode($capsulas);
$conn->close();
?>