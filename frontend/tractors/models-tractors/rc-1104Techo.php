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
$project_root_segment = (isset($script_path_parts[1]) && !empty($script_path_parts[1]) && $script_path_parts[1] !== 'frontend' && $script_path_parts[1] !== 'backend') ? '/' . $script_path_parts[1] : '';
// Si tu proyecto está en localhost/TractoresClavijo/, $project_root_segment será /TractoresClavijo
// Si tu proyecto está en localhost/, $project_root_segment será ''
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RC-1104 (Techo) - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="rc-1104Techo.css" />

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
                        <li class="nav-item">
                            <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], 'merch.php') !== false ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/merch/merch.php">Merch</a>
                        </li>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown ms-lg-3">
                                <a class="nav-link dropdown-toggle btn btn-user-dropdown" href="#" id="navbarUserDropdownGlobal" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?> <i class="bi bi-person-circle ms-1"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdownGlobal">
                                    <li><a class="dropdown-item" href="../../perfil/perfil.php">Mi Perfil</a></li>
                                    <li><a class="dropdown-item" href="../../pedidos/mis_pedidos.php">Mis Pedidos</a></li>
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

    <section class="tractors-header text-white text-center">
        <div class="container">
            <h1>TRACTOR RC-1104 CON TECHO</h1>
            <p class="lead">Potencia y eficiencia para tus tareas agrícolas.</p>
        </div>
    </section>

    <main class="container my-5">
        <section class="product-details-section">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0 d-flex justify-content-center align-items-center">
                    <img src="./fotos/rc-1104T.jpg" class="img-fluid product-main-image" alt="Tractor RC-1104 Techo">
                </div>

                <div class="col-lg-6 product-specs-container">
                    <h2 class="specs-title">DETALLES TÉCNICOS</h2>
                    <ul class="specs-list">
                        <li>Tracción: Doble 4x4</li>
                        <li>Motor: Diesel 4 cilindros, 4 tiempos, inyección directa, refrigerado por agua, tecnología Perkins.</li>
                        <li>Potencia: 115 hp TURBO</li>
                        <li>Cilindrada: 4070 cm³</li>
                        <li>Número de marchas: 24X24</li>
                        <li>Capacidad del tanque de combustible: 120L</li>
                        <li>Peso: 4000 Kg</li>
                    </ul>

                    <div class="pdf-section">
                        <h3 class="pdf-title">Ficha Técnica Completa (PDF)</h3>
                        <p class="pdf-model">Tractor Agrícola RC-1104 con Techo</p>
                        <a href="https://www.americanagro.com.ar/wp-content/uploads/2024/12/RC1104-C.-comprimido.pdf" target="_blank" class="btn btn-download-pdf">VER PDF</a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                    <h5>Tractores Clavijo</h5>
                    <p>Conecte con nosotros en nuestras redes sociales y manténgase al día con las últimas novedades.</p>
                    <a href="https://www.instagram.com/tractores.clavijo/" target="_blank" class="d-block text-decoration-none mb-2">
                        <i class="bi bi-instagram"></i> @tractores.clavijo
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
                        <li><a href="../../main/main.php"><i class="bi bi-caret-right-fill"></i> Inicio</a></li>
                        <li><a href="../../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="../tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
                        <li><a href="../../contact/contact.php"><i class="bi bi-caret-right-fill"></i> Contacto</a></li>
                        <li><a href="../../merch/merch.php"><i class="bi bi-caret-right-fill"></i> Merch</a></li>
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