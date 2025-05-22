<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviar_email_recuperacion($destinatario_email, $destinatario_nombre, $asunto, $cuerpo_html, $cuerpo_alt = '')
{
    $mail = new PHPMailer(true);

    $smtp_host = 'smtp.gmail.com';
    $smtp_username = 'tractoresclavijo25@gmail.com';
    $smtp_password = 'ahhenwezieycsgtv';
    $smtp_secure = PHPMailer::ENCRYPTION_STARTTLS;
    $smtp_port = 587;

    $remitente_email = 'tractoresclavijo25@gmail.com';
    $remitente_nombre = 'Tractores Clavijo - Soporte';


    try {

        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = $smtp_secure;
        $mail->Port       = $smtp_port;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($remitente_email, $remitente_nombre);
        $mail->addAddress($destinatario_email, $destinatario_nombre);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo_html;
        $mail->AltBody = !empty($cuerpo_alt) ? $cuerpo_alt : strip_tags($cuerpo_html);

        echo "DEBUG: Configuración de PHPMailer completa. Intentando enviar...\n";

        if ($mail->send()) {
            echo "DEBUG: Correo enviado exitosamente por PHPMailer.\n</pre>";
            return true;
        } else {
            echo "DEBUG: mail->send() devolvió false, pero no lanzó excepción. ErrorInfo: " . $mail->ErrorInfo . "\n</pre>";
            error_log("PHPMailer send() false: " . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        echo "DEBUG: Excepción capturada por PHPMailer!\n";
        echo "DEBUG: El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}\n";
        echo "DEBUG: Detalles de la excepción: {$e->getMessage()}\n";
        echo "</pre>";
        error_log("Excepción PHPMailer: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
        exit;
    }
}
