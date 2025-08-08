<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer

/**
 * Envía un correo notificando los cambios realizados a una carga.
 * Incluye el código SAP si está disponible.
 * @param string $nombreRegistro
 * @param array $cambios Array de arrays: ['campo', 'anterior', 'nuevo']
 * @param string $usuario
 * @param string $fecha
 * @param array $destinatarios
 * @param PDO|null $pdo (opcional) Conexión PDO para consultar el código SAP
 * @return bool
 */
function enviarCorreoAsignacionPrecio($nombreRegistro, $precio_venta, $precio_compra, $costo, $usuario, $fecha, $destinatarios = [], $mensaje = null) {

    $subject = "Asignacion de precio al material $nombreRegistro";
    $body = "
        <p>Se ha realizado una asignacion de precio al material $nombreRegistro</p>
        <ul>
            <li><b>Precio de Venta:</b> $precio_venta</li>
            <li><b>Predio de Compra:</b> $precio_compra</li>
            <li><b>Costo:</b> $costo</li>
            <li><b>Usuario que asigno el precio:</b> $usuario</li>
            <li><b>Fecha de asignacion:</b> $fecha</li>
        </ul>
        <p>$mensaje</p>
        <p>Este es un mensaje automático. Por favor no responder.</p>
    ";

    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP (ajusta según tu servidor)
        $mail->isSMTP();
        $mail->Host = 'mail.fds.com.co';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones@fds.com.co';
        $mail->Password = 'S1ST3NFDS-';

        // Usa el puerto y cifrado correctos:
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Para SSL
        $mail->Port = 465;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom('notificaciones@fds.com.co', 'Sistema de Gestion de Materiales');
        foreach ($destinatarios as $dest) {
            $mail->addAddress($dest);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Debug para ver errores en el log de PHP
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'error_log';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error enviando correo: {$mail->ErrorInfo}");
        return false;
    }
}
?>