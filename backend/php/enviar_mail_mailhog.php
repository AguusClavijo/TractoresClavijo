<?php

// Asegúrate de que esta ruta sea correcta para tu estructura de carpetas:
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Función para enviar un correo electrónico de notificación al administrador
 * usando Mailhog como servidor SMTP de prueba.
 * * @param array $data Datos de la consulta (nombre, email, asunto, mensaje)
 * @return bool True si el correo se envió a Mailhog, False en caso de error.
 */
function enviar_notificacion_a_mailhog(array $data)
{
    // Datos del administrador (a quien se le notifica la consulta)
    $admin_email = 'administrador@tractoresclavijo.com';
    $admin_nombre = 'Administrador Web';

    $mail = new PHPMailer(true);

    try {
        // =========================================================================
        // CONFIGURACIÓN DEDICADA PARA MAILHOG (ENTORNO LOCAL)
        // =========================================================================
        $mail->isSMTP();
        $mail->Host       = 'mailhog';    // Mapeado a la IP de tu máquina
        $mail->SMTPAuth   = false;          // Mailhog no requiere autenticación
        $mail->Port       = 1025;           // Puerto SMTP de Mailhog
        $mail->SMTPSecure = false;          // Deshabilitar la seguridad (es local)
        $mail->SMTPAutoTLS = false;         // Deshabilitar AutoTLS
        $mail->CharSet    = 'UTF-8';
        
        // OPCIONAL: Muestra la depuración en pantalla. ¡Quítalo cuando funcione!
        // $mail->SMTPDebug = 4;
        // $mail->Debugoutput = 'html'; 
        // =========================================================================

        // Remitente (la web) y Destinatario (el administrador)
        $mail->setFrom('no-responder@tractoresclavijo.com', 'Tractores Clavijo Web');
        $mail->addAddress($admin_email, $admin_nombre);

        // Contenido del Correo
        $mail->isHTML(true);
        $mail->Subject = 'NUEVA CONSULTA WEB: ' . $data['asunto'];
        
        $body_html = "
            <html><body>
            <h2>Nueva Consulta de Cliente</h2>
            <p>Se ha recibido una nueva consulta a través del formulario de contacto.</p>
            <ul>
                <li><strong>Nombre:</strong> " . htmlspecialchars($data['nombre_completo']) . "</li>
                <li><strong>Email:</strong> " . htmlspecialchars($data['email']) . "</li>
                <li><strong>Teléfono:</strong> " . htmlspecialchars($data['telefono'] ?? 'N/A') . "</li>
                <li><strong>Asunto:</strong> " . htmlspecialchars($data['asunto']) . "</li>
            </ul>
            <p><strong>Mensaje:</strong></p>
            <p style='border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9;'>" . nl2br(htmlspecialchars($data['mensaje'])) . "</p>
            <p>Esta notificación fue enviada a Mailhog (SMTP: localhost:1025).</p>
            </body></html>";

        $mail->Body    = $body_html;
        $mail->AltBody = "Nueva Consulta: Nombre: {$data['nombre_completo']}, Asunto: {$data['asunto']}. Mensaje: {$data['mensaje']}";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer a Mailhog falló. Error: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage());
        return false;
    }
}