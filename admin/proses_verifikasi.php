<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['action'])) {
    $order_id = (int)$_POST['order_id'];
    $action = $_POST['action'];
    $new_status = '';

    if ($action === 'approve') {
        $new_status = 'paid';
        
        $items_to_update_stmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
        $items_to_update_stmt->bind_param("i", $order_id);
        $items_to_update_stmt->execute();
        $items_to_update_result = $items_to_update_stmt->get_result();

        $update_stock_stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        while ($item = $items_to_update_result->fetch_assoc()) {
            $update_stock_stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $update_stock_stmt->execute();
        }
        $items_to_update_stmt->close();
        $update_stock_stmt->close();

    } elseif ($action === 'reject') {
        $new_status = 'ditolak';
    }

    if (!empty($new_status)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: verifikasi_pembayaran.php');
exit();
?>
