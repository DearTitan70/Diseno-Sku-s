<?php

// Cargar Composer autoload para PHPMailer
require_once __DIR__ . '/vendor/autoload.php';

// Configuración de la base de datos
$db = new mysqli('localhost', 'services_cargamasiva', 'S1ST3NFDS-', 'services_cargamasiva');
if ($db->connect_error) {
    die("Error de conexión: " . $db->connect_error);
}

// 1. Obtener cargas no notificadas
$sql = "SELECT * FROM catalogo_disenos WHERE NOTIFICADO = 0 OR NOTIFICADO IS NULL";
$result = $db->query($sql);

$cargas_no_notificadas = [];
while ($row = $result->fetch_assoc()) {
    $cargas_no_notificadas[] = $row;
}

if (count($cargas_no_notificadas) === 0) {
    exit("No hay cargas pendientes de notificar.\n");
}

// 2. Obtener correos de usuarios a notificar
$sql_usuarios = "SELECT correo FROM users WHERE notificar = 1 AND correo IS NOT NULL AND correo != ''";
$res_usuarios = $db->query($sql_usuarios);

$correos = [];
while ($row = $res_usuarios->fetch_assoc()) {
    $correos[] = $row['correo'];
}

if (count($correos) === 0) {
    exit("No hay usuarios a notificar.\n");
}

// 3. Preparar el mensaje de notificación
$mensaje = "Cargas no notificadas al " . date('Y-m-d') . ":\n\n";
foreach ($cargas_no_notificadas as $carga) {
    $mensaje .= "ID: {$carga['id']} | Usuario: {$carga['usuario']} | Fecha: {$carga['fecha_creacion']} | Nombre: {$carga['NOMBRE']}\n";
}

// 4. Enviar notificación usando PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'mail.fds.com.co';
    $mail->SMTPAuth = true;
    $mail->Username = 'notificaciones@fds.com.co';
    $mail->Password = 'S1ST3NFDS-';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Opcional, según configuración del servidor
    $mail->Port = 587; // O el puerto que corresponda

    $mail->setFrom('notificaciones@fds.com.co', 'Sistema de Notificaciones Gestion de SKU');
    foreach ($correos as $correo) {
        $mail->addAddress($correo);
    }
    $mail->Subject = 'Cargas nuevas al ' . date('Y-m-d');
    $mail->Body = $mensaje;

    // Descomenta la siguiente línea si quieres ver el mensaje en pantalla para debug
    // echo "<pre>$mensaje</pre>";

    $mail->send();
    echo "Notificación enviada correctamente.\n";
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}\n";
    exit(1);
}

// 5. Marcar como notificadas
$ids = array_column($cargas_no_notificadas, 'id');
$ids_str = implode(',', array_map('intval', $ids));
$db->query("UPDATE catalogo_disenos SET NOTIFICADO = 1 WHERE id IN ($ids_str)");

echo "Cargas marcadas como notificadas.\n";

?>