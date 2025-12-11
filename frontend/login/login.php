<?php session_start();
$cart_count = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
  foreach ($_SESSION['carrito'] as $item_cart) {
    if (isset($item_cart['cantidad'])) {
      $cart_count += $item_cart['cantidad']; // Suma las cantidades de cada producto
    }
  }
}
?>

<?php
// Poner esto al inicio de tus archivos PHP o en un config.php
// session_start(); // Ya lo tienes
$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = '';
// Si tu proyecto está en localhost/TractoresClavijo/, $project_root_segment será /TractoresClavijo
// Si tu proyecto está en localhost/, $project_root_segment será ''
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar Sesión - Tractores Clavijo</title>
  <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/81448e9ee5.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="login.css" />
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Tractores Clavijo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavGlobal" aria-controls="navbarNavGlobal" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavGlobal">
          <ul class="navbar-nav align-items-center">
            <li class="nav-item">
              <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'main.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'about.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/about/about.php">Sobre Nosotros</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'tractors.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/tractors/tractors.php">Tractores</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'contact.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/contact/contact.php">Contacto</a>
            </li>

            <?php if (isset($_SESSION['user_id'])): ?>
              <li class="nav-item dropdown ms-lg-3">
                <a class="nav-link dropdown-toggle btn btn-user-dropdown" href="#" id="navbarUserDropdownGlobal" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?> <i class="bi bi-person-circle ms-1"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdownGlobal">
                  <li><a class="dropdown-item" href="../perfil/perfil.php">Mi Perfil</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a class="dropdown-item" href="<?php echo $project_root_segment; ?>/backend/php/logout.php">Cerrar Sesión</a></li>
                </ul>
              </li>
            <?php else: ?>
              <li class="nav-item ms-lg-3">
                <?php // En login.php, este es un botón. En otras páginas, es un enlace.
                if (basename($_SERVER['PHP_SELF']) === 'login.php') : ?>
                  <button class="btn btn-login" id="loginPopupBtn" type="button">Iniciar Sesión</button>
                <?php else: ?>
                  <a class="btn btn-login" href="<?php echo $project_root_segment; ?>/frontend/login/login.php">Iniciar Sesión</a>
                <?php endif; ?>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <div class="form-popup-container <?php echo (isset($_SESSION['show_popup']) && $_SESSION['show_popup']) || isset($_SESSION['auth_message_type']) ? 'active' : '';
                                    unset($_SESSION['show_popup']); ?>" id="formPopupContainer">
    <div class="form-box">
      <span class="icono-cerrar" id="closePopupIcon"><i class="bi bi-x-lg"></i></span>

      <?php if (isset($_SESSION['auth_message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['auth_message_type']; ?> alert-dismissible fade show mb-3" role="alert" style="font-size: 0.9rem; padding: 0.75rem 1rem;">
          <?php if ($_SESSION['auth_message_type'] === 'danger' && is_array($_SESSION['auth_message'])): ?>
            <!-- <strong>Error:</strong><br> -->
            <ul>
              <?php foreach ($_SESSION['auth_message'] as $error_msg): ?>
                <li style="margin-bottom: 0.25rem;"><?php echo htmlspecialchars($error_msg); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <?php echo htmlspecialchars($_SESSION['auth_message']); ?>
          <?php endif; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
        </div>
        <?php unset($_SESSION['auth_message']);
        unset($_SESSION['auth_message_type']); ?>
      <?php endif; ?>

      <div class="form-wrapper" id="loginFormWrapper" style="<?php echo (isset($_SESSION['active_form']) && $_SESSION['active_form'] === 'register') || (isset($_SESSION['active_form']) && $_SESSION['active_form'] === 'forgot_password') ? 'display: none;' : 'display: block;'; ?>">
        <h2>Iniciar Sesión</h2>
        <form action="<?php echo $project_root_segment; ?>/backend/php/auth_handler.php" method="POST">
          <div class="contenedor-input">
            <input type="email" name="login_email" required value="<?php echo htmlspecialchars($_SESSION['form_data']['login_email'] ?? ''); ?>">
            <label>Email</label>
            <span class="icono"><i class="bi bi-envelope-fill"></i></span>
          </div>
          <div class="contenedor-input">
            <input type="password" name="login_password" required>
            <label>Contraseña</label>
            <span class="icono"><i class="bi bi-lock-fill"></i></span>
          </div>
          <div class="login-options">
            <label for="rememberMe"><input type="checkbox" id="rememberMe" name="rememberMe" value="1"> Recordarme</label>
            <a href="#" class="toggle-form-link" data-target="forgotPasswordFormWrapper">¿Olvidaste tu contraseña?</a>
          </div>
          <button type="submit" name="login_submit" class="btn-form-submit">Entrar</button>
          <div class="cambio-form">
            <p>¿No tienes una cuenta? <a href="#" class="toggle-form-link" data-target="registerFormWrapper">Regístrate</a></p>
          </div>
        </form>
      </div>

      <div class="form-wrapper" id="registerFormWrapper" style="<?php echo (isset($_SESSION['active_form']) && $_SESSION['active_form'] === 'register') ? 'display: block;' : 'display: none;'; ?>">
        <h2>Registrarse</h2>
        <form action="<?php echo $project_root_segment; ?>/backend/php/auth_handler.php" method="POST">
          <div class="contenedor-input">
            <input type="text" name="register_nombre" required value="<?php echo htmlspecialchars($_SESSION['form_data']['register_nombre'] ?? ''); ?>">
            <label>Nombre</label>
            <span class="icono"><i class="bi bi-person-fill"></i></span>
          </div>
          <div class="contenedor-input">
            <input type="text" name="register_apellido" required value="<?php echo htmlspecialchars($_SESSION['form_data']['register_apellido'] ?? ''); ?>">
            <label>Apellido</label>
            <span class="icono"><i class="bi bi-person-fill"></i></span>
          </div>
          <div class="contenedor-input">
            <input type="email" name="register_email" required value="<?php echo htmlspecialchars($_SESSION['form_data']['register_email'] ?? ''); ?>">
            <label>Email</label>
            <span class="icono"><i class="bi bi-envelope-fill"></i></span>
          </div>
          <div class="contenedor-input">
            <input type="password" name="register_password" required>
            <label>Contraseña</label>
            <span class="icono"><i class="bi bi-lock-fill"></i></span>
          </div>
          <div class="contenedor-input">
            <input type="password" name="register_password_confirm" required>
            <label>Confirmar Contraseña</label>
            <span class="icono"><i class="bi bi-lock-fill"></i></span>
          </div>
          <div class="recordar">
            <label><input type="checkbox" name="terms" required> Acepto los términos y condiciones</label>
          </div>
          <button type="submit" name="register_submit" class="btn-form-submit">Registrarme</button>
          <div class="cambio-form">
            <p>¿Ya tienes una cuenta? <a href="#" class="toggle-form-link" data-target="loginFormWrapper">Iniciar Sesión</a></p>
          </div>
        </form>
      </div>

      <div class="form-wrapper" id="forgotPasswordFormWrapper" style="<?php echo (isset($_SESSION['active_form']) && $_SESSION['active_form'] === 'forgot_password') ? 'display: block;' : 'display: none;';
                                                                      unset($_SESSION['active_form']); ?>">
        <h2>Recuperar Contraseña</h2>
        <form action="<?php echo $project_root_segment; ?>/backend/php/auth_handler.php" method="POST">
          <p style="color: #ccc; font-size: 0.9em; margin-bottom: 20px;">Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña.</p>
          <div class="contenedor-input">
            <input type="email" name="forgot_email" required value="<?php echo htmlspecialchars($_SESSION['form_data']['forgot_email'] ?? ''); ?>">
            <label>Email</label>
            <span class="icono"><i class="bi bi-envelope-fill"></i></span>
          </div>
          <button type="submit" name="forgot_password_submit" class="btn-form-submit">Enviar Instrucciones</button>
          <div class="cambio-form">
            <p><a href="#" class="toggle-form-link" data-target="loginFormWrapper">Volver a Iniciar Sesión</a></p>
          </div>
        </form>
      </div>

      <?php unset($_SESSION['form_data']); ?>
    </div>
  </div>
  <script>
    const initialActiveForm = "<?php echo htmlspecialchars($_SESSION['active_form'] ?? 'login'); ?>";
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="login.js"></script>
</body>

</html>