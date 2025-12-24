<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}
if (!isset($_GET['id'])) { header('Location: index.php'); exit(); }

$id = (int)$_GET['id'];
$errors = [];

$stmt_select = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
if($result->num_rows === 0) { header('Location: index.php'); exit(); }
$product = $result->fetch_assoc();
$stmt_select->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $desc = $conn->real_escape_string($_POST['description']);
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image_name = $product['image']; 

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = PROJECT_ROOT_PATH . "/uploads/products/";
        if($product['image'] != 'default.jpg' && file_exists($target_dir . $product['image'])){
            unlink($target_dir . $product['image']);
        }
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        if(!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $errors[] = "Gagal mengupload gambar baru.";
            $image_name = $product['image']; 
        }
    }
    
    if(empty($errors)){
        $stmt_update = $conn->prepare("UPDATE products SET name = ?, description = ?, category = ?, price = ?, stock = ?, image = ? WHERE id = ?");
        $stmt_update->bind_param("sssdisi", $name, $desc, $category, $price, $stock, $image_name, $id);
        if ($stmt_update->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Error: " . $stmt_update->error;
        }
        $stmt_update->close();
    }
}
include '_header_admin.php';
?>

<div class="pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Produk #<?php echo $id; ?></h1>
</div>

<?php if(!empty($errors)): ?>
<div class="alert alert-danger">
    <?php foreach($errors as $error): ?>
    <p class="mb-0"><?php echo $error; ?></p>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<form action="edit_produk.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
    <div class="row g-4">
        <!-- Kolom Form -->
        <div class="col-md-7 fade-in-element">
            <div class="card card-modern">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="Kaos" <?php if($product['category'] == 'Kaos') echo 'selected'; ?>>Kaos</option>
                                <option value="Polo" <?php if($product['category'] == 'Polo') echo 'selected'; ?>>Polo</option>
                                <option value="Sweater" <?php if($product['category'] == 'Sweater') echo 'selected'; ?>>Sweater</option>
                                <option value="Pakaian Wanita" <?php if($product['category'] == 'Pakaian Wanita') echo 'selected'; ?>>Pakaian Wanita</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" step="500" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stock" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
                        </div>
                    </div>
                     <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Kolom Gambar -->
        <div class="col-md-5 fade-in-element" style="animation-delay: 0.1s;">
            <div class="card card-modern">
                <div class="card-body">
                     <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk</label>
                        <p class="small text-muted">Ganti gambar produk (Opsional)</p>
                        <img src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($product['image']); ?>" id="image-preview" class="mb-2 img-thumbnail">
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');

    imageInput.addEventListener('change', function(event) {
        if (event.target.files && event.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    });
});
</script>

<?php include '_footer_admin.php'; ?>