<?php
session_start();
require_once '../conexion/db_connect.php';
require_once './mailer_config.php';

// ... (Lógica de $project_root_segment, $checkout_page_url, $thank_you_page_url como la tenías) ...
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
$checkout_page_url = $project_root_segment . "/frontend/checkout/checkout.php";
$thank_you_page_url = $project_root_segment . "/frontend/checkout/thank_you.php";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar_pedido_submit'])) {
    if (!isset($_SESSION['user_id']) || empty($_SESSION['pedido_actual_items']) || !isset($_SESSION['pedido_actual_total'])) {
        $_SESSION['checkout_message'] = ["Error al procesar el pedido. Sesión inválida o carrito vacío."]; // Array para consistencia
        $_SESSION['checkout_message_type'] = 'danger';
        header("Location: " . $checkout_page_url);
        exit();
    }

    $id_cliente = $_SESSION['user_id'];
    $total_pedido_from_session = $_SESSION['pedido_actual_total']; // Usar el total calculado y guardado en sesión
    $items_del_pedido_from_session = $_SESSION['pedido_actual_items']; // Usar los items guardados en sesión

    $direccion_envio_texto = trim($_POST['direccion_envio'] ?? '');
    $metodo_pago_seleccionado = trim($_POST['metodo_pago'] ?? '');

    // --- VALIDACIONES ---
    $checkout_errors = [];
    if (empty($direccion_envio_texto)) $checkout_errors['direccion_envio'] = "La dirección de envío es obligatoria.";
    if (empty($metodo_pago_seleccionado)) $checkout_errors['metodo_pago'] = "Debes seleccionar un método de pago.";

    if ($metodo_pago_seleccionado === 'Tarjeta de Crédito' || $metodo_pago_seleccionado === 'Tarjeta de Débito') {
        $cc_name = trim($_POST['cc-name'] ?? '');
        $cc_number = trim(str_replace(' ', '', $_POST['cc-number'] ?? ''));
        $cc_expiration = trim($_POST['cc-expiration'] ?? '');
        $cc_cvv = trim($_POST['cc-cvv'] ?? '');

        if (empty($cc_name)) $checkout_errors['cc-name'] = "El nombre en la tarjeta es obligatorio.";
        if (empty($cc_number)) {
            $checkout_errors['cc-number'] = "El número de tarjeta es obligatorio.";
        } elseif (!ctype_digit($cc_number) || strlen($cc_number) < 13 || strlen($cc_number) > 19) {
            $checkout_errors['cc-number'] = "Número de tarjeta inválido.";
        }
        if (empty($cc_expiration)) {
            $checkout_errors['cc-expiration'] = "Vencimiento es obligatorio.";
        } elseif (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2})$/', $cc_expiration, $matches)) {
            $checkout_errors['cc-expiration'] = "Formato de vencimiento inválido (MM/AA).";
        } else {
            $exp_year = (int)('20' . $matches[2]);
            $current_year = (int)date('Y');
            $exp_month = (int)$matches[1];
            $current_month = (int)date('m');
            if ($exp_year < $current_year || ($exp_year == $current_year && $exp_month < $current_month)) {
                $checkout_errors['cc-expiration'] = "La tarjeta ha expirado.";
            }
        }
        if (empty($cc_cvv)) {
            $checkout_errors['cc-cvv'] = "CVV es obligatorio.";
        } elseif (!ctype_digit($cc_cvv) || (strlen($cc_cvv) !== 3 && strlen($cc_cvv) !== 4)) {
            $checkout_errors['cc-cvv'] = "CVV inválido.";
        }
    }

    if (!empty($checkout_errors)) {
        $_SESSION['checkout_message'] = array_values($checkout_errors); // Enviar solo los mensajes
        $_SESSION['checkout_message_type'] = 'danger';
        $_SESSION['checkout_form_data'] = $_POST; // Guardar todo el POST
        $_SESSION['checkout_form_data']['errors'] = $checkout_errors; // Guardar errores específicos
        header("Location: " . $checkout_page_url);
        exit();
    }
    // --- FIN VALIDACIONES ---

    $conn->begin_transaction();
    try {
        // Obtener email y nombre del cliente para el correo
        $sql_cliente = "SELECT email, nombre, apellido FROM clientes WHERE id_cliente = ?";
        $stmt_cliente = $conn->prepare($sql_cliente);
        $stmt_cliente->bind_param("i", $id_cliente);
        $stmt_cliente->execute();
        $result_cliente = $stmt_cliente->get_result();
        $cliente_info = $result_cliente->fetch_assoc();
        $stmt_cliente->close();

        if (!$cliente_info) {
            throw new Exception("No se pudo encontrar la información del cliente.");
        }
        $cliente_email = $cliente_info['email'];
        $cliente_nombre_completo = $cliente_info['nombre'] . ' ' . $cliente_info['apellido'];

        // 1. Insertar en la tabla `pedidos`
        $sql_pedido = "INSERT INTO pedidos (id_cliente, total_pedido, direccion_envio_texto, metodo_pago_seleccionado, estado_pedido) 
                       VALUES (?, ?, ?, ?, 'Procesando')";
        $stmt_pedido = $conn->prepare($sql_pedido);
        if (!$stmt_pedido) throw new Exception("Error al preparar el pedido: " . $conn->error);

        $stmt_pedido->bind_param("idss", $id_cliente, $total_pedido_from_session, $direccion_envio_texto, $metodo_pago_seleccionado);
        if (!$stmt_pedido->execute()) throw new Exception("Error al guardar el pedido: " . $stmt_pedido->error);

        $id_pedido_insertado = $stmt_pedido->insert_id;
        $stmt_pedido->close();

        // 2. Insertar en la tabla `detalles_pedido` y construir cuerpo del email
        $sql_detalle = "INSERT INTO detalles_pedido (id_pedido, id_producto, nombre_producto_en_pedido, cantidad, precio_unitario_en_pedido, atributos_seleccionados_en_pedido, subtotal_linea) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_detalle = $conn->prepare($sql_detalle);
        if (!$stmt_detalle) throw new Exception("Error al preparar detalles del pedido: " . $conn->error);

        $email_body_items_html = "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
                                    <thead>
                                        <tr><th>Producto</th><th>Cantidad</th><th>Precio Unit.</th><th>Subtotal</th></tr>
                                    </thead>
                                    <tbody>";

        foreach ($items_del_pedido_from_session as $item) {
            $stmt_detalle->bind_param(
                "iisisds",
                $id_pedido_insertado,
                $item['id_producto'],
                $item['nombre_producto_en_pedido'],
                $item['cantidad'],
                $item['precio_unitario_en_pedido'],
                $item['atributos_seleccionados_en_pedido'],
                $item['subtotal_linea']
            );
            if (!$stmt_detalle->execute()) throw new Exception("Error al guardar detalle: " . $stmt_detalle->error);

            $email_body_items_html .= "<tr>
                                        <td>" . htmlspecialchars($item['nombre_producto_en_pedido']);
            $attrs = json_decode($item['atributos_seleccionados_en_pedido'], true);
            $attr_display_email = [];
            if (!empty($attrs['talle'])) $attr_display_email[] = "Talle: " . htmlspecialchars($attrs['talle']);
            if (!empty($attrs['color'])) $attr_display_email[] = "Color: " . htmlspecialchars($attrs['color']);
            if (!empty($attrs['tipo_mate'])) $attr_display_email[] = "Tipo: " . htmlspecialchars($attrs['tipo_mate']);
            if (!empty($attr_display_email)) $email_body_items_html .= "<br><small>(" . implode(', ', $attr_display_email) . ")</small>";
            $email_body_items_html .= "</td>
                                        <td style='text-align:center;'>" . $item['cantidad'] . "</td>
                                        <td style='text-align:right;'>$" . number_format($item['precio_unitario_en_pedido'], 2, ',', '.') . "</td>
                                        <td style='text-align:right;'>$" . number_format($item['subtotal_linea'], 2, ',', '.') . "</td>
                                      </tr>";

            $sql_update_stock = "UPDATE productos SET stock = stock - ? WHERE id_producto = ? AND stock >= ?";
            $stmt_stock = $conn->prepare($sql_update_stock);
            if (!$stmt_stock) throw new Exception("Error preparando actualización de stock: " . $conn->error);
            $stmt_stock->bind_param("iii", $item['cantidad'], $item['id_producto'], $item['cantidad']);
            if (!$stmt_stock->execute()) throw new Exception("Error actualizando stock: " . $stmt_stock->error);
            if ($stmt_stock->affected_rows === 0 && $item['cantidad'] > 0) {
                throw new Exception("Stock insuficiente para " . htmlspecialchars($item['nombre_producto_en_pedido']) . ".");
            }
            $stmt_stock->close();
        }
        $stmt_detalle->close();
        $email_body_items_html .= "</tbody></table>";

        // 4. Enviar Email de Confirmación
        $asunto_email_confirmacion = "Confirmación de tu Pedido #" . $id_pedido_insertado . " - Tractores Clavijo";
        $cuerpo_html_confirmacion = "<h1>¡Gracias por tu compra, " . htmlspecialchars($cliente_info['nombre']) . "!</h1>";
        $cuerpo_html_confirmacion .= "<p>Tu pedido #" . $id_pedido_insertado . " ha sido recibido y está siendo procesado.</p>";
        $cuerpo_html_confirmacion .= "<h3>Detalles del Pedido:</h3>" . $email_body_items_html;
        $cuerpo_html_confirmacion .= "<p style='margin-top: 15px;'><strong>Total del Pedido: $" . number_format($total_pedido_from_session, 2, ',', '.') . "</strong></p>";
        $cuerpo_html_confirmacion .= "<p><strong>Dirección de Envío:</strong><br>" . nl2br(htmlspecialchars($direccion_envio_texto)) . "</p>";
        $cuerpo_html_confirmacion .= "<p><strong>Método de Pago:</strong> " . htmlspecialchars($metodo_pago_seleccionado) . "</p>";
        $cuerpo_html_confirmacion .= "<p>Te notificaremos cuando tu pedido haya sido enviado.</p>";
        $cuerpo_html_confirmacion .= "<p>Saludos,<br>El equipo de Tractores Clavijo</p>";

        // Asegurarse de que la función enviar_email_recuperacion esté definida y sea adecuada
        // o crear una nueva función enviar_email_confirmacion si es necesario.
        // Por ahora, reutilizamos la existente.
        if (!enviar_email_recuperacion($cliente_email, $cliente_nombre_completo, $asunto_email_confirmacion, $cuerpo_html_confirmacion)) {
            // No tratar esto como un error fatal para el pedido, pero sí loguearlo.
            error_log("Error al enviar email de confirmación para el pedido #" . $id_pedido_insertado . " al cliente " . $cliente_email);
        }

        // Si todo fue bien, confirmar la transacción
        $conn->commit();

        unset($_SESSION['carrito']);
        unset($_SESSION['pedido_actual_items']);
        unset($_SESSION['pedido_actual_total']);
        unset($_SESSION['checkout_form_data']);

        $_SESSION['order_success_message'] = "¡Gracias por tu compra! Tu pedido #" . $id_pedido_insertado . " ha sido procesado exitosamente.";
        header("Location: " . $thank_you_page_url . "?pedido_id=" . $id_pedido_insertado);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['checkout_message'] = ["Hubo un error al procesar tu pedido: " . $e->getMessage()]; // Array para consistencia
        $_SESSION['checkout_message_type'] = 'danger';
        $_SESSION['checkout_form_data'] = $_POST;
        header("Location: " . $checkout_page_url);
        exit();
    }
} else {
    header("Location: " . $checkout_page_url);
    exit();
}
