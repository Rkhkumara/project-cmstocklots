<?php
include 'includes/header.php'; 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<div class="container mt-5 fade-in-element">
    <h2 class="section-title text-center mb-4">Riwayat Pesanan</h2>
    
    <?php if(isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Pesanan</th>
                    <th>Tanggal</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $user_id = $_SESSION['user_id'];
                $stmt = $conn->prepare("SELECT id, total_amount, status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && $result->num_rows > 0) {
                    while ($order = $result->fetch_assoc()){
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
                    <td><strong>#<?php echo $order['id']; ?></strong></td>
                    <td><?php echo date("d M Y, H:i", strtotime($order['created_at'])); ?></td>
                    <td class="text-end">Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                    <td class="text-center"><span class="badge <?php echo $status_badge; ?>"><?php echo $status_text; ?></span></td>
                    <td class="text-center">
                        <a href="invoice.php?order_id=<?php echo $order['id']; ?>" class="btn btn-secondary btn-sm" target="_blank" title="Invoice"><i class="bi bi-receipt"></i></a>
                        <?php if($order['status'] == 'waiting_payment' || $order['status'] == 'ditolak'): ?>
                            <a href="payment.php?order_id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm" title="Lanjutkan Pembayaran">Bayar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center py-4'>Anda belum memiliki riwayat pesanan.</td></tr>";
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="card card-modern mt-4">
        <div class="card-body">
            <h5 class="card-title">Penjelasan Status Pesanan</h5>
            <ul class="list-unstyled">
                <li><span class="badge bg-warning text-dark">Menunggu Pembayaran</span>: Pesanan Anda telah dibuat, silakan lakukan pembayaran.</li>
                <li><span class="badge bg-info text-dark">Verifikasi</span>: Bukti pembayaran Anda sedang kami periksa.</li>
                <li><span class="badge bg-danger">Ditolak</span>: Pembayaran Anda ditolak. Silakan unggah ulang bukti pembayaran yang valid.</li>
                <li><span class="badge bg-success">Lunas</span>: Pembayaran Anda telah kami terima dan pesanan sedang disiapkan.</li>
                <li><span class="badge bg-primary">Dikirim</span>: Pesanan Anda sedang dalam perjalanan ke alamat tujuan.</li>
                <li><span class="badge bg-secondary">Selesai</span>: Pesanan telah sampai di tujuan.</li>
            </ul>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>


<?php