<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}
include '_header_admin.php';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Pesanan</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pengguna</th>
                <th>Total</th>
                <th class="text-center">Status</th>
                <th>Tanggal</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT o.id, o.total_amount, o.status, o.created_at, u.username 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0):
                while ($order = $result->fetch_assoc()):
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
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['username']); ?></td>
                <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                <td class="text-center"><span class="badge <?php echo $status_badge; ?>"><?php echo $status_text; ?></span></td>
                <td><?php echo date("d M Y, H:i", strtotime($order['created_at'])); ?></td>
                <td class="text-center">
                    <a href="detail_pesanan.php?id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">Detail</a>
                </td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr><td colspan="6" class="text-center">Tidak ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include '_footer_admin.php'; ?>
