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

<?php
// frontend/merch/buzo-capucha.php
session_start(); // ¡MUY IMPORTANTE! Debe ser la primera línea.

// Define el ID de este producto específico.
// Este ID debe coincidir con el 'id_producto' en tu tabla 'productos'
// para el "Buzo con Capucha". Asumamos que es 4 según tu ejemplo.
$id_producto_actual = 4;
$nombre_producto_actual = "Buzo con Capucha"; // Para el título y otros lugares
$precio_producto_actual_display = "40.000"; // Para mostrar en la página (el precio real se tomará de la BD)
$imagen_producto_actual = "../img/4.png"; // Ruta a la imagen principal
// En una implementación más avanzada, estos datos vendrían de una consulta a la BD usando un ID de la URL (ej. buzo-capucha.php?id=4)
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($nombre_producto_actual); ?> - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="../../css/style.css" />
    <link rel="stylesheet" href="../merch.css" />
    <link rel="stylesheet" href="buzo-capucha.css" />
</head>

<body class="merch-page product-detail-page">

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

    <main style="padding-top: var(--navbar-height, 70px);">

        <section class="merch-header">
            <div class="container">
                <h1><?php echo htmlspecialchars($nombre_producto_actual); ?></h1>
                <p class="lead">La mejor opción para mantenerte abrigado y con estilo.</p>
            </div>
        </section>

        <section class="merch-products-section">
            <div class="container product-detail-container">

                <?php // Mostrar mensajes del carrito o de login requerido
                if (isset($_SESSION['cart_message'])): ?>
                    <div class="alert alert-<?php echo htmlspecialchars($_SESSION['cart_message_type'] ?? 'info'); ?> alert-dismissible fade show mb-4" role="alert">
                        <?php echo htmlspecialchars($_SESSION['cart_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['cart_message']);
                    unset($_SESSION['cart_message_type']); ?>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-6 col-md-12 product-detail-image">
                        <div class="main-image-wrapper">
                            <img src="<?php echo htmlspecialchars($imagen_producto_actual); ?>" alt="<?php echo htmlspecialchars($nombre_producto_actual); ?> Tractores Clavijo" class="img-fluid product-detail-main-img">
                        </div>
                        <div class="thumbnail-images mt-3 d-flex justify-content-center">
                            <img src="<?php echo htmlspecialchars($imagen_producto_actual); ?>" alt="Miniatura <?php echo htmlspecialchars($nombre_producto_actual); ?>" class="thumbnail-img active">
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-12 product-detail-info">
                        <h2 class="product-detail-title">Buzo con Capucha de Algodón Premium</h2>
                        <p class="product-detail-price">$<?php echo htmlspecialchars($precio_producto_actual_display); ?> ARS</p>

                        <div class="product-detail-description">
                            <p>Este buzo con capucha es la prenda ideal para los días frescos. Su diseño moderno y su confección de alta calidad te brindan confort y el toque distintivo de Tractores Clavijo.</p>
                            <ul>
                                <li>Material: Algodón peinado y poliéster para mayor durabilidad.</li>
                                <li>Funcionalidad: Capucha ajustable con cordones y bolsillo canguro.</li>
                                <li>Aislamiento: Interior afelpado para extra calidez.</li>
                                <li>Estilo: Corte moderno y logo estampado de alta calidad.</li>
                            </ul>
                        </div>

                        <form action="../../../backend/php/cart_handler.php" method="POST" class="mt-4">
                            <input type="hidden" name="id_producto" value="<?php echo $id_producto_actual; ?>">

                            <?php
                            // Ejemplo: datos de talles para este producto. En un sistema real, vendrían de la BD
                            $talles_disponibles_para_este_producto = ["S", "M", "L", "XL", "XXL"];
                            if (!empty($talles_disponibles_para_este_producto)):
                            ?>
                                <div class="mb-3 product-option-item">
                                    <label for="size_<?php echo $id_producto_actual; ?>" class="form-label">Talle:</label>
                                    <select class="form-select" id="size_<?php echo $id_producto_actual; ?>" name="size_seleccionado">
                                        <option value="" selected disabled>Seleccione un talle</option>
                                        <?php foreach ($talles_disponibles_para_este_producto as $talle): ?>
                                            <option value="<?php echo htmlspecialchars($talle); ?>"><?php echo htmlspecialchars($talle); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Ejemplo: $colores_disponibles_para_este_producto = ["Negro", "Azul"];
                            // if (!empty($colores_disponibles_para_este_producto)):
                            ?>
                            <!-- <div class="mb-3 product-option-item">
                                <label for="color_<?php echo $id_producto_actual; ?>" class="form-label">Color:</label>
                                <select class="form-select" id="color_<?php echo $id_producto_actual; ?>" name="color_seleccionado">
                                    <option value="" selected disabled>Seleccione un color</option>
                                     <?php // foreach ($colores_disponibles_para_este_producto as $color): 
                                        ?>
                                    <option value="<?php // echo htmlspecialchars($color); 
                                                    ?>"><?php // echo htmlspecialchars($color); 
                                                        ?></option>
                                     <?php // endforeach; 
                                        ?>
                                </select>
                            </div> -->
                            <?php // endif; 
                            ?>

                            <div class="mb-3 product-option-item">
                                <label for="quantity_<?php echo $id_producto_actual; ?>" class="form-label">Cantidad:</label>
                                <input type="number" class="form-control" id="quantity_<?php echo $id_producto_actual; ?>" name="quantity" value="1" min="1" max="99">
                            </div>

                            <div class="product-actions mt-4">
                                <button type="submit" name="add_to_cart_submit" class="btn btn-success btn-lg me-2 add-to-cart-btn">
                                    <i class="fa-solid fa-cart-plus me-2"></i>Añadir al Carrito
                                </button>
                                <a href="../merch.php" class="btn btn-outline-secondary btn-lg">
                                    <i class="fa-solid fa-arrow-left me-2"></i>Volver a Merch
                                </a>
                            </div>
                        </form>

                        <div class="delivery-info mt-4 pt-3 border-top">
                            <p><i class="fa-solid fa-truck-fast me-2"></i>Envío rápido a todo el país.</p>
                            <p><i class="fa-solid fa-credit-card me-2"></i>Pagos seguros con tarjeta y otros medios.</p>
                            <p><i class="fa-solid fa-shield-alt me-2"></i>Política de devolución de 30 días.</p>
                        </div>
                    </div>
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
                        <li><a href="../../about/about.php"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
                        <li><a href="../tractors/tractors.php"><i class="bi bi-caret-right-fill"></i> Tractores</a></li>
                        <li><a href="../../merch/merch.php"><i class="bi bi-caret-right-fill"></i> Merch</a></li>
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