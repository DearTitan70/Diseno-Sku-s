<?php
require_once __DIR__ . '/auth.php';
require 'enviar_correo_asignacion_precio.php';
require_login_and_role();
date_default_timezone_set('America/Bogota');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id'], $data['nombre'], $data['precio_compra'], $data['costo'], $data['precio_venta'], $data['orden_compra'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$id = intval($data['id']);
$nombre = trim($data['nombre']);
$precio_compra = floatval($data['precio_compra']);
$costo = floatval($data['costo']);
$precio_venta = floatval($data['precio_venta']);
$aplicar_a_todos = !empty($data['aplicar_a_todos']);
$orden_compra = trim($data['orden_compra']);
$usuario = $_SESSION['nombre'];
$fecha = date("Y-m-d H:i:s", time());

require_once __DIR__ . '/../conexion.php'; // Aquí se define $conn (mysqli)

try {
    if ($aplicar_a_todos) {
        // Actualiza todos los registros con ese nombre
        $stmt = $conn->prepare("UPDATE catalogo_disenos SET precio_compra=?, costo=?, precio_venta=?, orden_compra=? WHERE NOMBRE=?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("dddss", $precio_compra, $costo, $precio_venta, $orden_compra, $nombre);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        // Obtener destinatarios
        $stmt = $conn->prepare("SELECT correo FROM users WHERE notificar = 1");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->execute();
        $result = $stmt->get_result();
        $destinatarios = [];
        while ($row = $result->fetch_assoc()) {
            $destinatarios[] = $row['correo'];
        }
        $stmt->close();
        
        $mensaje = 'Esta asignacion fue aplicada a todos los registros con el mismo nombre';
        
        enviarCorreoAsignacionPrecio($nombre, $precio_venta, $precio_compra, $costo, $usuario, $fecha, $destinatarios, $mensaje);

        echo json_encode(['success' => true, 'message' => "Precios asignados a $affected registro(s)."]);
    } else {
        // Actualiza solo el registro por ID
        $stmt = $conn->prepare("UPDATE catalogo_disenos SET precio_compra=?, costo=?, precio_venta=?, orden_compra=? WHERE id=?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("dddii", $precio_compra, $costo, $precio_venta, $orden_compra, $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        // Obtener destinatarios
        $stmt = $conn->prepare("SELECT correo FROM users WHERE notificar = 1");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->execute();
        $result = $stmt->get_result();
        $destinatarios = [];
        while ($row = $result->fetch_assoc()) {
            $destinatarios[] = $row['correo'];
        }
        $stmt->close();

        enviarCorreoAsignacionPrecio($nombre, $precio_venta, $precio_compra, $costo, $usuario, $fecha, $destinatarios);

        echo json_encode(['success' => true, 'message' => "Precio asignado al registro."]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => "Error en la base de datos: " . $e->getMessage()]);
}