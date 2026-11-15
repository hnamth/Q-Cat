<?php
session_start();

if (!isset($_SESSION['admin'])) {
  header("Location: ../login.php");
  exit;
}

require_once __DIR__ . '/../config/koneksi.php';

/* ===========================
   DASHBOARD STATISTIK
=========================== */

// Total Menu
$qMenu = mysqli_query($conn, "SELECT COUNT(*) AS total FROM menu");
$total_menu = mysqli_fetch_assoc($qMenu)['total'];

// Total Pesanan
$qPesanan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan");
$total_pesanan = mysqli_fetch_assoc($qPesanan)['total'];

// Total Customer
$qCustomer = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='customer'");
$total_customer = mysqli_fetch_assoc($qCustomer)['total'];

// Pembayaran Pending
$qPending = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembayaran WHERE status_validasi='belum valid'");
$total_pending = mysqli_fetch_assoc($qPending)['total'];

// Total Pendapatan
$qPendapatan = mysqli_query($conn, "
SELECT
SUM(menu.harga * pesanan.jumlah) AS total
FROM pesanan
JOIN menu ON menu.id_menu = pesanan.id_menu
WHERE pesanan.status='Selesai'
");

$dataPendapatan = mysqli_fetch_assoc($qPendapatan);

$total_pendapatan = $dataPendapatan['total'];

if ($total_pendapatan == null) {
  $total_pendapatan = 0;
}

// Pesanan Terbaru
$qRecent = mysqli_query($conn, "
SELECT
users.nama,
menu.nama_menu,
pesanan.status,
pesanan.tanggal_pesan

FROM pesanan

JOIN users
ON users.id_user = pesanan.id_user

JOIN menu
ON menu.id_menu = pesanan.id_menu

ORDER BY pesanan.id_pesanan DESC

LIMIT 5
");

// Pembayaran Pending
$qBayar = mysqli_query($conn, "
SELECT
pembayaran.id_pembayaran,
users.nama,
menu.nama_menu

FROM pembayaran

JOIN pesanan
ON pembayaran.id_pesanan = pesanan.id_pesanan

JOIN users
ON users.id_user = pesanan.id_user

JOIN menu
ON menu.id_menu = pesanan.id_menu

WHERE pembayaran.status_validasi='belum valid'

LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Dashboard Admin | Q'Cat Catering</title>

  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">
      <div class="sidebar-header">
        <a class="brand-mark">

          <span class="brand-icon">
            <i class="bi bi-shop"></i>
          </span>

          <span class="brand-copy">

            <span class="brand-title">
              Q'Cat Catering
            </span>

            <span class="brand-subtitle">
              Administrator
            </span>
          </span>
        </a>

      </div>

      <nav class="sidebar-nav">

        <a class="nav-link active" href="index.php">
          <span class="nav-icon">
            <i class="bi bi-speedometer2"></i>
          </span>
          <span class="nav-text">Dashboard</span>
        </a>

        <a class="nav-link" href="menu.php">
          <span class="nav-icon">
            <i class="bi bi-egg-fried"></i>
          </span>
          <span class="nav-text">Kelola Menu</span>
        </a>

        <a class="nav-link" href="pesanan.php">
          <span class="nav-icon">
            <i class="bi bi-bag-check"></i>
          </span>
          <span class="nav-text">Pesanan</span>
        </a>

        <a class="nav-link" href="pembayaran.php">
          <span class="nav-icon">
            <i class="bi bi-credit-card"></i>
          </span>
          <span class="nav-text">Pembayaran</span>
        </a>

        <a class="nav-link" href="laporan.php">
          <span class="nav-icon">
            <i class="bi bi-file-earmark-text"></i>
          </span>
          <span class="nav-text">Laporan</span>
        </a>

        <a class="nav-link" href="../logout.php">
          <span class="nav-icon">
            <i class="bi bi-box-arrow-right"></i>
          </span>
          <span class="nav-text">Logout</span>
        </a>

      </nav>

      <div class="sidebar-user">
        <img src="../assets/images/logo3.png" class="avatar-img avatar-md">

        <strong>Admin Catering</strong>

        <small>Q'Cat Catering</small>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-4">
          <button
            class="sidebar-toggle"
            type="button"
            data-sidebar-toggle>

            <span></span>
            <span></span>
            <span></span>

          </button>

          <h5 class="ms-3 fw-bold">

            Dashboard Admin

          </h5>

          <div class="ms-auto">

            <div class="dropdown">

              <button
                class="profile-button dropdown-toggle"
                data-bs-toggle="dropdown">

                <img src="../assets/images/logo3.png" class="avatar-img avatar-md">

                Admin

              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>

                  <a
                    class="dropdown-item"
                    href="../logout.php">

                    Logout

                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
      <main class="dashboard-content">
        <div class="container-fluid px-4 py-4">
          <div class="page-heading">
            <h2 class="fw-bold">

              Dashboard Administrator

            </h2>
            <p class="text-muted">

              Selamat datang di Sistem Informasi Q'Cat Catering. Kelola menu, pesanan, pembayaran, dan laporan dari satu dashboard.

            </p>
          </div>

          <!-- CARD STATISTIK -->
          <div class="row g-4 mt-2">
            <div class="col-lg-3 col-md-6">
              <div class="panel p-4 text-center shadow-sm">
                <i class="bi bi-egg-fried fs-1 text-warning"></i>
                <h5 class="mt-3">Total Menu</h5>
                <h2 class="fw-bold"><?= $total_menu ?></h2>
                <small class="text-muted">
                  Menu tersedia
                </small>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="panel p-4 text-center shadow-sm">
                <i class="bi bi-bag-check fs-1 text-primary"></i>
                <h5 class="mt-3">Total Pesanan</h5>
                <h2 class="fw-bold"><?= $total_pesanan ?></h2>
                <small class="text-muted">

                  Semua Pesanan

                </small>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="panel p-4 text-center shadow-sm">
                <i class="bi bi-people-fill fs-1 text-success"></i>
                <h5 class="mt-3">Customer</h5>
                <h2 class="fw-bold"><?= $total_customer ?></h2>
                <small class="text-muted">

                  Pengguna Customer

                </small>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <div class="panel p-4 text-center shadow-sm">
                <i class="bi bi-credit-card fs-1 text-danger"></i>
                <h5 class="mt-3">Pending</h5>
                <h2 class="fw-bold"><?= $total_pending ?></h2>
                <small class="text-muted">

                  Menunggu Validasi

                </small>
              </div>
            </div>
          </div>

          <div class="panel mt-4">
            <h4 class="mb-4">
              <i class="bi bi-lightning-charge-fill text-danger"></i>

              Quick Menu

            </h4>
            <div class="row text-center">
              <div class="col-md-3">
                <a href="menu.php" class="btn btn-danger w-100 p-3">
                  <i class="bi bi-egg-fried fs-3"></i>
                  <br>

                  Kelola Menu

                </a>
              </div>
              <div class="col-md-3">
                <a href="pesanan.php" class="btn btn-primary w-100 p-3">
                  <i class="bi bi-cart-check fs-3"></i>
                  <br>

                  Pesanan

                </a>
              </div>
              <div class="col-md-3">
                <a href="pembayaran.php" class="btn btn-success w-100 p-3">
                  <i class="bi bi-credit-card fs-3"></i>
                  <br>

                  Pembayaran

                </a>
              </div>
              <div class="col-md-3">
                <a href="laporan.php" class="btn btn-dark w-100 p-3">

                  <i class="bi bi-file-earmark-text fs-3"></i>
                  <br>

                  Laporan

                </a>
              </div>
            </div>
          </div>

          <!-- PESANAN TERBARU -->
          <div class="panel mt-5 p-4">
            <h4 class="mb-4">
              <i class="bi bi-clock-history"></i>

              Pesanan Terbaru

            </h4>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">

                  <tr>
                    <th>Customer</th>
                    <th>Menu</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                  </tr>
                </thead>

                <tbody>
                  <?php while ($r = mysqli_fetch_assoc($qRecent)) { ?>
                    <tr>
                      <td><?= $r['nama']; ?></td>
                      <td><?= $r['nama_menu']; ?></td>
                      <td><?= $r['tanggal_pesan']; ?></td>

                      <td>

                        <?php

                        switch ($r['status']) {

                          case "menunggu":

                            echo "<span class='badge bg-warning'>Menunggu</span>";

                            break;

                          case "Diterima":

                            echo "<span class='badge bg-primary'>Diterima</span>";

                            break;

                          case "Selesai":

                            echo "<span class='badge bg-success'>Selesai</span>";

                            break;

                          default:

                            echo "<span class='badge bg-danger'>Ditolak</span>";
                        }

                        ?>

                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>

          <!-- PEMBAYARAN PENDING -->
          <div class="panel mt-4 p-4">
            <h4 class="mb-4">
              <i class="bi bi-credit-card"></i>

              <div class="col-lg-3 col-md-6">
                <div class="panel p-4 text-center shadow-sm">

                  <i class="bi bi-cash-stack fs-1 text-success"></i>

                  <h5 class="mt-3">
                    Pendapatan
                  </h5>

                  <h4 class="fw-bold text-success">

                    Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>

                  </h4>

                  <small class="text-muted">

                    Total Penjualan

                  </small>

                </div>
              </div>

              Pembayaran Belum Divalidasi

            </h4>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Customer</th>
                    <th>Menu</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>

                  <?php while ($b = mysqli_fetch_assoc($qBayar)) { ?>
                    <tr>
                      <td><?= $b['nama']; ?></td>
                      <td><?= $b['nama_menu']; ?></td>
                      <td>
                        <a href="pembayaran.php"
                          class="btn btn-success btn-sm">
                          <i class="bi bi-eye"></i>

                          Lihat

                        </a>
                      </td>
                    </tr>

                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
      <footer class="admin-footer">
        <div class="container-fluid">
          <span>

            Â© <?= date("Y"); ?> Q'Cat Catering Admin Dashboard

          </span>
        </div>
      </footer>
    </div>

  </div>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>

  <script src="../assets/js/main.js"></script>

</body>

</html>
