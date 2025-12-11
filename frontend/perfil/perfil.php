<?php
session_start();
require_once '../../backend/conexion/db_connect.php'; // Ajusta la ruta

// Lógica para $project_root_segment y $cart_count (copia de otras páginas)
$script_path_parts = explode('/', dirname($_SERVER['PHP_SELF']));
$project_root_folder_name_in_htdocs = 'TractoresClavijo';
$project_root_segment = "";
$path_index = array_search($project_root_folder_name_in_htdocs, $script_path_parts);
if ($path_index !== false) {
    for ($i = 1; $i <= $path_index; $i++) {
        $project_root_segment .= "/" . $script_path_parts[$i];
    }
} else if (empty($script_path_parts[1])) {
    $project_root_segment = "";
}

$cart_count = 0;
if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item_cart_count) {
        if (isset($item_cart_count['cantidad'])) {
            $cart_count += $item_cart_count['cantidad'];
        }
    }
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    $_SESSION['auth_message'] = "Debes iniciar sesión para ver tu perfil.";
    $_SESSION['auth_message_type'] = 'warning';
    $_SESSION['show_popup'] = true;
    $_SESSION['active_form'] = 'login';
    header("Location: " . $project_root_segment . "/frontend/login/login.php");
    exit();
}

$id_cliente_actual = $_SESSION['user_id'];
$cliente_info = null;
$profile_errors = [];

// Obtener información del cliente
$sql_cliente = "SELECT nombre, apellido, email, telefono, fecha_registro 
                FROM clientes WHERE id_cliente = ?";
$stmt_cliente = $conn->prepare($sql_cliente);
if ($stmt_cliente) {
    $stmt_cliente->bind_param("i", $id_cliente_actual);
    $stmt_cliente->execute();
    $result_cliente = $stmt_cliente->get_result();
    if ($result_cliente->num_rows === 1) {
        $cliente_info = $result_cliente->fetch_assoc();
    } else {
        $profile_errors[] = "No se pudo encontrar la información de tu perfil.";
    }
    $stmt_cliente->close();
} else {
    $profile_errors[] = "Error al preparar la consulta del perfil.";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81448e9ee5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="perfil.css" /> {/* Nuevo CSS */}
</head>

<body class="page-background">
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

    <main class="page-main-content">
        <div class="container py-5">
            <h1 class="page-title mb-4">Mi Perfil</h1>

            <?php if (!empty($profile_errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($profile_errors as $error): ?>
                        <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($cliente_info): ?>
                <div class="card profile-card">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="bi bi-person-badge-fill me-2"></i>Información Personal</h5>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($cliente_info['nombre']); ?></p>
                        <p><strong>Apellido:</strong> <?php echo htmlspecialchars($cliente_info['apellido']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($cliente_info['email']); ?></p>
                        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente_info['telefono'] ?? 'No especificado'); ?></p>
                        <p><strong>Miembro desde:</strong> <?php echo date("d/m/Y", strtotime($cliente_info['fecha_registro'])); ?></p>
                    </div>
                </div>
            <?php elseif (empty($profile_errors)): ?>
                <p class="text-center">Cargando información del perfil...</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Tractores Clavijo</h5>
                    <p>Conecte con nosotros en nuestras redes sociales y manténgase al día con las últimas novedades.</p>
                    <a href="https://www.instagram.com/tractoresclavijo_" target="_blank" class="d-block text-decoration-none mb-2">
                        <i class="bi bi-instagram"></i> @tractoresclavijo_
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=100089567695602" target="_blank" class="d-block text-decoration-none">
                        <i class="bi bi-facebook"></i> Tractores Clavijo
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Ubicación</h5>
                    <address>
                        Carril Santos Lugares Ingeniero Giagnoni<br>
                        Mendoza - ARGENTINA<br>
                        <i class="bi bi-telephone-fill"></i> Teléfono: 0263 452-6714
                    </address>
                </div>
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="../main/main.php"><i class="bi bi-caret-right-fill"></i> Inicio</a></li>
                        <li><a href="../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="../tractors/tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
                        <li><a href="../contact/contact.php"><i class="bi bi-caret-right-fill"></i> Contacto</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center border-top pt-3 mt-4">
                <small>© <?php echo date("Y"); ?> Tractores Clavijo - Todos los derechos reservados</small>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>