<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'] ?? 'deactivate'; 

    $new_status = ($action === 'activate') ? 1 : 0;

    $stmt = $conn->prepare("UPDATE products SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $id);
    $stmt->execute();
    $stmt->close();
}

header('Location: index.php');
exit();
?>