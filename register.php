<?php
require_once 'includes/db.php';
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username)) $errors[] = "Username wajib diisi.";
    if (empty($email)) $errors[] = "Email wajib diisi.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format email tidak valid.";
    if (empty($password)) $errors[] = "Password wajib diisi.";
    if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter.";
    if ($password !== $confirm_password) $errors[] = "Konfirmasi password tidak cocok.";

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Username atau email sudah terdaftar.";
    }
    $stmt->close();

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        if ($stmt->execute()) {
            $success_message = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
        } else {
            $errors[] = "Terjadi kesalahan. Coba lagi.";
        }
        $stmt->close();
    }
}
include 'includes/header.php';
?>
<div class="container mt-5">
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-modern">
            <div class="card-header text-center">
                <h3>Daftar</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php else: ?>
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Register</button>
                </form>
                <?php endif; ?>
            </div>
            <div class="card-footer text-center">
                Sudah Punya Akun? <a href="login.php" class="text-dark">Masuk</a>
            </div>
        </div>
    </div>
</div>
</div>
<?php include 'includes/footer.php'; ?>