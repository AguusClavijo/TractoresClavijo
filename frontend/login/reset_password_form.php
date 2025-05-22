<?php
session_start();
require_once '../../backend/conexion/db_connect.php'; // Ajusta la ruta a tu db_connect.php

$selector = $_GET['selector'] ?? null;
$validator_hex = $_GET['validator'] ?? null; // El validador viene en hexadecimal desde el enlace

$errors = [];
$show_form = false;
$user_email_for_form = null; // Para mostrar el email si se desea

if (empty($selector) || empty($validator_hex)) {
    $_SESSION['auth_message'] = "Enlace de restablecimiento inválido o faltan parámetros.";
    $_SESSION['auth_message_type'] = 'danger';
    $_SESSION['show_popup'] = true; // Para que el popup se muestre en login.php
    $_SESSION['active_form'] = 'login'; // Mostrar el formulario de login
    header("Location: login.php"); // Redirigir a login.php
    exit();
}

// Convertir validador de hexadecimal a binario
$validator_bin = hex2bin($validator_hex);
if ($validator_bin === false) {
    $_SESSION['auth_message'] = "Token de validación corrupto.";
    $_SESSION['auth_message_type'] = 'danger';
    $_SESSION['show_popup'] = true;
    $_SESSION['active_form'] = 'login';
    header("Location: login.php");
    exit();
}

// Buscar el token en la base de datos
$sql_check_token = "SELECT * FROM password_reset_tokens WHERE selector = ? AND fecha_expiracion >= NOW() AND utilizado = FALSE";
$stmt_check = $conn->prepare($sql_check_token);

if ($stmt_check) {
    $stmt_check->bind_param("s", $selector);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($token_data = $result->fetch_assoc()) {
        // Token encontrado y no expirado/utilizado, ahora verificar el validador (token secreto)
        if (password_verify($validator_bin, $token_data['hashed_token'])) {
            // Token completamente válido, mostrar el formulario de reseteo
            $show_form = true;
            $_SESSION['reset_selector'] = $selector; // Guardar selector en sesión para el envío del nuevo form
            $_SESSION['reset_validator_hex'] = $validator_hex; // Guardar validador en sesión
            $user_email_for_form = htmlspecialchars($token_data['email']);
        } else {
            $errors[] = "El token de restablecimiento no es válido o ha sido manipulado.";
        }
    } else {
        $errors[] = "El enlace de restablecimiento ha expirado, ya fue utilizado o no es válido.";
    }
    $stmt_check->close();
} else {
    $errors[] = "Error al procesar la solicitud de restablecimiento.";
}

if (!empty($errors)) {
    $_SESSION['auth_message'] = $errors;
    $_SESSION['auth_message_type'] = 'danger';
    $_SESSION['show_popup'] = true;
    $_SESSION['active_form'] = 'forgot_password'; // Volver al form de "olvidé contraseña"
    header("Location: login.php");
    exit();
}

$conn->close();

if (!$show_form) {
    // Si por alguna razón no se debe mostrar el formulario pero no hubo errores para redirigir antes
    $_SESSION['auth_message'] = "No se puede procesar tu solicitud en este momento.";
    $_SESSION['auth_message_type'] = 'danger';
    $_SESSION['show_popup'] = true;
    $_SESSION['active_form'] = 'login';
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="login.css" />
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="../main/main.php">Tractores Clavijo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavReset" aria-controls="navbarNavReset" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavReset">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item"><a class="nav-link" href="login.php">Volver a Iniciar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="reset-password-page-container">
        <div class="form-box" style="transform: scale(1); opacity: 1; visibility: visible;">
            <h2>Restablecer Contraseña</h2>
            <?php if (isset($user_email_for_form)): ?>
                <p style="color: #ccc; font-size: 0.9em; margin-bottom: 15px; text-align:center;">
                    Restableciendo contraseña para: <strong><?php echo $user_email_for_form; ?></strong>
                </p>
            <?php endif; ?>

            <form action="../../backend/php/auth_handler.php" method="POST">
                <input type="hidden" name="reset_selector" value="<?php echo htmlspecialchars($selector); ?>">
                <input type="hidden" name="reset_validator_hex" value="<?php echo htmlspecialchars($validator_hex); ?>">

                <div class="contenedor-input">
                    <input type="password" name="new_password" id="new_password" required>
                    <label for="new_password">Nueva Contraseña</label>
                    <span class="icono"><i class="bi bi-lock-fill"></i></span>
                </div>
                <div class="contenedor-input">
                    <input type="password" name="confirm_new_password" id="confirm_new_password" required>
                    <label for="confirm_new_password">Confirmar Nueva Contraseña</label>
                    <span class="icono"><i class="bi bi-lock-fill"></i></span>
                </div>
                <button type="submit" name="confirm_reset_password_submit" class="btn-form-submit">Restablecer Contraseña</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>