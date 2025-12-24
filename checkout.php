<?php
include 'includes/header.php';
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Anda harus login untuk melanjutkan ke pembayaran.";
    header('Location: login.php');
    exit();
}
if (empty($_SESSION['cart'])) {
    header('Location: keranjang.php');
    exit();
}

$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();
$user_stmt->close();
?>
<div class="container mt-5">
<div class="row g-5">
    <div class="col-md-7 fade-in-element">
        <h2 class="section-title mb-4">Pesanan</h2>
        <div class="card card-modern">
            <div class="card-body">
                <form id="checkout-form" action="order_action.php" method="POST">
                    <h5 class="mb-3">Detail Pengiriman</h5>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['full_name'] ?? $_SESSION['username']); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Pengiriman</label>
                        <textarea id="address" name="address" class="form-control" rows="3" placeholder="Masukkan alamat lengkap Anda" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>
                     <div class="mb-3">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <select class="form-select" id="kecamatan" name="kecamatan" required>
                            <option value="">Pilih Kecamatan</option>
                            <option value="Cakung">Cakung</option>
                            <option value="Cipayung">Cipayung</option>
                            <option value="Ciracas">Ciracas</option>
                            <option value="Duren Sawit">Duren Sawit</option>
                            <option value="Jatinegara">Jatinegara</option>
                            <option value="Kramat Jati">Kramat Jati</option>
                            <option value="Makasar">Makasar</option>
                            <option value="Matraman">Matraman</option>
                            <option value="Pasar Rebo">Pasar Rebo</option>
                            <option value="Pulo Gadung">Pulo Gadung</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5 fade-in-element" style="animation-delay: 0.1s;">
        <h2 class="section-title mb-4">Ringkasan Pesanan</h2>
        <div class="card card-modern">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php
                    $total_price = 0;
                    $product_ids = array_keys($_SESSION['cart']);
                    if (!empty($product_ids)) {
                        $ids_string = implode(',', $product_ids);
                        $sql = "SELECT id, name, price FROM products WHERE id IN ($ids_string)";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while($product = $result->fetch_assoc()) {
                                $quantity = $_SESSION['cart'][$product['id']];
                                $subtotal = $product['price'] * $quantity;
                                $total_price += $subtotal;
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><?php echo htmlspecialchars($product['name']); ?> (x<?php echo $quantity; ?>)</span>
                        <span class="text-muted">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                    </li>
                    <?php
                            }
                        }
                    }
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-top pt-3">
                        <span>Subtotal</span>
                        <span id="subtotal-text">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span>Biaya Pengiriman</span>
                        <span id="shipping-cost-text">Rp 0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 fw-bold border-top pt-3">
                        <span>Total</span>
                        <span id="grand-total-text">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></span>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                 <button type="submit" form="checkout-form" name="place_order" class="btn btn-primary w-100">Buat Pesanan</button>
            </div>
        </div>
    </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kecamatanSelect = document.getElementById('kecamatan');
    const shippingCostText = document.getElementById('shipping-cost-text');
    const grandTotalText = document.getElementById('grand-total-text');
    const subtotal = <?php echo $total_price; ?>;

    const shippingCosts = {
        "Cakung": 15000,
        "Duren Sawit": 15000,
        "Pulo Gadung": 15000,
        "Jatinegara": 12000,
        "Kramat Jati": 12000,
        "Matraman": 12000,
        "Cipayung": 18000,
        "Ciracas": 18000,
        "Makasar": 18000,
        "Pasar Rebo": 18000
    };

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function updateTotals() {
        const selectedKecamatan = kecamatanSelect.value;
        let currentShippingCost = shippingCosts[selectedKecamatan] || 0;
        
        const grandTotal = subtotal + currentShippingCost;

        shippingCostText.textContent = formatRupiah(currentShippingCost);
        grandTotalText.textContent = formatRupiah(grandTotal);
    }
    
    kecamatanSelect.addEventListener('change', updateTotals);
    updateTotals();
});
</script>
<?php include 'includes/footer.php'; ?>


<?php