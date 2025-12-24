<?php
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}
if (!isset($_GET['order_id'])) {
    header("Location: " . BASE_URL . "akun.php");
    exit();
}

$order_id = (int)$_GET['order_id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

$sql = "SELECT o.*, u.username, u.email, u.full_name, u.address 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ?";

if ($user_role !== 'admin') {
    $sql .= " AND o.user_id = ?";
}

$stmt = $conn->prepare($sql);
if ($user_role !== 'admin') {
    $stmt->bind_param("ii", $order_id, $user_id);
} else {
    $stmt->bind_param("i", $order_id);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invoice tidak ditemukan atau Anda tidak memiliki akses.");
}
$order = $result->fetch_assoc();
$stmt->close();

$items_stmt = $conn->prepare("SELECT oi.*, p.name as product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$items = [];
while($item = $items_result->fetch_assoc()){
    $items[] = $item;
}
$items_stmt->close();

$back_url = BASE_URL . 'riwayat_pesanan.php'; 
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $back_url = BASE_URL . 'admin/pesanan.php'; 
}

$status_badge = '';
$status_text = '';
switch ($order['status']) {
    case 'waiting_payment':
        $status_badge = 'bg-warning text-dark';
        $status_text = 'Menunggu Pembayaran';
        break;
    case 'verifying':
        $status_badge = 'bg-info text-dark';
        $status_text = 'Verifikasi';
        break;
    case 'paid':
        $status_badge = 'bg-success';
        $status_text = 'Lunas';
        break;
    case 'shipped':
        $status_badge = 'bg-primary';
        $status_text = 'Dikirim';
        break;
    case 'completed':
        $status_badge = 'bg-secondary';
        $status_text = 'Selesai';
        break;
    case 'ditolak':
        $status_badge = 'bg-danger';
        $status_text = 'Ditolak';
        break;
    default:
        $status_badge = 'bg-light text-dark';
        $status_text = ucfirst(str_replace('_', ' ', $order['status']));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order['id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1B3C53;
            --bg-color: #F9F3EF;
        }
        body { font-family: 'Montserrat', sans-serif; background-color: var(--bg-color); }
        .invoice-container { max-width: 800px; margin: auto; }
        .invoice-box { background: #fff; box-shadow: 0 0 20px rgba(0, 0, 0, .05); }
        .invoice-header { background-color: var(--primary-color); color: var(--bg-color); padding: 40px; }
        .invoice-header h1 { font-family: 'Cormorant Garamond', serif; margin: 0; }
        .invoice-body { padding: 40px; }
        .invoice-table thead { background-color: #f8f9fa; }
        .invoice-footer { background-color: #f8f9fa; padding: 20px 40px; color: #6c757d; font-size: 0.9rem; }
        .print-button-container { text-align: center; margin-bottom: 20px; }
        @media print {
            body { background-color: #fff; }
            .print-button-container, .back-button { display: none; }
            .invoice-box { box-shadow: none; border: 0; margin: 0; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="print-button-container">
            <a href="<?php echo $back_url; ?>" class="btn btn-secondary back-button"><i class="bi bi-arrow-left"></i> Kembali</a>
            <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak</button>
        </div>
        <div class="invoice-box">
            <div class="invoice-header text-center">
                <h1>INVOICE</h1>
                <p class="mb-0">Nomor Pesanan: #<?php echo $order['id']; ?></p>
            </div>
            <div class="invoice-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h5 class="mb-3">Ditagihkan kepada:</h5>
                        <p class="mb-1"><strong><?php echo htmlspecialchars($order['full_name'] ?? $order['username']); ?></strong></p>
                        <p class="mb-1"><?php echo htmlspecialchars($order['email']); ?></p>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($order['address'] ?? 'Alamat tidak tersedia')); ?></p>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <h5 class="mb-3">Detail:</h5>
                        <p class="mb-1"><strong>Tanggal Invoice:</strong> <?php echo date("d M Y", strtotime($order['created_at'])); ?></p>
                        <p class="mb-1"><strong>Status:</strong> <span class="badge <?php echo $status_badge; ?>"><?php echo $status_text; ?></span></p>
                        <p class="mb-1"><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table invoice-table">
                        <thead class="table-light">
                            <tr>
                                <th>Deskripsi</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td class="text-center"><?php echo $item['quantity']; ?></td>
                                <td class="text-end">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                <td class="text-end">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"></td>
                                <td class="text-end">Subtotal</td>
                                <td class="text-end">Rp <?php echo number_format($order['total_amount'] - $order['shipping_cost'], 0, ',', '.'); ?></td>
                            </tr>
                             <tr>
                                <td colspan="2"></td>
                                <td class="text-end">Biaya Pengiriman</td>
                                <td class="text-end">Rp <?php echo number_format($order['shipping_cost'], 0, ',', '.'); ?></td>
                            </tr>
                            <tr class="fw-bold">
                                <td colspan="2"></td>
                                <td class="text-end">Total</td>
                                <td class="text-end">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="invoice-footer text-center">
                <p>Terima kasih telah berbelanja di CM Stocklots</p>
                <p class="small">Jika ada pertanyaan, hubungi kami di cmstocklots@gmail.com</p>
            </div>
        </div>
    </div>
</body>
</html>
