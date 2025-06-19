<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer

/**
 * Envía un correo notificando la creación de nuevas cargas, mostrando los NOMBRES asignados.
 * @param array $nombresInsertados Array de nombres insertados en la BD.
 * @param string $usuario
 * @param string $fecha
 * @param array $destinatarios
 * @return bool
 */
function enviarCorreoNuevaCarga($nombresInsertados, $usuario, $fecha, $destinatarios = []) {
    if (empty($nombresInsertados)) return false;

    // Construir la lista de nombres en HTML
    $listaNombres = "<ul>";
    foreach ($nombresInsertados as $nombre) {
        // Si $nombre es array, toma el primer valor
        if (is_array($nombre)) {
            $nombre = reset($nombre);
        }
        $listaNombres .= "<li><b>Nombre:</b> " . htmlspecialchars((string)$nombre) . "</li>";
    }
    $listaNombres .= "</ul>";

    $subject = "NUEVA CARGA en el sistema de creación de SKU's";
    $body = "
        <p>Se ha realizado una <b>NUEVA CARGA</b> en el sistema.</p>
        <ul>
            <li><b>Usuario:</b> $usuario</li>
            <li><b>Fecha:</b> $fecha</li>
            <li><b>Cantidad de filas:</b> " . count($nombresInsertados) . "</li>
        </ul>
        <p><b>Nombres de los registros:</b></p>
        $listaNombres
        <a href='https://fds.com.co/diseno/sections/index.php'>Click aquí para ingresar al sistema</a>
        <p>Este es un mensaje automático. Por favor no responder.</p>
    ";

    $mail = new PHPMailer(true);
    try {
        // Configuración SMTP 
        $mail->isSMTP();
        $mail->Host = 'mail.fds.com.co';
        $mail->SMTPAuth = true;
        $mail->Username = 'notificaciones@fds.com.co';
        $mail->Password = 'S1ST3NFDS-';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Para SSL
        $mail->Port = 465;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom('notificaciones@fds.com.co', 'Sistema de Cargas');
        foreach ($destinatarios as $dest) {
            $mail->addAddress($dest);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Debug para ver errores en el log de PHP
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'error_log';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error enviando correo: {$mail->ErrorInfo}");
        return false;
    }
}
?>