<?php include 'includes/header.php'; ?>
<div class="container mt-5">
<h2 class="section-title text-center mb-4"><i class="bi bi-bag-check"></i> Keranjang</h2>

<?php if (!empty($_SESSION['cart'])): ?>
<form action="keranjang_action.php" method="POST">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 50%;">Produk</th>
                    <th class="text-center">Harga</th>
                    <th class="text-center" style="width: 10%;">Kuantitas</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                $product_ids = array_keys($_SESSION['cart']);
                if (!empty($product_ids)) {
                    $ids_string = implode(',', $product_ids);
                    $sql = "SELECT * FROM products WHERE id IN ($ids_string)";
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while ($product = $result->fetch_assoc()) {
                            $quantity = $_SESSION['cart'][$product['id']];
                            $subtotal = $product['price'] * $quantity;
                            $total_price += $subtotal;
                ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($product['image']); ?>" width="80" class="me-3" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($product['name']); ?></h6>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                    <td class="text-center">
                        <input type="number" name="quantities[<?php echo $product['id']; ?>]" value="<?php echo $quantity; ?>" min="1" max="<?php echo $product['stock']; ?>" class="form-control form-control-sm text-center">
                    </td>
                    <td class="text-end fw-bold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                    <td class="text-center">
                        <a href="keranjang_action.php?remove=<?php echo $product['id']; ?>" class="btn btn-outline-danger btn-sm" title="Hapus item">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </td>
                </tr>
                <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
        <button type="submit" name="update_cart" class="btn btn-outline-secondary mb-2"><i class="bi bi-arrow-repeat"></i> Perbarui Total</button>
        <div class="text-end">
            <h3>Total: <span class="fw-bold">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span></h3>
        </div>
    </div>
</form>
<div class="text-end mt-4">
    <a href="produk.php" class="btn btn-outline-dark">Lanjut Belanja</a>
    <a href="checkout.php" class="btn btn-dark">Beli <i class="bi bi-arrow-right"></i></a>
</div>

<?php else: ?>
    <div class="alert alert-secondary text-center" role="alert">
        <h4 class="alert-heading">Keranjang Belanjamu Kosong!</h4>
        <p>Sepertinya kamu belum menambahkan apa-apa ke dalam keranjang.</p>
        <hr>
        <a href="produk.php" class="btn btn-dark">Mulai Berbelanja</a>
    </div>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
