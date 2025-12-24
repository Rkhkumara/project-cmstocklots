<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CM Stocklots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">CM Stocklots</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>about.php">Tentang Kami</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>contact.php">Kontak</a></li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo BASE_URL; ?>keranjang.php">
            <i class="bi bi-bag"></i> 
            <span class="badge rounded-pill cart-badge">
              <?php 
                $cart_count = 0;
                if(isset($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $qty) {
                        $cart_count += $qty;
                    }
                }
                echo $cart_count; 
              ?>
            </span>
          </a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>akun.php">Profil</a></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>riwayat_pesanan.php">Riwayat Pesanan</a></li>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                  <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin/index.php">Dashboard Admin</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout.php">Keluar</a></li>
              </ul>
            </li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Masuk</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="main-content">