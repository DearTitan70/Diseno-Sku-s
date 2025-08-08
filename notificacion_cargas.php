<?php

require_once __DIR__ . '/vendor/autoload.php';

$db = new mysqli('localhost', 'services_cargamasiva', 'S1ST3NFDS-', 'services_cargamasiva');
if ($db->connect_error) {
    die("Error de conexi¨®n: " . $db->connect_error);
}

$sql = "SELECT * FROM catalogo_disenos WHERE (NOTIFICADO = 0 OR NOTIFICADO IS NULL) AND ESTADO IS NULL";
$result = $db->query($sql);

$cargas_no_notificadas = [];
while ($row = $result->fetch_assoc()) {
    $cargas_no_notificadas[] = $row;
}

if (count($cargas_no_notificadas) === 0) {
    exit("No hay cargas pendientes de notificar.\n");
}

$sql_usuarios = "SELECT correo FROM users WHERE notificar = 1 AND correo IS NOT NULL AND correo != ''";
$res_usuarios = $db->query($sql_usuarios);

$correos = [];
while ($row = $res_usuarios->fetch_assoc()) {
    $correos[] = $row['correo'];
}

if (count($correos) === 0) {
    exit("No hay usuarios a notificar.\n");
}

$cargas_por_nombre = [];

foreach ($cargas_no_notificadas as $carga) {
    $nombre = $carga['NOMBRE'];
    if (!isset($cargas_por_nombre[$nombre])) {
        $cargas_por_nombre[$nombre] = [];
    }
    $cargas_por_nombre[$nombre][] = $carga;
}

$mensaje = "Resumen de nuevas cargas al " . date('Y-m-d') . ":\n\n";
foreach ($cargas_por_nombre as $nombre => $cargas) {
    $cantidad = count($cargas);
    $primera_carga = $cargas[0]; 
    $mensaje .= "Nombre: {$nombre} ({$cantidad} " . ($cantidad > 1 ? 'registros' : 'registro') . ") | Primer registro por: {$primera_carga['usuario']} el {$primera_carga['fecha_creacion']}\n";
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.fds.com.co';
    $mail->SMTPAuth = true;
    $mail->Username = 'notificaciones@fds.com.co';
    $mail->Password = 'S1ST3NFDS-';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
    $mail->Port = 587; 

    $mail->setFrom('notificaciones@fds.com.co', 'Sistema de Notificaciones Gestion de SKU');
    foreach ($correos as $correo) {
        $mail->addAddress($correo);
    }
    $mail->Subject = 'Cargas nuevas al ' . date('Y-m-d');
    $mail->Body = $mensaje;

    $mail->send();
    echo "Notificaci¨®n enviada correctamente.\n";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}\n";
    exit(1);
}

$ids = array_column($cargas_no_notificadas, 'id');
$ids_str = implode(',', array_map('intval', $ids));
$db->query("UPDATE catalogo_disenos SET NOTIFICADO = 1 WHERE id IN ($ids_str)");

echo "Cargas marcadas como notificadas.\n";

?>