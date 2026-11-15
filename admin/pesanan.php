<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("location:../login.php");
    exit;
}

include "../config/koneksi.php";

$query = mysqli_query($conn, "
SELECT
    pesanan.*,
    users.nama,
    users.role,
    menu.nama_menu,
    pembayaran.status_validasi
FROM pesanan
LEFT JOIN users ON pesanan.id_user = users.id_user
JOIN menu ON pesanan.id_menu = menu.id_menu
LEFT JOIN pembayaran ON pembayaran.id_pesanan = pesanan.id_pesanan
ORDER BY pesanan.id_pesanan DESC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pesanan Catering</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="admin-shell">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a class="brand-mark">
                    <span class="brand-icon">
                        <i class="bi bi-shop"></i>
                    </span>
                    <span class="brand-copy">
                        <span class="brand-title">Q'Cat Catering</span>
                        <span class="brand-subtitle">Admin</span>
                    </span>
                </a>
            </div>
            <nav class="sidebar-nav">
                <a class="nav-link" href="index.php">
                    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a class="nav-link" href="menu.php">
                    <span class="nav-icon"><i class="bi bi-basket"></i></span>
                    <span class="nav-text">Menu Catering</span>
                </a>
                <a class="nav-link active" href="pesanan.php">
                    <span class="nav-icon"><i class="bi bi-cart"></i></span>
                    <span class="nav-text">Pesanan</span>
                </a>
                <a class="nav-link" href="pembayaran.php">
                    <span class="nav-icon"><i class="bi bi-credit-card"></i></span>
                    <span class="nav-text">Pembayaran</span>
                </a>
                <a class="nav-link" href="../logout.php">
                    <span class="nav-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="nav-text">Logout</span>
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </nav>
            <div class="sidebar-user">
                <img src="../assets/images/logo3.png" class="avatar-img avatar-md">
                <strong>Admin Catering</strong>
                <small>Jagonya Ayam</small>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid">
                    <h4>Data Pesanan Catering</h4>
                </div>
            </nav>
            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    <div class="page-heading d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <h1 class="h3">Pesanan Masuk</h1>
                            <p class="text-muted mb-0">Kelola pesanan customer dan pesanan manual.</p>
                        </div>
                        <a href="tambah_pesanan.php" class="btn btn-primary">
                            <i class="bi bi-plus"></i>
                            Tambah Pesanan
                        </a>
                    </div>

                    <?php if (isset($_GET['pesan'])) { ?>
                        <div class="alert alert-info mt-4">
                            <?= htmlspecialchars($_GET['pesan']); ?>
                        </div>
                    <?php } ?>

                    <div class="panel p-4 mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <tr>
                                    <th>No</th>
                                    <th>Customer</th>
                                    <th>Sumber</th>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Alamat</th>
                                    <th>Pengiriman</th>
                                    <th>Status</th>
                                    <th>Aksi Status</th>
                                    <th>RUD</th>
                                </tr>

                                <?php
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $isManual = strtolower((string) $data['role']) != "customer";
                                    $status = strtolower($data['status']);
                                    $statusValidasi = strtolower((string) $data['status_validasi']);
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if ($isManual) { ?>
                                                <?= htmlspecialchars($data['nama'] ?: 'Tidak terdaftar') ?>
                                            <?php } else { ?>
                                                <?= htmlspecialchars($data['nama']) ?>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($isManual) { ?>
                                                <span class="badge bg-info">Manual Admin</span>
                                            <?php } else { ?>
                                                <span class="badge bg-secondary">Akun Customer</span>
                                            <?php } ?>
                                        </td>
                                        <td><?= htmlspecialchars($data['nama_menu']) ?></td>
                                        <td><?= htmlspecialchars($data['jumlah']) ?></td>
                                        <td><?= htmlspecialchars($data['tanggal_pesan']) ?></td>
                                        <td><?= htmlspecialchars($data['alamat']) ?></td>
                                        <td><?= htmlspecialchars($data['pengiriman']) ?></td>
                                        <td>
                                            <?php
                                            if ($status == "menunggu") {
                                                echo "<span class='badge bg-warning'>Menunggu</span>";
                                            } elseif ($status == "diterima") {
                                                echo "<span class='badge bg-primary'>Diterima</span>";
                                            } elseif ($status == "selesai") {
                                                echo "<span class='badge bg-success'>Selesai</span>";
                                            } else {
                                                echo "<span class='badge bg-danger'>Ditolak</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($status == "menunggu") { ?>
                                                <a href="update_status.php?id=<?= $data['id_pesanan']; ?>&status=Diterima"
                                                    class="btn btn-primary btn-sm">
                                                    Terima
                                                </a>

                                                <a href="update_status.php?id=<?= $data['id_pesanan']; ?>&status=Ditolak"
                                                    class="btn btn-danger btn-sm">
                                                    Tolak
                                                </a>
                                            <?php } elseif ($status == "diterima") { ?>
                                                <?php if ($isManual) { ?>
                                                    <a href="update_status.php?id=<?= $data['id_pesanan']; ?>&status=Selesai"
                                                        class="btn btn-success btn-sm">
                                                        Selesai
                                                    </a>
                                                <?php } else { ?>
                                                    <?php if ($statusValidasi == "") { ?>
                                                        <button class="btn btn-secondary btn-sm" disabled>
                                                            Belum Upload Pembayaran
                                                        </button>
                                                    <?php } elseif ($statusValidasi == "belum valid") { ?>
                                                        <button class="btn btn-warning btn-sm" disabled>
                                                            Menunggu Validasi Pembayaran
                                                        </button>
                                                    <?php } elseif ($statusValidasi == "valid") { ?>
                                                        <span class="badge bg-success">Pembayaran Valid</span>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } elseif ($status == "selesai") { ?>
                                                <span class="badge bg-success">Pesanan Selesai</span>
                                            <?php } elseif ($status == "ditolak") { ?>
                                                <span class="badge bg-danger">Pesanan Ditolak</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($isManual) { ?>
                                                <a href="edit_pesanan.php?id=<?= $data['id_pesanan']; ?>"
                                                    class="btn btn-warning btn-sm mb-1">
                                                    Edit
                                                </a>
                                            <?php } else { ?>
                                                <button class="btn btn-secondary btn-sm mb-1" disabled
                                                    title="Pesanan dari akun customer terdaftar tidak dapat diedit manual.">
                                                    Edit Terkunci
                                                </button>
                                            <?php } ?>

                                            <?php if ($isManual || $status == "selesai") { ?>
                                                <a href="hapus_pesanan.php?id=<?= $data['id_pesanan']; ?>"
                                                    class="btn btn-danger btn-sm mb-1"
                                                    onclick="return confirm('Hapus pesanan ini?')">
                                                    Hapus
                                                </a>
                                            <?php } else { ?>
                                                <button class="btn btn-outline-danger btn-sm mb-1" disabled
                                                    title="Pesanan hanya dapat dihapus jika status sudah Selesai.">
                                                    Hapus Terkunci
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="admin-footer">
                <div class="container-fluid">
                    <span>Sistem Catering Admin Dashboard</span>
                </div>
            </footer>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>
