<?php
session_start();
require_once '../conexion/db_connect.php';

$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = (isset($script_path_parts[1]) && !empty($script_path_parts[1]) && is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $script_path_parts[1])) ? '/' . $script_path_parts[1] : '';
$cart_page_url = $project_root_segment . "/frontend/cart/cart.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart_item_key']) && isset($_POST['new_quantity'])) {
    $cart_item_key_to_update = $_POST['update_cart_item_key'];
    $new_quantity = (int)$_POST['new_quantity'];

    if (isset($_SESSION['carrito'][$cart_item_key_to_update])) {
        $id_producto = $_SESSION['carrito'][$cart_item_key_to_update]['id_producto'];

        if ($new_quantity > 0) {
            $sql_stock = "SELECT stock, nombre_producto FROM productos WHERE id_producto = ?";
            $stmt_stock = $conn->prepare($sql_stock);
            if ($stmt_stock) {
                $stmt_stock->bind_param("i", $id_producto);
                $stmt_stock->execute();
                $result_stock = $stmt_stock->get_result();
                if ($producto_db = $result_stock->fetch_assoc()) {
                    if ($new_quantity <= $producto_db['stock']) {
                        $_SESSION['carrito'][$cart_item_key_to_update]['cantidad'] = $new_quantity;
                        $_SESSION['cart_page_message'] = "Cantidad de '" . htmlspecialchars($producto_db['nombre_producto']) . "' actualizada.";
                        $_SESSION['cart_page_message_type'] = 'success';
                    } else {
                        $_SESSION['carrito'][$cart_item_key_to_update]['cantidad'] = $producto_db['stock'];
                        $_SESSION['cart_page_message'] = "No hay suficiente stock para '" . htmlspecialchars($producto_db['nombre_producto']) . "'. Cantidad ajustada a " . $producto_db['stock'] . ".";
                        $_SESSION['cart_page_message_type'] = 'warning';
                    }
                } else {
                    $_SESSION['cart_page_message'] = "Producto no encontrado para actualizar stock.";
                    $_SESSION['cart_page_message_type'] = 'danger';
                    unset($_SESSION['carrito'][$cart_item_key_to_update]);
                }
                $stmt_stock->close();
            } else {
                $_SESSION['cart_page_message'] = "Error al verificar stock del producto.";
                $_SESSION['cart_page_message_type'] = 'danger';
            }
        } else {
            unset($_SESSION['carrito'][$cart_item_key_to_update]);
            $_SESSION['cart_page_message'] = "Producto eliminado del carrito.";
            $_SESSION['cart_page_message_type'] = 'info';
        }
    } else {
        $_SESSION['cart_page_message'] = "Ítem no encontrado en el carrito para actualizar.";
        $_SESSION['cart_page_message_type'] = 'danger';
    }
    $conn->close();
    header("Location: " . $cart_page_url);
    exit();
} else {
    header("Location: " . $cart_page_url);
    exit();
}
