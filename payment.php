<?php
include 'includes/header.php';
if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}
$order_id = (int)$_GET['order_id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Pesanan tidak ditemukan.</div></div>";
    include 'includes/footer.php';
    exit();
}
$order = $result->fetch_assoc();
?>
<div class="container mt-5">
<div class="row justify-content-center">
    <div class="col-md-8 fade-in-element">
        <div class="card card-modern">
            <div class="card-header text-center">
                <h3>Pembayaran <br>#<?php echo $order['id']; ?></h3>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['upload_error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['upload_error']; unset($_SESSION['upload_error']); ?>
                </div>
                <?php endif; ?>

                <div class="alert alert-light border">
                    <h4 class="alert-heading">Instruksi Pembayaran</h4>
                    <p>Silakan lakukan transfer sejumlah <strong>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></strong> ke rekening berikut:</p>
                    <ul>
                        <li><strong>Bank BCA:</strong> 4960426377 (a/n Rakha Aditisna Kumara)</li>
                    </ul>
                    <hr>
                    <p class="mb-0">Setelah melakukan pembayaran, mohon unggah bukti transfer atau screenshot pembayaran Anda pada form di bawah ini.</p>
                </div>

                <hr>
                
                <?php if($order['status'] == 'waiting_payment' || $order['status'] == 'ditolak'): ?>
                <form action="upload_payment_proof.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Unggah Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                        <div class="form-text">Format yang diterima: JPG, JPEG, PNG. Ukuran maks: 2MB.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
                </form>
                <?php elseif($order['status'] == 'verifying'): ?>
                <div class="alert alert-warning text-center">
                    <h4>Pembayaran Anda sedang diverifikasi.</h4>
                    <p>Harap tunggu hingga 1x24 jam untuk tim kami memproses pembayaran Anda. Terima kasih.</p>
                    <img src="<?php echo BASE_URL; ?>uploads/proofs/<?php echo htmlspecialchars($order['payment_proof']); ?>" class="img-fluid rounded mt-3" style="max-height: 400px;" alt="Bukti Pembayaran">
                </div>
                <?php else: ?>
                 <div class="alert alert-success text-center">
                    <h4>Pembayaran untuk pesanan ini telah berhasil.</h4>
                    <a href="riwayat_pesanan.php" class="btn btn-primary">Lihat Riwayat Pesanan</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'includes/footer.php'; ?>
