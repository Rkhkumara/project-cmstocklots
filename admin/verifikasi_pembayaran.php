<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
include '_header_admin.php';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Verifikasi Pembayaran</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>User</th>
                <th>Total</th>
                <th>Bukti Pembayaran</th>
                <th>Tanggal Order</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT o.id, o.total_amount, o.payment_proof, o.created_at, u.username 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.id 
                    WHERE o.status = 'verifying' 
                    ORDER BY o.created_at ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td>Rp <?php echo number_format($row['total_amount'], 0, ',', '.'); ?></td>
                <td>
                    <a href="../uploads/proofs/<?php echo htmlspecialchars($row['payment_proof']); ?>" target="_blank">
                       Bukti
                    </a>
                </td>
                <td><?php echo date("d M Y H:i", strtotime($row['created_at'])); ?></td>
                <td>
                    <form action="proses_verifikasi.php" method="POST" class="d-inline">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Setujui</button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Tolak</button>
                    </form>
                </td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr><td colspan="6" class="text-center">Tidak ada pembayaran yang perlu diverifikasi.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include '_footer_admin.php'; ?>