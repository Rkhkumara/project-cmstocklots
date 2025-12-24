<?php
include 'includes/header.php'; 
?>

<div class="container mt-5">
    <div class="text-center fade-in-element">
        <h1 class="section-title">Koleksi Kami</h1>
        <p class="lead col-md-8 mx-auto">Jelajahi semua produk pilihan kami yang dirancang untuk menyempurnakan gaya Anda.</p>
    </div>
    
    <div class="filter-bar my-4 p-3 rounded fade-in-element">
        <form action="produk.php#products-grid" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="Polo" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Polo') ? 'selected' : ''; ?>>Polo</option>
                    <option value="Sweater" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Sweater') ? 'selected' : ''; ?>>Sweater</option>
                    <option value="Kaos" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Kaos') ? 'selected' : ''; ?>>Kaos</option>
                    <option value="Pakaian Wanita" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Pakaian Wanita') ? 'selected' : ''; ?>>Pakaian Wanita</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="created_at_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'created_at_desc') ? 'selected' : ''; ?>>Terbaru</option>
                    <option value="price_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : ''; ?>>Harga: Rendah ke Tinggi</option>
                    <option value="price_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : ''; ?>>Harga: Tinggi ke Rendah</option>
                    <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Nama: A-Z</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="products-grid">
        <?php
        $search_term = isset($_GET['search']) ? $_GET['search'] : '';
        $sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'created_at_desc';
        $category_filter = isset($_GET['category']) ? $_GET['category'] : '';

        $sql = "SELECT * FROM products WHERE stock > 0 AND is_active = 1";
        $params = [];
        $types = '';

        if (!empty($search_term)) {
            $sql .= " AND name LIKE ?";
            $search_like = "%" . $search_term . "%";
            $params[] = &$search_like;
            $types .= 's';
        }

        if (!empty($category_filter)) {
            $sql .= " AND category = ?";
            $params[] = &$category_filter;
            $types .= 's';
        }

        switch ($sort_option) {
            case 'price_asc':
                $sql .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY price DESC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY name ASC";
                break;
            default:
                $sql .= " ORDER BY created_at DESC";
                break;
        }

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
        ?>
        <div class="col fade-in-element">
            <div class="card h-100 card-modern">
                <div class="card-img-container">
                    <img src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null;this.src='https://placehold.co/600x400/EEE/31343C?text=Image+Not+Found';">
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <p class="card-text fw-bold">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                </div>
                <div class="card-footer">
                    <form action="keranjang_action.php" method="POST" class="d-flex">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" name="add_to_cart" class="btn btn-primary w-100"><i class="bi bi-bag-plus"></i> Tambah</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
            }
        } else {
            echo "<div class='col-12'><p class='alert alert-secondary text-center'>Produk yang Anda cari tidak ditemukan.</p></div>";
        }
        $stmt->close();
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>


<?php