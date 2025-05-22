<?php
session_start();

require_once '../../backend/conexion/db_connect.php';

$script_path_parts = explode('/', dirname($_SERVER['PHP_SELF']));
$project_root_folder_name = 'TractoresClavijo'; // ¡¡¡CAMBIA ESTO AL NOMBRE EXACTO DE TU CARPETA RAÍZ EN HTDOCS!!!
$project_root_segment = "";
$path_index = array_search($project_root_folder_name, $script_path_parts);
if ($path_index !== false) {
    for ($i = 1; $i <= $path_index; $i++) {
        $project_root_segment .= "/" . $script_path_parts[$i];
    }
} else if (empty($script_path_parts[1])) { // Si está en la raíz de localhost y no en subcarpeta
    $project_root_segment = "";
}
// Si $project_root_segment sigue vacío y tu proyecto SÍ está en una subcarpeta,
// puedes definirlo manualmente aquí como fallback:
// if (empty($project_root_segment) && $_SERVER['HTTP_HOST'] === 'localhost') {
//    $project_root_segment = '/TractoresClavijo'; // O el nombre de tu carpeta
// }


$cart_count = 0;
$carrito_items_para_mostrar = [];
$subtotal_general = 0.00;
$costo_envio_ejemplo = 5000.00;

if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    $product_ids_in_cart = [];
    foreach ($_SESSION['carrito'] as $item_sesion) {
        if (isset($item_sesion['id_producto'])) {
            $product_ids_in_cart[] = $item_sesion['id_producto'];
        }
    }
    $product_ids_in_cart = array_unique($product_ids_in_cart);

    if (!empty($product_ids_in_cart)) {
        $placeholders = implode(',', array_fill(0, count($product_ids_in_cart), '?'));
        $types = str_repeat('i', count($product_ids_in_cart));

        $sql_productos_carrito = "SELECT id_producto, nombre_producto, precio_venta, imagen_url, stock 
                                  FROM productos 
                                  WHERE id_producto IN ($placeholders) AND activo = TRUE";

        $stmt_cart_prods = $conn->prepare($sql_productos_carrito);

        if ($stmt_cart_prods) {
            $stmt_cart_prods->bind_param($types, ...$product_ids_in_cart);
            $stmt_cart_prods->execute();
            $result_cart_prods = $stmt_cart_prods->get_result();

            $productos_db_info = [];
            while ($row = $result_cart_prods->fetch_assoc()) {
                $productos_db_info[$row['id_producto']] = $row;
            }
            $stmt_cart_prods->close();

            foreach ($_SESSION['carrito'] as $cart_item_key => $item_sesion) {
                $id_producto_sesion = $item_sesion['id_producto'];

                if (isset($productos_db_info[$id_producto_sesion])) {
                    $producto_info_db = $productos_db_info[$id_producto_sesion];
                    $cantidad_en_carrito = $item_sesion['cantidad'];

                    if ($cantidad_en_carrito > $producto_info_db['stock']) {
                        $cantidad_en_carrito = $producto_info_db['stock'];
                        if ($cantidad_en_carrito > 0) {
                            $_SESSION['carrito'][$cart_item_key]['cantidad'] = $cantidad_en_carrito;
                        } else {
                            unset($_SESSION['carrito'][$cart_item_key]);
                            continue;
                        }
                    }
                    if ($cantidad_en_carrito <= 0) {
                        unset($_SESSION['carrito'][$cart_item_key]);
                        continue;
                    }

                    $subtotal_item = $producto_info_db['precio_venta'] * $cantidad_en_carrito;
                    $subtotal_general += $subtotal_item;

                    // Determinar la ruta base para las imágenes de merch
                    // Asumimos que las imágenes en la BD están guardadas como 'img/nombre_imagen.png'
                    // y que esta ruta es relativa a la carpeta 'frontend/merch/'
                    $imagen_path_desde_merch = $producto_info_db['imagen_url'];
                    // Si la ruta en la BD ya incluye 'img/', no necesitamos añadirlo.
                    // Si la ruta en la BD es solo 'nombre_imagen.png', necesitaríamos 'img/' . $producto_info_db['imagen_url']

                    // Corrección de la ruta de la imagen:
                    // Asumiendo que $producto_info_db['imagen_url'] es algo como 'img/11.png' (relativo a la carpeta 'merch')
                    // o '../merch/img/11.png' (relativo a la página de producto individual, ej., frontend/merch/productos/buzo.php)

                    $base_image_url = $project_root_segment . '/frontend/merch/';
                    $image_db_path = $producto_info_db['imagen_url'];

                    // Limpiar posible '../' de la ruta de la BD si la guardaste así desde una subcarpeta de merch
                    if (strpos($image_db_path, '../') === 0) {
                        // Si es '../merch/img/X.png', nos interesa 'merch/img/X.png'
                        // Pero como ya estamos en la base '/frontend/merch/', solo necesitamos 'img/X.png'
                        $path_parts_img = explode('/', $image_db_path);
                        if (count($path_parts_img) >= 3 && $path_parts_img[0] === '..' && $path_parts_img[1] === 'merch') {
                            $image_final_path = implode('/', array_slice($path_parts_img, 2)); // Toma desde 'img/...'
                        } else if (count($path_parts_img) >= 2 && $path_parts_img[0] === '..') { // Si es solo ../img/X.png
                            $image_final_path = implode('/', array_slice($path_parts_img, 1));
                        } else {
                            $image_final_path = $image_db_path; // Usar como está si no empieza con ../
                        }
                    } else {
                        $image_final_path = $image_db_path; // Usar como está si no empieza con ../
                    }

                    // Asegurarse de que no haya barras duplicadas
                    $full_image_path = rtrim($base_image_url, '/') . '/' . ltrim($image_final_path, '/');


                    $carrito_items_para_mostrar[] = [
                        'id_producto' => $id_producto_sesion,
                        'cart_item_key' => $cart_item_key,
                        'nombre' => $producto_info_db['nombre_producto'],
                        'precio' => $producto_info_db['precio_venta'],
                        'cantidad' => $cantidad_en_carrito,
                        'imagen_url_display' => $full_image_path, // USAR ESTA PARA MOSTRAR
                        'subtotal_item' => $subtotal_item,
                        'link_producto' => $project_root_segment . '/frontend/merch/detalle_producto.php?id=' . $id_producto_sesion,
                        'talle_seleccionado' => $item_sesion['talle'] ?? null
                    ];
                } else {
                    unset($_SESSION['carrito'][$cart_item_key]);
                }
            }
        }
    }
    $cart_count = 0;
    if (!empty($carrito_items_para_mostrar)) {
        foreach ($carrito_items_para_mostrar as $item_display) {
            $cart_count += $item_display['cantidad'];
        }
    } else {
        $_SESSION['carrito'] = [];
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrito de Compras - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81448e9ee5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="cart.css" />
</head>

<body class="cart-page">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Tractores Clavijo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavCartPage" aria-controls="navbarNavCartPage" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavCartPage">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'main.php' ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/about/about.php">Sobre Nosotros</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'tractors.php' ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/tractors/tractors.php">Tractores</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'contact.php' ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/contact/contact.php">Contacto</a></li>
                        <li class="nav-item"><a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'merch.php' ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/merch/merch.php">Merch</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown ms-lg-3">
                                <a class="nav-link dropdown-toggle btn btn-user-dropdown" href="#" id="navbarUserDropdownCart" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?> <i class="bi bi-person-circle ms-1"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdownCart">
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
                                <a class="btn btn-login" href="<?php echo $project_root_segment; ?>/frontend/login/login.php">Iniciar Sesión</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-cart <?php echo ($cart_count > 0 ? 'cart-has-items' : ''); ?> <?php echo (basename($_SERVER['PHP_SELF']) === 'cart.php' ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/cart/cart.php">
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

    <main class="cart-page-main">
        <div class="container cart-container py-5">

            <?php if (isset($_SESSION['cart_page_message'])): ?>
                <div class="alert alert-<?php echo htmlspecialchars($_SESSION['cart_page_message_type'] ?? 'info'); ?> alert-dismissible fade show mb-4" role="alert">
                    <?php echo htmlspecialchars($_SESSION['cart_page_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['cart_page_message']);
                unset($_SESSION['cart_page_message_type']); ?>
            <?php endif; ?>

            <h1 class="text-center mb-5 cart-title">Tu Carrito de Compras</h1>

            <?php if (!empty($carrito_items_para_mostrar)): ?>
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <div class="cart-items-wrapper">
                            <div class="cart-table-header d-none d-md-flex row gx-0">
                                <div class="col-md-5">Producto</div>
                                <div class="col-md-2 text-center">Precio</div>
                                <div class="col-md-3 text-center">Cantidad</div>
                                <div class="col-md-2 text-end">Subtotal</div>
                            </div>

                            <?php foreach ($carrito_items_para_mostrar as $item): ?>
                                <div class="cart-item row gx-0 align-items-center">
                                    <div class="col-12 col-md-5 cart-item-product">
                                        <img src="<?php echo htmlspecialchars($item['imagen_url_display']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" class="cart-item-image">
                                        <div class="cart-item-details">
                                            <a href="<?php echo htmlspecialchars($item['link_producto']); ?>" class="cart-item-name"><?php echo htmlspecialchars($item['nombre']); ?></a>
                                            <?php if (!empty($item['talle_seleccionado'])): ?>
                                                <p class="cart-item-variant small text-muted mb-0">Talle: <?php echo htmlspecialchars($item['talle_seleccionado']); ?></p>
                                            <?php endif; ?>
                                            <form action="../../backend/php/cart_handler_remove.php" method="POST" class="d-inline">
                                                <input type="hidden" name="remove_cart_item_key" value="<?php echo htmlspecialchars($item['cart_item_key']); ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger cart-remove-item mt-1"><i class="bi bi-trash3-fill"></i> Quitar</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2 cart-item-price text-md-center">
                                        <span class="d-md-none small-label">Precio: </span>$<?php echo number_format($item['precio'], 2, ',', '.'); ?>
                                    </div>
                                    <div class="col-5 col-md-3 cart-item-quantity text-md-center">
                                        <span class="d-md-none small-label">Cant: </span>
                                        <form action="../../backend/php/cart_handler_update.php" method="POST" class="d-inline-flex align-items-center quantity-update-form">
                                            <input type="hidden" name="update_cart_item_key" value="<?php echo htmlspecialchars($item['cart_item_key']); ?>">
                                            <input type="number" class="form-control form-control-sm quantity-input" name="new_quantity" value="<?php echo $item['cantidad']; ?>" min="1" max="99">
                                            <button type="submit" class="btn btn-sm btn-outline-primary ms-1"><i class="bi bi-arrow-repeat"></i></button>
                                        </form>
                                    </div>
                                    <div class="col-3 col-md-2 cart-item-subtotal text-end">
                                        <span class="d-md-none small-label">Subt: </span>$<?php echo number_format($item['subtotal_item'], 2, ',', '.'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <h3 class="cart-summary-title">Resumen del Pedido</h3>
                            <div class="summary-item">
                                <span>Subtotal</span>
                                <span>$<?php echo number_format($subtotal_general, 2, ',', '.'); ?></span>
                            </div>
                            <div class="summary-item">
                                <span>Envío</span>
                                <span>$<?php echo number_format($costo_envio_ejemplo, 2, ',', '.'); ?></span>
                            </div>
                            <hr>
                            <div class="summary-item total">
                                <span>Total</span>
                                <span>$<?php echo number_format($subtotal_general + $costo_envio_ejemplo, 2, ',', '.'); ?></span>
                            </div>
                            <a href="<?php echo $project_root_segment; ?>/frontend/checkout/checkout.php" class="btn btn-success btn-checkout w-100 mt-4">Proceder al Pago</a>
                            <a href="<?php echo $project_root_segment; ?>/frontend/merch/merch.php" class="btn btn-outline-secondary w-100 mt-2">Seguir Comprando</a>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="text-center cart-empty">
                    <i class="bi bi-cart-x-fill cart-empty-icon"></i>
                    <h2 class="mt-3">Tu carrito está vacío</h2>
                    <p class="lead text-muted">Parece que no has añadido ningún producto a tu carrito todavía.</p>
                    <a href="<?php echo $project_root_segment; ?>/frontend/merch/merch.php" class="btn btn-primary btn-lg mt-3">Ver Productos</a>
                </div>
            <?php endif; ?>

        </div>
    </main>

    <footer>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>