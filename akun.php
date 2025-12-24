<?php 
include 'includes/header.php'; 
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
$user_stmt->close();
?>
<div class="container mt-5 fade-in-element">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="section-title text-center">Profil</h2>
            <div class="card card-modern">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                        <li class="list-group-item"><strong>Alamat:</strong> <?php echo nl2br(htmlspecialchars($user['address'] ?? 'Alamat belum diatur.')); ?></li>
                    </ul>
                    <div class="mt-4">
                        <a href="edit_profil.php" class="btn btn-primary">Edit Profil</a>
                        <a href="ganti_password.php" class="btn btn-outline-secondary">Ganti Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>