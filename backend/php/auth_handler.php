<?php
session_start();

require_once '../conexion/db_connect.php';
require_once './mailer_config.php';

function generate_token($length = 32)
{
    return bin2hex(random_bytes($length));
}

function redirect_with_message_auth($url, $message, $type = 'danger', $form_to_show = 'login')
{
    $_SESSION['auth_message'] = $message;
    $_SESSION['auth_message_type'] = $type;
    if ($type === 'danger' || !isset($_SESSION['form_data'])) {
        $_SESSION['form_data'] = $_POST;
    } elseif ($type === 'success') {
        unset($_SESSION['form_data']);
    }
    $_SESSION['active_form'] = $form_to_show;
    $_SESSION['show_popup'] = true;

    ob_start();
    header("Location: " . $url);
    ob_end_flush();
    exit();
}

$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = '';
$login_page_url = $project_root_segment . "/frontend/login/login.php";
$main_page_url = $project_root_segment . "/frontend/main/main.php";
$reset_password_form_page_url = $project_root_segment . "/frontend/login/reset_password_form.php";

if (isset($_POST['register_submit'])) {
    $nombre = trim($_POST['register_nombre'] ?? '');
    $apellido = trim($_POST['register_apellido'] ?? '');
    $email = trim($_POST['register_email'] ?? '');
    $password = $_POST['register_password'] ?? '';
    $password_confirm = $_POST['register_password_confirm'] ?? '';
    $terms_accepted = isset($_POST['terms']);
    $errors = [];

    if (empty($nombre)) $errors[] = "El nombre es obligatorio.";
    if (empty($apellido)) $errors[] = "El apellido es obligatorio.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "El formato del email no es válido.";
    if (strlen($password) < 6) $errors[] = "La contraseña debe tener al menos 6 caracteres.";
    if ($password !== $password_confirm) $errors[] = "Las contraseñas no coinciden.";
    if (!$terms_accepted) $errors[] = "Debes aceptar los términos y condiciones.";

    if (empty($errors)) {
        $sql_check_email = "SELECT id_cliente FROM clientes WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check_email);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) $errors[] = "Este correo electrónico ya está registrado.";
        $stmt_check->close();
    }

    if (empty($errors)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql_register = "INSERT INTO clientes (nombre, apellido, email, password, activo) VALUES (?, ?, ?, ?, TRUE)";
        $stmt_register = $conn->prepare($sql_register);
        $stmt_register->bind_param("ssss", $nombre, $apellido, $email, $password_hashed);
        if ($stmt_register->execute()) {
            redirect_with_message_auth($login_page_url, "¡Registro exitoso! Ahora puedes iniciar sesión.", 'success', 'login');
        } else {
            $errors[] = "Error al registrar el usuario.";
        }
        $stmt_register->close();
    }
    if (!empty($errors)) redirect_with_message_auth($login_page_url, $errors, 'danger', 'register');
    $conn->close();
    exit();
} elseif (isset($_POST['login_submit'])) {
    $email = trim($_POST['login_email'] ?? '');
    $password = $_POST['login_password'] ?? '';
    $remember_me = isset($_POST['rememberMe']);
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "El email no es válido.";
    if (empty($password)) $errors[] = "La contraseña es obligatoria.";

    if (empty($errors)) {
        $sql_login = "SELECT id_cliente, nombre, apellido, email, password, activo FROM clientes WHERE email = ?";
        $stmt_login = $conn->prepare($sql_login);
        if ($stmt_login) {
            $stmt_login->bind_param("s", $email);
            $stmt_login->execute();
            $result = $stmt_login->get_result();

            if ($user = $result->fetch_assoc()) {
                if ($user['activo'] == 1 && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id_cliente'];
                    $_SESSION['user_nombre'] = $user['nombre'];
                    $_SESSION['user_email'] = $user['email'];
                    unset($_SESSION['form_data']);

                    if ($remember_me) {
                        $selector = generate_token(16);
                        $validator = generate_token(32);
                        $hashed_validator = password_hash($validator, PASSWORD_DEFAULT);
                        $expires_seconds = 60 * 60 * 24 * 30;
                        $expires_datetime = date('Y-m-d H:i:s', time() + $expires_seconds);

                        $sql_delete_old_tokens = "DELETE FROM auth_tokens WHERE id_cliente = ?";
                        $stmt_delete = $conn->prepare($sql_delete_old_tokens);
                        if ($stmt_delete) {
                            $stmt_delete->bind_param("i", $user['id_cliente']);
                            $stmt_delete->execute();
                            $stmt_delete->close();
                        }

                        $sql_insert_token = "INSERT INTO auth_tokens (id_cliente, selector, hashed_validator, fecha_expiracion) VALUES (?, ?, ?, ?)";
                        $stmt_token = $conn->prepare($sql_insert_token);
                        if ($stmt_token) {
                            $stmt_token->bind_param("isss", $user['id_cliente'], $selector, $hashed_validator, $expires_datetime);
                            $stmt_token->execute();
                            $stmt_token->close();

                            setcookie('remember_me_selector', $selector, time() + $expires_seconds, "/", "", isset($_SERVER["HTTPS"]), true);
                            setcookie('remember_me_validator', $validator, time() + $expires_seconds, "/", "", isset($_SERVER["HTTPS"]), true);
                        }
                    }
                    $conn->close();
                    header("Location: " . $main_page_url);
                    exit();
                } else {
                    $errors[] = "Email o contraseña incorrectos, o la cuenta está inactiva.";
                }
            } else {
                $errors[] = "Email o contraseña incorrectos.";
            }
            $stmt_login->close();
        } else {
            $errors[] = "Error preparando el inicio de sesión.";
        }
    }
    redirect_with_message_auth($login_page_url, $errors, 'danger', 'login');
    $conn->close();
    exit();
} elseif (isset($_POST['forgot_password_submit'])) {
    $email = trim($_POST['forgot_email'] ?? '');
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Por favor, ingresa un correo electrónico válido.";
    }

    if (empty($errors)) {
        $sql_find_user = "SELECT id_cliente, email, nombre FROM clientes WHERE email = ? AND activo = 1";
        $stmt_find = $conn->prepare($sql_find_user);
        if ($stmt_find) {
            $stmt_find->bind_param("s", $email);
            $stmt_find->execute();
            $result = $stmt_find->get_result();

            if ($user = $result->fetch_assoc()) {
                $selector = generate_token(16);
                $token_plain = generate_token(32);
                $hashed_token = password_hash($token_plain, PASSWORD_DEFAULT);
                $expires_seconds = 60 * 60 * 1;
                $expires_datetime = date('Y-m-d H:i:s', time() + $expires_seconds);
                $utilizado_default = 0;

                $sql_insert_reset = "INSERT INTO password_reset_tokens (id_cliente, email, selector, hashed_token, fecha_expiracion, utilizado) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert_reset = $conn->prepare($sql_insert_reset);

                if ($stmt_insert_reset) {
                    $stmt_insert_reset->bind_param("issssi", $user['id_cliente'], $user['email'], $selector, $hashed_token, $expires_datetime, $utilizado_default);
                    if ($stmt_insert_reset->execute()) {
                        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                        $host = $_SERVER['HTTP_HOST'];
                        $reset_link = $protocol . $host . $reset_password_form_page_url . "?selector=" . $selector . "&validator=" . bin2hex($token_plain);

                        $asunto_email = "Restablecimiento de contraseña - Tractores Clavijo";
                        $cuerpo_html_email = "<h1>Restablecimiento de Contraseña</h1><p>Hola " . htmlspecialchars($user['nombre']) . ",</p><p>Para restablecer tu contraseña, haz clic en el siguiente enlace (válido por 1 hora):</p><p><a href='" . $reset_link . "'>" . $reset_link . "</a></p>";

                        if (enviar_email_recuperacion($user['email'], $user['nombre'], $asunto_email, $cuerpo_html_email)) {
                            redirect_with_message_auth($login_page_url, "Si tu correo está registrado, recibirás instrucciones. Revisa tu spam.", 'success', 'forgot_password');
                        } else {
                            $errors[] = "No se pudo enviar el correo de recuperación. Intenta más tarde.";
                        }
                    } else {
                        $errors[] = "No se pudo guardar el token de reseteo.";
                    }
                    $stmt_insert_reset->close();
                } else {
                    $errors[] = "Error preparando la inserción del token.";
                }
            } else {
                redirect_with_message_auth($login_page_url, "Si tu correo está registrado, recibirás instrucciones. Revisa tu spam.", 'success', 'forgot_password');
            }
            $stmt_find->close();
        } else {
            $errors[] = "Error preparando la búsqueda de usuario.";
        }
    }
    if (!empty($errors)) redirect_with_message_auth($login_page_url, $errors, 'danger', 'forgot_password');
    $conn->close();
    exit();
} elseif (isset($_POST['confirm_reset_password_submit'])) {
    $selector = $_POST['reset_selector'] ?? null;
    $validator_hex = $_POST['reset_validator_hex'] ?? null;
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';
    $errors = [];

    if (empty($selector) || empty($validator_hex)) $errors[] = "Información de reseteo inválida.";
    if (strlen($new_password) < 6) $errors[] = "La nueva contraseña debe tener al menos 6 caracteres.";
    if ($new_password !== $confirm_new_password) $errors[] = "Las nuevas contraseñas no coinciden.";

    if (empty($errors)) {
        $validator_bin = hex2bin($validator_hex);
        if ($validator_bin === false) {
            $errors[] = "Token de validación corrupto.";
        } else {
            $sql_check_token = "SELECT * FROM password_reset_tokens WHERE selector = ? AND fecha_expiracion >= NOW() AND utilizado = FALSE";
            $stmt_check = $conn->prepare($sql_check_token);
            $stmt_check->bind_param("s", $selector);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($token_data = $result->fetch_assoc()) {
                if (password_verify($validator_bin, $token_data['hashed_token'])) {
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                    $id_cliente = $token_data['id_cliente'];

                    $sql_update_pass = "UPDATE clientes SET password = ? WHERE id_cliente = ?";
                    $stmt_update = $conn->prepare($sql_update_pass);
                    $stmt_update->bind_param("si", $new_password_hashed, $id_cliente);
                    if ($stmt_update->execute()) {
                        $sql_invalidate_token = "UPDATE password_reset_tokens SET utilizado = TRUE WHERE id_reset_token = ?";
                        $stmt_invalidate = $conn->prepare($sql_invalidate_token);
                        $stmt_invalidate->bind_param("i", $token_data['id_reset_token']);
                        $stmt_invalidate->execute();
                        $stmt_invalidate->close();

                        redirect_with_message_auth($login_page_url, "Tu contraseña ha sido restablecida. Ya puedes iniciar sesión.", 'success', 'login');
                    } else {
                        $errors[] = "No se pudo actualizar la contraseña.";
                    }
                    $stmt_update->close();
                } else {
                    $errors[] = "Token de restablecimiento inválido.";
                }
            } else {
                $errors[] = "El enlace de restablecimiento ha expirado o no es válido.";
            }
            $stmt_check->close();
        }
    }
    if (!empty($errors)) redirect_with_message_auth($login_page_url, $errors, 'danger', 'forgot_password');
    $conn->close();
    exit();
}


if ($_SERVER["REQUEST_METHOD"] !== "POST" && !isset($_SESSION['user_id']) && isset($_COOKIE['remember_me_selector']) && isset($_COOKIE['remember_me_validator'])) {
    if (!$conn || !$conn->ping()) {
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        if ($conn) mysqli_set_charset($conn, "utf8mb4");
        else die("Error reconectando a BD para autologin.");
    }
}


if (!headers_sent()) {
    header("Location: " . $login_page_url);
    exit();
}

if (isset($conn) && $conn instanceof mysqli && $conn->ping()) {
    $conn->close();
}
