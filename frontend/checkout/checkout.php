<?php
session_start();

// Redirigir si no hay sesión de usuario o el carrito está vacío
if (!isset($_SESSION['user_id'])) {
    $_SESSION['auth_message'] = "Debes iniciar sesión para proceder al pago.";
    $_SESSION['auth_message_type'] = 'warning';

    $script_path_parts_checkout = explode('/', dirname($_SERVER['PHP_SELF']));
    $project_root_folder_name_checkout = 'TractoresClavijo'; // AJUSTA SI ES NECESARIO
    $project_root_segment_checkout = "";
    $path_index_checkout = array_search($project_root_folder_name_checkout, $script_path_parts_checkout);
    if ($path_index_checkout !== false) {
        for ($i = 1; $i <= $path_index_checkout; $i++) {
            $project_root_segment_checkout .= "/" . $script_path_parts_checkout[$i];
        }
    } else if (empty($script_path_parts_checkout[1])) {
        $project_root_segment_checkout = "";
    }
    $login_page_url_checkout = $project_root_segment_checkout . "/frontend/login/login.php";

    $_SESSION['show_popup'] = true;
    $_SESSION['active_form'] = 'login';
    header("Location: " . $login_page_url_checkout);
    exit();
}

if (empty($_SESSION['carrito'])) {
    $script_path_parts_checkout_cart = explode('/', dirname($_SERVER['PHP_SELF']));
    $project_root_folder_name_checkout_cart = 'TractoresClavijo'; // AJUSTA SI ES NECESARIO
    $project_root_segment_checkout_cart = "";
    $path_index_checkout_cart = array_search($project_root_folder_name_checkout_cart, $script_path_parts_checkout_cart);
    if ($path_index_checkout_cart !== false) {
        for ($i = 1; $i <= $path_index_checkout_cart; $i++) {
            $project_root_segment_checkout_cart .= "/" . $script_path_parts_checkout_cart[$i];
        }
    } else if (empty($script_path_parts_checkout_cart[1])) {
        $project_root_segment_checkout_cart = "";
    }
    $cart_page_url_checkout = $project_root_segment_checkout_cart . "/frontend/cart/cart.php";

    $_SESSION['cart_page_message'] = "Tu carrito está vacío. Añade productos antes de proceder al pago.";
    $_SESSION['cart_page_message_type'] = 'info';
    header("Location: " . $cart_page_url_checkout);
    exit();
}

require_once '../../backend/conexion/db_connect.php'; // Desde frontend/checkout/ a backend/conexion/

// Lógica para $project_root_segment
$script_path_parts = explode('/', dirname($_SERVER['PHP_SELF']));
$project_root_folder_name_in_htdocs = 'TractoresClavijo'; // ¡¡¡CAMBIA ESTO SI ES NECESARIO!!!
$project_root_segment = "";
$path_index = array_search($project_root_folder_name_in_htdocs, $script_path_parts);
if ($path_index !== false) {
    for ($i = 1; $i <= $path_index; $i++) {
        $project_root_segment .= "/" . $script_path_parts[$i];
    }
} else if (empty($script_path_parts[1])) {
    $project_root_segment = "";
}


// Calcular totales y obtener detalles de productos del carrito
$subtotal_general = 0;
$items_del_pedido_actual = []; // Cambiado el nombre para evitar conflicto con $_SESSION['pedido_actual_items']
$costo_envio_ejemplo = 5000.00;

if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    $product_ids_in_cart = array_column($_SESSION['carrito'], 'id_producto');
    $product_ids_in_cart = array_unique($product_ids_in_cart);

    if (!empty($product_ids_in_cart)) {
        $placeholders = implode(',', array_fill(0, count($product_ids_in_cart), '?'));
        $types = str_repeat('i', count($product_ids_in_cart));
        $sql_productos_checkout = "SELECT id_producto, nombre_producto, precio_venta FROM productos WHERE id_producto IN ($placeholders)";
        $stmt_checkout = $conn->prepare($sql_productos_checkout);
        $stmt_checkout->bind_param($types, ...$product_ids_in_cart);
        $stmt_checkout->execute();
        $result_checkout = $stmt_checkout->get_result();
        $productos_info_db = [];
        while ($row = $result_checkout->fetch_assoc()) {
            $productos_info_db[$row['id_producto']] = $row;
        }
        $stmt_checkout->close();

        foreach ($_SESSION['carrito'] as $cart_item_key => $item_sesion) {
            if (isset($productos_info_db[$item_sesion['id_producto']])) {
                $db_prod = $productos_info_db[$item_sesion['id_producto']];
                $cantidad = $item_sesion['cantidad'];
                $precio_actual = $db_prod['precio_venta'];
                $subtotal_item = $precio_actual * $cantidad;
                $subtotal_general += $subtotal_item;

                $items_del_pedido_actual[] = [ // Usar la variable local
                    'id_producto' => $item_sesion['id_producto'],
                    'nombre_producto_en_pedido' => $db_prod['nombre_producto'],
                    'cantidad' => $cantidad,
                    'precio_unitario_en_pedido' => $precio_actual,
                    'atributos_seleccionados_en_pedido' => json_encode([
                        'talle' => $item_sesion['talle'] ?? null,
                        'color' => $item_sesion['color'] ?? null,
                        'tipo_mate' => $item_sesion['tipo_mate'] ?? null,
                    ]),
                    'subtotal_linea' => $subtotal_item
                ];
            }
        }
    }
}
$total_con_envio = $subtotal_general + $costo_envio_ejemplo;
$_SESSION['pedido_actual_items'] = $items_del_pedido_actual; // Guardar para procesar_pedido.php
$_SESSION['pedido_actual_total'] = $total_con_envio;

$conn->close();

// Recuperar datos del formulario si hubo un error previo
$form_data = $_SESSION['checkout_form_data'] ?? [];
unset($_SESSION['checkout_form_data']); // Limpiar después de usar
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - Tractores Clavijo</title>
    <link rel="icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $project_root_segment; ?>/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81448e9ee5.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="checkout.css" />
</head>

<body class="checkout-page">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Tractores Clavijo</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavCheckout" aria-controls="navbarNavCheckout" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavCheckout">
                    <ul class="navbar-nav align-items-center">
                        <li class="nav-item"><a class="nav-link" href="<?php echo $project_root_segment; ?>/frontend/main/main.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $project_root_segment; ?>/frontend/merch/merch.php">Merch</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown ms-lg-3">
                                <a class="nav-link dropdown-toggle btn btn-user-dropdown" href="#" id="navbarUserDropdownCheckout" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Hola, <?php echo htmlspecialchars($_SESSION['user_nombre']); ?> <i class="bi bi-person-circle ms-1"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdownCheckout">
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
                            <a class="btn btn-cart <?php echo (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0 ? 'cart-has-items' : ''); ?> <?php echo (basename($_SERVER['PHP_SELF']) === 'cart.php' ? 'active' : ''); ?>" href="<?php echo $project_root_segment; ?>/frontend/cart/cart.php">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <?php
                                $header_cart_count_checkout = 0;
                                if (isset($_SESSION['carrito']) && is_array($_SESSION['carrito'])) {
                                    foreach ($_SESSION['carrito'] as $item_hdr_cart_checkout) {
                                        if (isset($item_hdr_cart_checkout['cantidad'])) {
                                            $header_cart_count_checkout += $item_hdr_cart_checkout['cantidad'];
                                        }
                                    }
                                }
                                if ($header_cart_count_checkout > 0):
                                ?>
                                    <span class="badge bg-danger ms-1 cart-count-badge"><?php echo $header_cart_count_checkout; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="checkout-page-main">
        <div class="container py-5">
            <h1 class="text-center mb-5 checkout-title">Finalizar Compra</h1>

            <?php if (isset($_SESSION['checkout_message'])): ?>
                <div class="alert alert-<?php echo htmlspecialchars($_SESSION['checkout_message_type'] ?? 'info'); ?> alert-dismissible fade show mb-4 col-md-7 col-lg-8 mx-auto" role="alert">
                    <?php if (is_array($_SESSION['checkout_message'])): ?>
                        <ul class="mb-0" style="padding-left: 1.2rem;">
                            <?php foreach ($_SESSION['checkout_message'] as $msg_item): ?>
                                <li style="margin-bottom: 0.25rem;"><?php echo htmlspecialchars($msg_item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <?php echo htmlspecialchars($_SESSION['checkout_message']); ?>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.85rem 1rem;"></button>
                </div>
                <?php unset($_SESSION['checkout_message']);
                unset($_SESSION['checkout_message_type']); ?>
            <?php endif; ?>

            <div class="row g-5">
                <div class="col-md-7 col-lg-8 order-md-last">
                    <h4 class="mb-3">Resumen del Pedido</h4>
                    <?php if (!empty($items_del_pedido_actual)): ?>
                        <ul class="list-group mb-3">
                            <?php foreach ($items_del_pedido_actual as $item): ?>
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0"><?php echo htmlspecialchars($item['nombre_producto_en_pedido']); ?> (x<?php echo $item['cantidad']; ?>)</h6>
                                        <small class="text-muted">
                                            <?php
                                            $attrs_json = json_decode($item['atributos_seleccionados_en_pedido'], true);
                                            $attr_display = [];
                                            if (!empty($attrs_json['talle'])) $attr_display[] = "Talle: " . htmlspecialchars($attrs_json['talle']);
                                            if (!empty($attrs_json['color'])) $attr_display[] = "Color: " . htmlspecialchars($attrs_json['color']);
                                            if (!empty($attrs_json['tipo_mate'])) $attr_display[] = "Tipo: " . htmlspecialchars($attrs_json['tipo_mate']);
                                            echo implode(', ', $attr_display);
                                            ?>
                                        </small>
                                    </div>
                                    <span class="text-muted">$<?php echo number_format($item['subtotal_linea'], 2, ',', '.'); ?></span>
                                </li>
                            <?php endforeach; ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <strong>$<?php echo number_format($subtotal_general, 2, ',', '.'); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Envío</span>
                                <strong>$<?php echo number_format($costo_envio_ejemplo, 2, ',', '.'); ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <strong class="text-success">Total (ARS)</strong>
                                <strong class="text-success">$<?php echo number_format($total_con_envio, 2, ',', '.'); ?></strong>
                            </li>
                        </ul>
                    <?php else: ?>
                        <p class="text-center">No hay ítems en tu pedido para mostrar.</p>
                    <?php endif; ?>
                </div>

                <div class="col-md-5 col-lg-4">
                    <h4 class="mb-3">Datos de Envío y Pago</h4>
                    <form action="../../backend/php/procesar_pedido.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección de Envío Completa</label>
                            <textarea class="form-control <?php echo isset($form_data['errors']['direccion_envio']) ? 'is-invalid' : ''; ?>" id="direccion" name="direccion_envio" rows="3" placeholder="Calle, Número, Piso/Dpto, Ciudad, Provincia, CP" required><?php echo htmlspecialchars($form_data['direccion_envio'] ?? ''); ?></textarea>
                            <div class="invalid-feedback">
                                <?php echo htmlspecialchars($form_data['errors']['direccion_envio'] ?? 'Por favor, ingresa tu dirección de envío.'); ?>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h4 class="mb-3">Método de Pago</h4>
                        <div class="my-3">
                            <div class="form-check">
                                <input id="credito" name="metodo_pago" type="radio" class="form-check-input" value="Tarjeta de Crédito" <?php echo (isset($form_data['metodo_pago']) && $form_data['metodo_pago'] === 'Tarjeta de Crédito') ? 'checked' : ((!isset($form_data['metodo_pago'])) ? 'checked' : ''); ?> required>
                                <label class="form-check-label" for="credito">Tarjeta de Crédito</label>
                            </div>
                            <div class="form-check">
                                <input id="debito" name="metodo_pago" type="radio" class="form-check-input" value="Tarjeta de Débito" <?php echo (isset($form_data['metodo_pago']) && $form_data['metodo_pago'] === 'Tarjeta de Débito') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="debito">Tarjeta de Débito</label>
                            </div>
                            <div class="form-check">
                                <input id="mercadopago" name="metodo_pago" type="radio" class="form-check-input" value="MercadoPago" <?php echo (isset($form_data['metodo_pago']) && $form_data['metodo_pago'] === 'MercadoPago') ? 'checked' : ''; ?> required>
                                <label class="form-check-label" for="mercadopago">MercadoPago</label>
                            </div>
                        </div>
                        <div class="row gy-3">
                            <div class="col-12">
                                <label for="cc-name" class="form-label">Nombre en la tarjeta</label>
                                <input type="text" class="form-control <?php echo isset($form_data['errors']['cc-name']) ? 'is-invalid' : ''; ?>" id="cc-name" name="cc-name" placeholder="" value="<?php echo htmlspecialchars($form_data['cc-name'] ?? ''); ?>">
                                <div class="invalid-feedback"><?php echo htmlspecialchars($form_data['errors']['cc-name'] ?? ''); ?></div>
                            </div>
                            <div class="col-12">
                                <label for="cc-number" class="form-label">Número de tarjeta</label>
                                <input type="text" class="form-control <?php echo isset($form_data['errors']['cc-number']) ? 'is-invalid' : ''; ?>" id="cc-number" name="cc-number" placeholder="" value="<?php echo htmlspecialchars($form_data['cc-number'] ?? ''); ?>">
                                <div class="invalid-feedback"><?php echo htmlspecialchars($form_data['errors']['cc-number'] ?? ''); ?></div>
                            </div>
                            <div class="col-md-7 col-sm-12">
                                <label for="cc-expiration" class="form-label">Vencimiento</label>
                                <input type="text" class="form-control <?php echo isset($form_data['errors']['cc-expiration']) ? 'is-invalid' : ''; ?>" id="cc-expiration" name="cc-expiration" placeholder="MM/AA" value="<?php echo htmlspecialchars($form_data['cc-expiration'] ?? ''); ?>">
                                <div class="invalid-feedback"><?php echo htmlspecialchars($form_data['errors']['cc-expiration'] ?? ''); ?></div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                                <label for="cc-cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control <?php echo isset($form_data['errors']['cc-cvv']) ? 'is-invalid' : ''; ?>" id="cc-cvv" name="cc-cvv" placeholder="" value="<?php echo htmlspecialchars($form_data['cc-cvv'] ?? ''); ?>">
                                <div class="invalid-feedback"><?php echo htmlspecialchars($form_data['errors']['cc-cvv'] ?? ''); ?></div>
                            </div>
                        </div>
                        <hr class="my-4">
                        <button class="w-100 btn btn-primary btn-lg btn-checkout-confirm" type="submit" name="confirmar_pedido_submit">Confirmar y Pagar</button>
                    </form>
                </div>
            </div>
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
                        <li><a href="#"><i class="bi bi-caret-right-fill"></i> Sobre Nosotros</a></li>
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
    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>