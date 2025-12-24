<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}
include '_header_admin.php';
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data Pengguna</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Registrasi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
            if ($result && $result->num_rows > 0):
                while ($user = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <span class="badge <?php echo $user['role'] === 'admin' ? 'bg-success' : 'bg-secondary'; ?>">
                        <?php echo ucfirst($user['role']); ?>
                    </span>
                </td>
                <td><?php echo date("d M Y, H:i", strtotime($user['created_at'])); ?></td>
            </tr>
            <?php 
                endwhile;
            else:
            ?>
            <tr><td colspan="5" class="text-center">Tidak ada pengguna terdaftar.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include '_footer_admin.php'; ?>