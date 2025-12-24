<?php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_FILES['payment_proof'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $order_id = (int)$_POST['order_id'];
    $user_id = $_SESSION['user_id'];
    $image = $_FILES['payment_proof'];

    if ($image['error'] !== 0) {
        $_SESSION['upload_error'] = "Terjadi kesalahan saat mengupload file.";
        header("Location: payment.php?order_id=$order_id");
        exit();
    }
    
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['upload_error'] = "Format file tidak diizinkan. Hanya JPG, JPEG, PNG.";
        header("Location: payment.php?order_id=$order_id");
        exit();
    }

    if ($image['size'] > 2097152) { // 2MB
        $_SESSION['upload_error'] = "Ukuran file terlalu besar. Maksimal 2MB.";
        header("Location: payment.php?order_id=$order_id");
        exit();
    }

    $new_filename = "proof_" . $order_id . "_" . time() . "." . $file_ext;
    $target_dir = "uploads/proofs/";
    $target_file = $target_dir . $new_filename;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    if (move_uploaded_file($image['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("UPDATE orders SET payment_proof = ?, status = 'verifying' WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $new_filename, $order_id, $user_id);
        if($stmt->execute()){
            header("Location: payment.php?order_id=$order_id");
            exit();
        } else {
             $_SESSION['upload_error'] = "Gagal menyimpan data ke database.";
        }
    } else {
        $_SESSION['upload_error'] = "Gagal memindahkan file yang diupload.";
    }
    
    header("Location: payment.php?order_id=$order_id");
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>
