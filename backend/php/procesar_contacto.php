<?php

session_start();

require_once '../conexion/db_connect.php';
require_once 'enviar_mail_mailhog.php';

$errors = [];
$success_message = "";

$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = '';

$contact_page_url = $project_root_segment . "/frontend/contact/contact.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre_completo = isset($_POST['contactName']) ? trim($_POST['contactName']) : '';
    $email = isset($_POST['contactEmail']) ? trim($_POST['contactEmail']) : '';
    $telefono = isset($_POST['contactPhone']) ? trim($_POST['contactPhone']) : null;
    $asunto = isset($_POST['contactSubject']) ? trim($_POST['contactSubject']) : '';
    $mensaje = isset($_POST['contactMessage']) ? trim($_POST['contactMessage']) : '';
    $ip_origen = $_SERVER['REMOTE_ADDR'];

    if (empty($nombre_completo)) {
        $errors[] = "El nombre completo es obligatorio.";
    }
    if (empty($email)) {
        $errors[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El formato del correo electrónico no es válido.";
    }
    if (empty($asunto)) {
        $errors[] = "El asunto es obligatorio.";
    }
    if (empty($mensaje)) {
        $errors[] = "El mensaje es obligatorio.";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO consultas_contacto (nombre_completo, email, telefono, asunto, mensaje, ip_origen, estado_consulta) 
                VALUES (?, ?, ?, ?, ?, ?, 'Pendiente')";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "ssssss",
                $nombre_completo,
                $email,
                $telefono,
                $asunto,
                $mensaje,
                $ip_origen
            );

            if ($stmt->execute()) {
                $success_message = "¡Gracias por tu mensaje! Procesaremos tu solicitud y recibirás una respuesta automática pronto.";
                $n8n_webhook_url = 'http://n8n:5678/webhook/03e9ade7-bdf4-4e59-adfd-a0811f97601e'; 
                
                $data_for_n8n = json_encode([
                    'id_consulta'     => $conn->insert_id,
                    'nombre_completo' => $nombre_completo,
                    'email'           => $email,
                    'telefono'        => $telefono,
                    'asunto'          => $asunto,
                    'mensaje'         => $mensaje,
                    'ip_origen'       => $ip_origen,
                ]);
                
                $ch = curl_init($n8n_webhook_url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_for_n8n);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_for_n8n)
                ));
                
                curl_setopt($ch, CURLOPT_TIMEOUT, 1);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
                
                curl_exec($ch);
                curl_close($ch);

                $datos_consulta_email = ['nombre_completo' => $nombre_completo,'email' => $email,'telefono' => $telefono,'asunto' => $asunto,'mensaje' => $mensaje];

                enviar_notificacion_a_mailhog($datos_consulta_email);
                
            } else {
                $errors[] = "Error al guardar tu mensaje: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        } else {
            $errors[] = "Error al preparar la consulta: " . htmlspecialchars($conn->error);
        }
    }

    $conn->close();

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
    }
    if (!empty($success_message)) {
        $_SESSION['form_success'] = $success_message;
    }

    header("Location: " . $contact_page_url);
    exit();
} else {
    header("Location: " . $contact_page_url);
    exit();
}
