<?php
require_once 'includes/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: " . BASE_URL . "admin/index.php");
            } else {
                header("Location: " . BASE_URL . "index.php");
            }
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username atau Email tidak ditemukan.";
    }
    $stmt->close();
}
include 'includes/header.php';
?>
<div class="container mt-5">
<div class="row justify-content-center">
    <div class="col-md-5 fade-in-element">
        <div class="card card-modern">
            <div class="card-header text-center">
                <h3>Masuk</h3>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username atau Email</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>
            </div>
             <div class="card-footer text-center">
                Tidak Punya Akun? <a href="register.php" class="text-primary-emphasis">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'includes/footer.php'; ?>
