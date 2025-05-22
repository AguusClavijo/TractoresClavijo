<?php
session_start();
require_once '../../backend/conexion/db_connect.php';

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

if (!isset($_SESSION['user_id'])) {
    $_SESSION['auth_message'] = "Debes iniciar sesión para ver tus pedidos.";
    $_SESSION['auth_message_type'] = 'warning';
    $_SESSION['show_popup'] = true;
    $_SESSION['active_form'] = 'login';
    header("Location: " . $project_root_segment . "/frontend/login/login.php");
    exit();
}

$id_cliente_actual = $_SESSION['user_id'];
$pedidos_cliente = [];
$orders_errors = [];

$sql_pedidos = "SELECT id_pedido, fecha_pedido, total_pedido, estado_pedido 
                FROM pedidos 
                WHERE id_cliente = ? 
                ORDER BY fecha_pedido DESC";
$stmt_pedidos = $conn->prepare($sql_pedidos);

if ($stmt_pedidos) {
    $stmt_pedidos->bind_param("i", $id_cliente_actual);
    $stmt_pedidos->execute();
    $result_pedidos = $stmt_pedidos->get_result();
    while ($row = $result_pedidos->fetch_assoc()) {
        $pedidos_cliente[] = $row;
    }
    $stmt_pedidos->close();
} else {
    $orders_errors[] = "Error al cargar tus pedidos.";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81448e9ee5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="mis_pedidos.css" />
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
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'merch.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/merch/merch.php">Merch</a>
                        </li>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown ms-lg-3">
                                <a class="nav-link dropdown-toggle btn btn-user-dropdown" href="#" id="navbarUserDropdownGlobal" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?> <i class="bi bi-person-circle ms-1"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdownGlobal">
                                    <li><a class="dropdown-item" href="../perfil/perfil.php">Mi Perfil</a></li>
                                    <li><a class="dropdown-item" href="../pedidos/mis_pedidos.php">Mis Pedidos</a></li>
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

                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-cart <?php echo ($cart_count > 0 ? 'cart-active-indicator' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/cart/cart.php">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <?php if ($cart_count > 0): ?>
                                    <span class="badge bg-danger ms-1 cart-count-badge"><?php echo $cart_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="page-main-content">
        <div class="container py-5">
            <h1 class="page-title mb-4">Mis Pedidos</h1>

            <?php if (!empty($orders_errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($orders_errors as $error): ?>
                        <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($pedidos_cliente) && empty($orders_errors)): ?>
                <div class="text-center p-5 border rounded bg-light">
                    <i class="bi bi-box-seam display-3 text-muted mb-3"></i>
                    <h3>Aún no has realizado ningún pedido</h3>
                    <p class="text-muted">Explora nuestros productos y encuentra lo que necesitas.</p>
                    <a href="<?php echo $project_root_segment; ?>/frontend/merch/merch.php" class="btn btn-primary mt-2">Ver Productos</a>
                </div>
            <?php elseif (!empty($pedidos_cliente)): ?>
                <div class="table-responsive orders-table-container">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Pedido #</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Total</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos_cliente as $pedido): ?>
                                <tr>
                                    <th scope="row"><?php echo htmlspecialchars($pedido['id_pedido']); ?></th>
                                    <td><?php echo date("d/m/Y H:i", strtotime($pedido['fecha_pedido'])); ?></td>
                                    <td>$<?php echo number_format($pedido['total_pedido'], 2, ',', '.'); ?></td>
                                    <td><span class="badge bg-<?php
                                                                switch (strtolower($pedido['estado_pedido'])) {
                                                                    case 'procesando':
                                                                        echo 'warning text-dark';
                                                                        break;
                                                                    case 'enviado':
                                                                        echo 'info';
                                                                        break;
                                                                    case 'completado':
                                                                        echo 'success';
                                                                        break;
                                                                    case 'cancelado':
                                                                        echo 'danger';
                                                                        break;
                                                                    default:
                                                                        echo 'secondary';
                                                                }
                                                                ?>"><?php echo htmlspecialchars(ucfirst($pedido['estado_pedido'])); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
                        <li><a href="../merch/merch.php"><i class="bi bi-caret-right-fill"></i> Merch</a></li>
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