<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}
if(!isset($_GET['id'])) {
    header('Location: pesanan.php');
    exit();
}
$order_id = (int)$_GET['id'];

$stmt_old_status = $conn->prepare("SELECT status FROM orders WHERE id = ?");
$stmt_old_status->bind_param("i", $order_id);
$stmt_old_status->execute();
$old_order = $stmt_old_status->get_result()->fetch_assoc();
$old_status = $old_order['status'];
$stmt_old_status->close();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];

    if ($new_status === 'paid' && !in_array($old_status, ['paid', 'shipped', 'completed'])) {
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
    }

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: detail_pesanan.php?id=$order_id");
    exit();
}


$stmt = $conn->prepare("SELECT o.*, u.username, u.email, u.full_name, u.address FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0) {
    die("Pesanan tidak ditemukan.");
}
$order = $result->fetch_assoc();
$stmt->close();

$items_stmt = $conn->prepare("SELECT oi.*, p.name as product_name, p.image as product_image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$items = [];
while($item = $items_result->fetch_assoc()){
    $items[] = $item;
}
$items_stmt->close();

include '_header_admin.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Pesanan #<?php echo $order['id']; ?></h1>
    <a href="../invoice.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm" target="_blank"><i class="bi bi-printer"></i> Lihat Invoice</a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <h4 class="mb-3">Item yang Dipesan</h4>
        <div class="card">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach($items as $item): ?>
                        <li class="list-group-item d-flex align-items-center">
                            <img src="<?php echo BASE_URL . 'uploads/products/' . htmlspecialchars($item['product_image']); ?>" width="60" class="me-3">
                            <div>
                                <strong><?php echo htmlspecialchars($item['product_name']); ?></strong><br>
                                <small class="text-muted"><?php echo $item['quantity']; ?> x Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></small>
                            </div>
                            <strong class="ms-auto">Rp <?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-footer fw-bold d-flex justify-content-between">
                <span>TOTAL</span>
                <span>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h4 class="mb-3">Detail Pelanggan & Status</h4>
        <div class="card">
            <div class="card-body">
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Alamat:</strong><br><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                <?php if(!empty($order['payment_proof'])): ?>
                <p><strong>Bukti Pembayaran:</strong> <a href="<?php echo BASE_URL . 'uploads/proofs/' . $order['payment_proof']; ?>" target="_blank">Lihat Bukti</a></p>
                <?php endif; ?>
                <hr>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="status" class="form-label"><strong>Status Pesanan</strong></label>
                        <select name="status" id="status" class="form-select">
                            <option value="waiting_payment" <?php if($order['status'] == 'waiting_payment') echo 'selected'; ?>>Menunggu Pembayaran</option>
                            <option value="verifying" <?php if($order['status'] == 'verifying') echo 'selected'; ?>>Verifikasi</option>
                            <option value="paid" <?php if($order['status'] == 'paid') echo 'selected'; ?>>Lunas</option>
                            <option value="shipped" <?php if($order['status'] == 'shipped') echo 'selected'; ?>>Dikirim</option>
                            <option value="completed" <?php if($order['status'] == 'completed') echo 'selected'; ?>>Selesai</option>
                            <option value="ditolak" <?php if($order['status'] == 'ditolak') echo 'selected'; ?>>Ditolak</option>
                        </select>
                    </div>
                    <button type="submit" name="update_status" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '_footer_admin.php'; ?>
