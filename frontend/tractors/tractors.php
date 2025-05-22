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
    <title>Tractores - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="tractors.css" />
    <link rel="stylesheet" href="../css/contact.css" />
</head>

<body class="tractors-page">
    <?php
    // Activar la visualización de errores de PHP para depuración
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Definición del array de tractores
    // Las rutas de 'src' son relativas a la carpeta 'tractors' (donde está tractors.php)
    // Las rutas de 'pagina' son ahora relativas a la carpeta 'tractors' también, porque 'models-tractors' está dentro.
    // CORRECTION: Assuming 'models-tractors' is a sibling directory to 'tractors' or at the web root,
    // the paths need to be adjusted to go up one level or be absolute from the root.
    // Given the common "mal" redirection issue, prepending '../' is a common fix if the directory structure is flat.
    $tractores = [
        ['id' => 'tractor1', 'src' => 'fotos/1.jpg', 'alt' => 'Tractor RA 254', 'modelo' => 'RA 254', 'hp' => '25 HP', 'pagina' => '../tractors/models-tractors/ra-254.php'],
        ['id' => 'tractor2', 'src' => 'fotos/2.jpg', 'alt' => 'Tractor RD 304', 'modelo' => 'RD 304', 'hp' => '30 HP', 'pagina' => '../tractors/models-tractors/rd-304.php'],
        ['id' => 'tractor4', 'src' => 'fotos/4.jpg', 'alt' => 'Tractor RK 454', 'modelo' => 'RK 454', 'hp' => '45 HP', 'pagina' => '../tractors/models-tractors/rk-454.php'],
        ['id' => 'tractor5', 'src' => 'fotos/5.jpg', 'alt' => 'Tractor RA 504', 'modelo' => 'RA 504', 'hp' => '58 HP', 'pagina' => '../tractors/models-tractors/ra-504.php'],
        ['id' => 'tractor6', 'src' => 'fotos/6.jpg', 'alt' => 'Tractor RA 504-F', 'modelo' => 'RA 504-F', 'hp' => '58 HP', 'pagina' => '../tractors/models-tractors/ra-504-f.php'],
        ['id' => 'tractor7', 'src' => 'fotos/7.jpg', 'alt' => 'Tractor RD 504', 'modelo' => 'RD 504', 'hp' => '58 HP', 'pagina' => '../tractors/models-tractors/rd-504.php'],
        ['id' => 'tractor8', 'src' => 'fotos/8.jpg', 'alt' => 'Tractor RK 504 CON TECHO', 'modelo' => 'RK 504 CON TECHO', 'hp' => '58 HP', 'pagina' => '../tractors/models-tractors/rk-504Techo.php'],
        ['id' => 'tractor9', 'src' => 'fotos/9.jpg', 'alt' => 'Tractor RK 504 CON CABINA', 'modelo' => 'RK 504 CON CABINA', 'hp' => '58 HP', 'pagina' => '../tractors/models-tractors/rk-504Cabina.php'],
        ['id' => 'tractor10', 'src' => 'fotos/10.jpg', 'alt' => 'Tractor RA 704', 'modelo' => 'RA 704', 'hp' => '75 HP', 'pagina' => '../tractors/models-tractors/ra-704.php'],
        ['id' => 'tractor11', 'src' => 'fotos/11.jpg', 'alt' => 'Tractor RK 704 CON TECHO', 'modelo' => 'RK 704 CON TECHO', 'hp' => '75 HP', 'pagina' => '../tractors/models-tractors/rk-704Techo.php'],
        ['id' => 'tractor12', 'src' => 'fotos/12.jpg', 'alt' => 'Tractor RK 704 CON CABINA', 'modelo' => 'RK 704 CON CABINA', 'hp' => '75 HP', 'pagina' => '../tractors/models-tractors/rk-704Cabina.php'],
        ['id' => 'tractor15', 'src' => 'fotos/15.jpg', 'alt' => 'Tractor RK 904 CON TECHO', 'modelo' => 'RK 904 CON TECHO', 'hp' => '92 HP', 'pagina' => '../tractors/models-tractors/rk-904Techo.php'],
        ['id' => 'tractor16', 'src' => 'fotos/16.jpg', 'alt' => 'Tractor RK 904 CON CABINA', 'modelo' => 'RK 904 CON CABINA', 'hp' => '92 HP', 'pagina' => '../tractors/models-tractors/rk-904Cabina.php'],
        ['id' => 'tractor17', 'src' => 'fotos/17.jpg', 'alt' => 'Tractor RC 1004 CON TECHO', 'modelo' => 'RC 1004 CON TECHO', 'hp' => '105 HP', 'pagina' => '../tractors/models-tractors/rc-1004Techo.php'],
        ['id' => 'tractor18', 'src' => 'fotos/18.jpg', 'alt' => 'Tractor RC 1004 CON CABINA', 'modelo' => 'RC 1004 CON CABINA', 'hp' => '105 HP', 'pagina' => '../tractors/models-tractors/rc-1004Cabina.php'],
        ['id' => 'tractor19', 'src' => 'fotos/19.jpg', 'alt' => 'Tractor RC 1104 CON TECHO', 'modelo' => 'RC 1104 CON TECHO', 'hp' => '115 HP', 'pagina' => '../tractors/models-tractors/rc-1104Techo.php'],
        ['id' => 'tractor20', 'src' => 'fotos/20.jpg', 'alt' => 'Tractor RC 1104 CON CABINA', 'modelo' => 'RC 1104 CON CABINA', 'hp' => '115 HP', 'pagina' => '../tractors/models-tractors/rc-1104Cabina.php'],
        ['id' => 'tractor21', 'src' => 'fotos/21.jpg', 'alt' => 'Tractor RC 1404', 'modelo' => 'RC 1404', 'hp' => '140 HP', 'pagina' => '../tractors/models-tractors/rc-1404.php'],
    ];
    ?>
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

    <main>
        <section class="tractors-header text-white text-center">
            <div class="container">
                <h1>NUESTROS TRACTORES</h1>
                <p class="lead">Explora nuestra gama de tractores, diseñados para cada necesidad agrícola.</p>
            </div>
        </section>

        <section class="tractors-products-section py-5">
            <div class="container">
                <h2 class="text-center mb-5">Modelos Disponibles</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($tractores as $tractor) : ?>
                        <div class="col">
                            <a href="<?php echo $tractor['pagina']; ?>" class="product-card-link">
                                <div class="product-card text-center">
                                    <img src="<?php echo $tractor['src']; ?>" class="img-fluid rounded mb-3" alt="<?php echo $tractor['alt']; ?>">
                                    <h3 class="product-title"><?php echo $tractor['modelo']; ?></h3>
                                    <p class="product-price"><?php echo $tractor['hp']; ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
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
                        <li><a href="#"><i class="bi bi-caret-right-fill"></i> Inicio</a></li>
                        <li><a href="../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="../tractors/tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
                        <li><a href="../contact/contact.php"><i class="bi bi-caret-right-fill"></i> Contacto</a></li>
                        <li><a href="../merch/merch.php"><i class="bi bi-caret-right-fill"></i> Merch</a></li>
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