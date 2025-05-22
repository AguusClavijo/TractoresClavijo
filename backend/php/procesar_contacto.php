<?php

session_start();

require_once '../conexion/db_connect.php';

$errors = [];
$success_message = "";

$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = "";
if (isset($script_path_parts[1]) && !empty($script_path_parts[1])) {
    $project_root_segment = '/' . $script_path_parts[1];
}

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
                $success_message = "¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.";
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
