<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Panel Admin</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="../logout.php">Keluar</a>
    </div>
  </div>
</header>
<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="position-sticky pt-3">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" href="index.php"><i class="bi bi-box-seam"></i> Produk</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="verifikasi_pembayaran.php"><i class="bi bi-patch-check"></i> Verifikasi Pembayaran
                <?php
                    $verif_count_result = $conn->query("SELECT COUNT(id) as count FROM orders WHERE status = 'verifying'");
                    if ($verif_count_result && $verif_count_result->num_rows > 0) {
                        $verif_count = $verif_count_result->fetch_assoc()['count'];
                        if($verif_count > 0){
                            echo "<span class='badge bg-danger ms-2'>$verif_count</span>";
                        }
                    }
                ?>
            </a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="pesanan.php"><i class="bi bi-file-earmark-text"></i> Pesanan</a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="pengguna.php"><i class="bi bi-people"></i> Pengguna</a>
          </li>
        </ul>
      </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">