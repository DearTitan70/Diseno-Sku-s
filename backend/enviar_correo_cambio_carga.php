<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer

/**
 * Envía un correo notificando los cambios realizados a una carga.
 * Incluye el código SAP si está disponible.
 * @param int $idCarga
 * @param array $cambios Array de arrays: ['campo', 'anterior', 'nuevo']
 * @param string $usuario
 * @param string $fecha
 * @param array $destinatarios
 * @param PDO|null $pdo (opcional) Conexión PDO para consultar el código SAP
 * @return bool
 */
function enviarCorreoCambioCarga($idCarga, $cambios, $usuario, $fecha, $destinatarios = [], $pdo = null) {
    if (empty($cambios)) return false;

    // Consultar el código SAP si se proporciona una conexión PDO
    $codigoSAP = null;
    if ($pdo instanceof PDO) {
        $stmt = $pdo->prepare("SELECT SAP FROM cargas WHERE id = ?");
        if ($stmt->execute([$idCarga])) {
            $codigoSAP = $stmt->fetchColumn();
        }
    }

    $tabla = "<table border='1' cellpadding='4' cellspacing='0'><tr><th>Campo</th><th>Antes</th><th>Después</th></tr>";
    foreach ($cambios as $c) {
        $tabla .= "<tr><td>{$c['campo']}</td><td>{$c['anterior']}</td><td>{$c['nuevo']}</td></tr>";
    }
    $tabla .= "</table>";

    // Construir el encabezado con ID y/o Código SAP
    $infoCarga = "<b>N° $idCarga</b>";
    if (!empty($codigoSAP)) {
        $infoCarga .= " (Código SAP: <b>$codigoSAP</b>)";
    }

    $subject = "Modificación de carga N° $idCarga en el sistema de creacion de SKU's";
    $body = "
        <p>Se ha realizado una modificación en la carga $infoCarga.</p>
        <ul>
            <li><b>Usuario:</b> $usuario</li>
            <li><b>Fecha:</b> $fecha</li>
        </ul>
        <p><b>Resumen de cambios:</b></p>
        $tabla
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

        $mail->setFrom('notificaciones@fds.com.co', 'Sistema de Cargas');
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