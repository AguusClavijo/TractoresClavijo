<?php
session_start();

$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
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

$login_page_url = $project_root_segment . "/frontend/login/login.php";
$default_redirect_url = $_SERVER['HTTP_REFERER'] ?? $project_root_segment . "/frontend/merch/merch.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart_submit'])) {

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['auth_message'] = "Debes iniciar sesión para añadir productos al carrito.";
        $_SESSION['auth_message_type'] = 'warning';
        $_SESSION['show_popup'] = true;
        $_SESSION['active_form'] = 'login';
        header("Location: " . $login_page_url);
        exit();
    }

    $id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
    $cantidad_solicitada = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $talle_seleccionado = isset($_POST['size_seleccionado']) && !empty($_POST['size_seleccionado']) ? trim($_POST['size_seleccionado']) : null;
    $color_seleccionado = isset($_POST['color_seleccionado']) && !empty($_POST['color_seleccionado']) ? trim($_POST['color_seleccionado']) : null;
    $tipo_mate_seleccionado = isset($_POST['tipo_mate_seleccionado']) && !empty($_POST['tipo_mate_seleccionado']) ? trim($_POST['tipo_mate_seleccionado']) : null;


    if ($cantidad_solicitada < 1) {
        $cantidad_solicitada = 1;
    }

    if ($id_producto > 0) {
        require_once '../conexion/db_connect.php';

        $nombre_producto = "Producto Desconocido";
        $precio_producto = 0.00;
        $imagen_url_producto = "";
        $stock_disponible = 0;

        $sql_prod_info = "SELECT nombre_producto, precio_venta, imagen_url, stock FROM productos WHERE id_producto = ? AND activo = TRUE";
        $stmt_info = $conn->prepare($sql_prod_info);

        if ($stmt_info) {
            $stmt_info->bind_param("i", $id_producto);
            $stmt_info->execute();
            $res_info = $stmt_info->get_result();
            if ($prod_data = $res_info->fetch_assoc()) {
                $nombre_producto = $prod_data['nombre_producto'];
                $precio_producto = $prod_data['precio_venta'];
                $imagen_url_producto = $prod_data['imagen_url'];
                $stock_disponible = (int)$prod_data['stock'];
            } else {
                $_SESSION['cart_message'] = "El producto seleccionado no está disponible.";
                $_SESSION['cart_message_type'] = 'danger';
                header("Location: " . $default_redirect_url);
                exit();
            }
            $stmt_info->close();
        } else {
            $_SESSION['cart_message'] = "Error al obtener información del producto.";
            $_SESSION['cart_message_type'] = 'danger';
            header("Location: " . $default_redirect_url);
            exit();
        }

        $cart_item_key_parts = [(string)$id_producto];
        if ($talle_seleccionado) $cart_item_key_parts[] = 't' . $talle_seleccionado;
        if ($color_seleccionado) $cart_item_key_parts[] = 'c' . $color_seleccionado;
        if ($tipo_mate_seleccionado) $cart_item_key_parts[] = 'm' . $tipo_mate_seleccionado;
        $cart_item_key = implode('-', $cart_item_key_parts);


        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $cantidad_actual_en_carrito = $_SESSION['carrito'][$cart_item_key]['cantidad'] ?? 0;
        $cantidad_final_en_carrito = $cantidad_actual_en_carrito + $cantidad_solicitada;

        if ($cantidad_final_en_carrito > $stock_disponible) {
            $_SESSION['cart_message'] = "No hay suficiente stock para '" . htmlspecialchars($nombre_producto) . "'. Solicitado: $cantidad_solicitada, Disponible: " . ($stock_disponible - $cantidad_actual_en_carrito) . ".";
            $_SESSION['cart_message_type'] = 'warning';
        } else {
            if (isset($_SESSION['carrito'][$cart_item_key])) {
                $_SESSION['carrito'][$cart_item_key]['cantidad'] = $cantidad_final_en_carrito;
                $_SESSION['cart_message'] = "'" . htmlspecialchars($nombre_producto) . "' actualizado en tu carrito.";
                $_SESSION['cart_message_type'] = 'success';
            } else {
                $_SESSION['carrito'][$cart_item_key] = [
                    'id_producto' => $id_producto,
                    'nombre' => $nombre_producto,
                    'precio' => $precio_producto,
                    'cantidad' => $cantidad_solicitada,
                    'imagen_url' => $imagen_url_producto,
                    'talle' => $talle_seleccionado,
                    'color' => $color_seleccionado,
                    'tipo_mate' => $tipo_mate_seleccionado
                ];
                $_SESSION['cart_message'] = "'" . htmlspecialchars($nombre_producto) . "' añadido a tu carrito.";
                $_SESSION['cart_message_type'] = 'success';
            }
        }
    } else {
        $_SESSION['cart_message'] = "ID de producto inválido.";
        $_SESSION['cart_message_type'] = 'danger';
    }

    if (isset($conn) && $conn instanceof mysqli && $conn->ping()) {
        $conn->close();
    }

    header("Location: " . $default_redirect_url);
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart_item_key']) && isset($_POST['new_quantity'])) {
    $cart_item_key_to_update = $_POST['update_cart_item_key'];
    $new_quantity = (int)$_POST['new_quantity'];

    if (isset($_SESSION['carrito'][$cart_item_key_to_update])) {
        $id_producto_en_carrito = $_SESSION['carrito'][$cart_item_key_to_update]['id_producto'];

        if ($new_quantity > 0) {
            require_once '../conexion/db_connect.php';
            $sql_stock = "SELECT stock, nombre_producto FROM productos WHERE id_producto = ?";
            $stmt_stock = $conn->prepare($sql_stock);
            $nombre_item_actualizado = $_SESSION['carrito'][$cart_item_key_to_update]['nombre'];

            if ($stmt_stock) {
                $stmt_stock->bind_param("i", $id_producto_en_carrito);
                $stmt_stock->execute();
                $result_stock = $stmt_stock->get_result();
                if ($producto_db = $result_stock->fetch_assoc()) {
                    $nombre_item_actualizado = $producto_db['nombre_producto'];
                    if ($new_quantity <= $producto_db['stock']) {
                        $_SESSION['carrito'][$cart_item_key_to_update]['cantidad'] = $new_quantity;
                        $_SESSION['cart_page_message'] = "Cantidad de '" . htmlspecialchars($nombre_item_actualizado) . "' actualizada.";
                        $_SESSION['cart_page_message_type'] = 'success';
                    } else {
                        $_SESSION['carrito'][$cart_item_key_to_update]['cantidad'] = $producto_db['stock'];
                        $_SESSION['cart_page_message'] = "Stock insuficiente. Cantidad de '" . htmlspecialchars($nombre_item_actualizado) . "' ajustada a " . $producto_db['stock'] . ".";
                        $_SESSION['cart_page_message_type'] = 'warning';
                    }
                } else {
                    $_SESSION['cart_page_message'] = "Producto no encontrado para actualizar stock. Ítem eliminado.";
                    $_SESSION['cart_page_message_type'] = 'danger';
                    unset($_SESSION['carrito'][$cart_item_key_to_update]);
                }
                $stmt_stock->close();
            } else {
                $_SESSION['cart_page_message'] = "Error al verificar stock del producto.";
                $_SESSION['cart_page_message_type'] = 'danger';
            }
            if (isset($conn) && $conn instanceof mysqli) $conn->close();
        } else {
            $nombre_item_eliminado = $_SESSION['carrito'][$cart_item_key_to_update]['nombre'] ?? 'Producto';
            unset($_SESSION['carrito'][$cart_item_key_to_update]);
            $_SESSION['cart_page_message'] = "'" . htmlspecialchars($nombre_item_eliminado) . "' eliminado del carrito.";
            $_SESSION['cart_page_message_type'] = 'info';
        }
    } else {
        $_SESSION['cart_page_message'] = "Ítem no encontrado en el carrito para actualizar.";
        $_SESSION['cart_page_message_type'] = 'danger';
    }
    header("Location: " . $project_root_segment . "/frontend/cart/cart.php");
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_cart_item_key'])) {
    $cart_item_key_to_remove = $_POST['remove_cart_item_key'];

    if (isset($_SESSION['carrito'][$cart_item_key_to_remove])) {
        $nombre_producto_eliminado = $_SESSION['carrito'][$cart_item_key_to_remove]['nombre'] ?? 'Producto';
        unset($_SESSION['carrito'][$cart_item_key_to_remove]);
        $_SESSION['cart_page_message'] = "'" . htmlspecialchars($nombre_producto_eliminado) . "' eliminado de tu carrito.";
        $_SESSION['cart_page_message_type'] = 'success';
    } else {
        $_SESSION['cart_page_message'] = "Ítem no encontrado en el carrito para eliminar.";
        $_SESSION['cart_page_message_type'] = 'danger';
    }
    header("Location: " . $project_root_segment . "/frontend/cart/cart.php");
    exit();
} else {
    header("Location: " . $project_root_segment . "/frontend/main/main.php");
    exit();
}
