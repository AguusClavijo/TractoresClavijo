<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviar_email_recuperacion($destinatario_email, $destinatario_nombre, $asunto, $cuerpo_html, $cuerpo_alt = '')
{
    $mail = new PHPMailer(true);

    // =========================================================================
    // CONFIGURACIÓN PARA MAILHOG (ENTORNO LOCAL)
    // Usamos 'localhost:1025' que es el puerto de captura SMTP mapeado por Docker
    // =========================================================================
    $smtp_host = 'localhost';             // Usamos localhost (donde corre Docker/Mailhog)
    $smtp_username = '';                  // Mailhog no requiere usuario
    $smtp_password = '';                  // Mailhog no requiere contraseña
    $smtp_secure = false;                 // Desactivamos la seguridad (es local)
    $smtp_port = 1025;                    // Puerto SMTP de Mailhog
    // =========================================================================

    $remitente_email = 'no-responder@tractoresclavijo.com'; // Puedes dejar tu email si quieres, pero no se enviará
    $remitente_nombre = 'Tractores Clavijo - Soporte';


    try {

        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = false;          // Importante: false para Mailhog
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = $smtp_secure;    // Importante: false (o PHPMailer::ENCRYPTION_NONE)
        $mail->Port       = $smtp_port;     // Importante: 1025
        $mail->SMTPAutoTLS = false;          // Deshabilitar AutoTLS
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($remitente_email, $remitente_nombre);
        $mail->addAddress($destinatario_email, $destinatario_nombre);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo_html;
        $mail->AltBody = !empty($cuerpo_alt) ? $cuerpo_alt : strip_tags($cuerpo_html);

        // Puedes comentar o dejar estas líneas DEBUG si te ayudan:
        // echo "DEBUG: Configuración de PHPMailer completa. Intentando enviar...\n";

        if ($mail->send()) {
            // echo "DEBUG: Correo enviado exitosamente por PHPMailer.\n</pre>";
            return true;
        } else {
            // echo "DEBUG: mail->send() devolvió false. ErrorInfo: " . $mail->ErrorInfo . "\n</pre>";
            error_log("PHPMailer send() false: " . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        // echo "DEBUG: Excepción capturada por PHPMailer!\n";
        // echo "DEBUG: El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}\n";
        // echo "DEBUG: Detalles de la excepción: {$e->getMessage()}\n";
        // echo "</pre>";
        error_log("Excepción PHPMailer: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
        return false; // Cambiamos 'exit' por 'return false' para manejar el error
    }
}