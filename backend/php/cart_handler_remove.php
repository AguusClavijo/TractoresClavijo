<?php
session_start();

$script_path_parts = explode('/', dirname($_SERVER['SCRIPT_NAME']));
$project_root_segment = (isset($script_path_parts[1]) && !empty($script_path_parts[1]) && is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $script_path_parts[1])) ? '/' . $script_path_parts[1] : '';
$cart_page_url = $project_root_segment . "/frontend/cart/cart.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_cart_item_key'])) {
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
    header("Location: " . $cart_page_url);
    exit();
} else {
    header("Location: " . $cart_page_url);
    exit();
}
