<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image_name = 'default.jpg';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = PROJECT_ROOT_PATH . "/uploads/products/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0755, true); }
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if(!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $errors[] = "Gagal mengupload gambar.";
        }
    }

    if(empty($errors)){
        $stmt = $conn->prepare("INSERT INTO products (name, description, category, price, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdis", $name, $desc, $category, $price, $stock, $image_name);
        
        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
include '_header_admin.php';
?>
<h1 class="h2">Tambah Produk Baru</h1>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
    <?php foreach($errors as $error): ?>
    <p><?php echo $error; ?></p>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<form action="tambah_produk.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Kaos">Kaos</option>
                <option value="Polo">Polo</option>
                <option value="Sweater">Sweater</option>
                <option value="Pakaian Wanita">Pakaian Wanita</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" class="form-control" id="price" name="price" step="500" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stock" name="stock" required>
        </div>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Gambar Produk</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="index.php" class="btn btn-secondary">Batal</a>
</form>
<?php include '_footer_admin.php'; ?>


<?php