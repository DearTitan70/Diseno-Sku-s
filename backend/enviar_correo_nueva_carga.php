
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer

/**
 * Envía un correo notificando la creación de nuevas cargas, mostrando solo los IDs asignados.
 * @param array $idsInsertados Array de IDs autoincrementales asignados por la BD.
 * @param string $usuario
 * @param string $fecha
 * @param array $destinatarios
 * @return bool
 */
function enviarCorreoNuevaCarga($idsInsertados, $usuario, $fecha, $destinatarios = []) {
    if (empty($idsInsertados)) return false;

    // Construir la lista de IDs en HTML (robusto para arrays o escalares)
    $listaIds = "<ul>";
    foreach ($idsInsertados as $id) {
        // Si $id es array, toma el primer valor
        if (is_array($id)) {
            $id = reset($id);
        }
        $listaIds .= "<li><b>ID:</b> " . htmlspecialchars((string)$id) . "</li>";
    }
    $listaIds .= "</ul>";

    $subject = "NUEVA CARGA en el sistema de creación de SKU's";
    $body = "
        <p>Se ha realizado una <b>NUEVA CARGA</b> en el sistema.</p>
        <ul>
            <li><b>Usuario:</b> $usuario</li>
            <li><b>Fecha:</b> $fecha</li>
            <li><b>Cantidad de filas:</b> " . count($idsInsertados) . "</li>
        </ul>
        <p><b>IDs asignados por la base de datos:</b></p>
        $listaIds
        <a href='https://fds.com.co/diseno/sections/index.php'>Click aqui para ingresar al sistema</a>
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
