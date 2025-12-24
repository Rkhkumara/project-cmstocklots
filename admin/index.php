<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}
include '_header_admin.php';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Produk</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="tambah_produk.php" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-circle"></i> Tambah</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()):
                    $row_class = $row['is_active'] ? '' : 'table-secondary text-muted';
            ?>
            <tr class="<?php echo $row_class; ?>">
                <td><?php echo $row['id']; ?></td>
                <td><img src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($row['image']); ?>" width="80" onerror="this.onerror=null;this.src='https://placehold.co/100x100/EEE/31343C?text=No+Image';"></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td>
                    <?php if ($row['is_active']): ?>
                        <span class="badge bg-success">Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Nonaktif</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit_produk.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                    <?php if ($row['is_active']): ?>
                        <a href="hapus_produk.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menonaktifkan produk ini?');"><i class="bi bi-eye-slash"></i> Nonaktifkan</a>
                    <?php else: ?>
                         <a href="hapus_produk.php?id=<?php echo $row['id']; ?>&action=activate" class="btn btn-info btn-sm" onclick="return confirm('Anda yakin ingin mengaktifkan kembali produk ini?');"><i class="bi bi-eye"></i> Aktifkan</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php 
                endwhile;
            }
            ?>
        </tbody>
    </table>
</div>
<?php include '_footer_admin.php'; ?>