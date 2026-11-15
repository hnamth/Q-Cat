<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$query = mysqli_query($conn, "
SELECT
    pembayaran.*,
    pesanan.id_pesanan,
    pesanan.jumlah,
    pesanan.tanggal_pesan,
    users.nama,
    menu.nama_menu
FROM pembayaran
JOIN pesanan ON pembayaran.id_pesanan = pesanan.id_pesanan
JOIN users ON pesanan.id_user = users.id_user
JOIN menu ON pesanan.id_menu = menu.id_menu
ORDER BY pembayaran.id_pembayaran DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Pembayaran</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin.css">

</head>
<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
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
                            Admin Panel
                        </span>
                    </span>
                </a>
            </div>
            <nav class="sidebar-nav">
                <a class="nav-link" href="index.php">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <a class="nav-link" href="menu.php">
                    <i class="bi bi-basket"></i>
                    <span>Menu</span>
                </a>

                <a class="nav-link" href="pesanan.php">
                    <i class="bi bi-cart"></i>
                    <span>Pesanan</span>
                </a>

                <a class="nav-link active" href="pembayaran.php">
                    <i class="bi bi-credit-card"></i>
                    <span>Pembayaran</span>
                </a>

                <a class="nav-link" href="../logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>

        <!-- MAIN -->
        <div class="admin-main">
            <nav class="navbar admin-navbar bg-white">
                <div class="container-fluid">
                    <h4>
                        Data Pembayaran Customer
                    </h4>
                </div>
            </nav>
            <main class="dashboard-content">
                <div class="container-fluid py-4">
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                Daftar Pembayaran
                            </h5>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-dark">

                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>Menu</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                        <th>Bukti</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_assoc($query)) {

                                    ?>

                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['nama']; ?></td>
                                            <td><?= $row['nama_menu']; ?></td>
                                            <td><?= $row['jumlah']; ?></td>
                                            <td><?= $row['tanggal_pesan']; ?></td>
                                            <td>
                                                <a
                                                    href="../uploads/<?= $row['bukti_transfer']; ?>"
                                                    target="_blank"
                                                    class="btn btn-info btn-sm">
                                                    <i class="bi bi-image"></i>
                                                    Lihat
                                                </a>
                                            </td>

                                            <td>
                                                <?php

                                                if ($row['status_validasi'] == "Belum Valid") {

                                                ?>
                                                    <span class="badge bg-warning text-dark">
                                                        Belum Valid
                                                    </span>
                                                <?php

                                                } else {

                                                ?>
                                                    <span class="badge bg-success">
                                                        Valid
                                                    </span>
                                                <?php

                                                }

                                                ?>
                                            </td>

                                            <td>
                                                <?php

                                                if ($row['status_validasi'] == "Belum Valid") {

                                                ?>

                                                    <a
                                                        href="validasi_pembayaran.php?id=<?= $row['id_pembayaran']; ?>"
                                                        class="btn btn-success btn-sm">

                                                        <i class="bi bi-check-circle"></i>

                                                        Validasi

                                                    </a>

                                                <?php

                                                } else {

                                                ?>

                                                    <button
                                                        class="btn btn-secondary btn-sm"
                                                        disabled>

                                                        Sudah Valid

                                                    </button>

                                                <?php

                                                }

                                                ?>
                                            </td>
                                        </tr>
                                    <?php

                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>