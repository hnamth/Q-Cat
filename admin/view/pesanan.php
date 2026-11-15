<?php

session_start();


if (!isset($_SESSION['admin'])) {

    header("location:../login.php");

    exit;
}


require_once __DIR__ . '/../config/koneksi.php';



$query = mysqli_query($conn, "
SELECT
pesanan.*,
users.nama,
menu.nama_menu,
pembayaran.status_validasi

FROM pesanan

JOIN users
ON pesanan.id_user=users.id_user

JOIN menu
ON pesanan.id_menu=menu.id_menu

LEFT JOIN pembayaran
ON pembayaran.id_pesanan=pesanan.id_pesanan

ORDER BY pesanan.id_pesanan DESC
");


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>

        Pesanan Catering

    </title>
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
                        <span class="brand-title">

                            Q'Cat Catering

                        </span>
                        <span class="brand-subtitle">

                            Admin

                        </span>
                    </span>
                </a>
            </div>
            <nav class="sidebar-nav">
                <a class="nav-link" href="index.php">
                    <span class="nav-icon">

                        <i class="bi bi-speedometer2"></i>

                    </span>
                    <span class="nav-text">

                        Dashboard

                    </span>
                </a>
                <a class="nav-link" href="menu.php">
                    <span class="nav-icon">

                        <i class="bi bi-basket"></i>

                    </span>
                    <span class="nav-text">

                        Menu Catering

                    </span>
                </a>
                <a class="nav-link active"

                    href="pesanan.php">


                    <span class="nav-icon">

                        <i class="bi bi-cart"></i>

                    </span>


                    <span class="nav-text">

                        Pesanan

                    </span>


                </a>
                <a class="nav-link" href="../logout.php">
                    <span class="nav-icon">

                        <i class="bi bi-box-arrow-right"></i>

                    </span>
                    <span class="nav-text">

                        Logout

                    </span>
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </nav>
            <div class="sidebar-user">
                <img src="../assets/images/logo3.png" class="avatar-img avatar-md">

                <strong>

                    Admin Catering

                </strong>


                <small>

                    Jagonya Ayam
                </small>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid">
                    <h4>

                        Data Pesanan Catering

                    </h4>
                </div>
            </nav>
            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    <div class="page-heading">
                        <h1 class="h3">

                            Pesanan Masuk

                        </h1>
                        <p class="text-muted">

                            Kelola pesanan customer catering

                        </p>
                    </div>
                    <div class="panel p-4 mt-4">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>No</th>
                                <th>Customer</th>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Alamat</th>
                                <th>Pengiriman</th>
                                <th>Status</th>
                                <th>Aksi</th>

                            </tr>

                            <?php

                            $no = 1;

                            while ($data = mysqli_fetch_assoc($query)) {

                            ?>
                                <?php

                                // Cek apakah pembayaran sudah divalidasi
                                $cekBayar = mysqli_query($conn, "
                                SELECT *
                                FROM pembayaran
                                WHERE id_pesanan='" . $data['id_pesanan'] . "'
                                AND status_validasi='valid'
                                ");

                                $sudahValid = mysqli_num_rows($cekBayar) > 0;

                                ?>
                                <tr>
                                    <td>
                                        <?= $no++ ?>
                                    </td>
                                    <td>
                                        <?= $data['nama'] ?>
                                    </td>
                                    <td>
                                        <?= $data['nama_menu'] ?>
                                    </td>
                                    <td>
                                        <?= $data['jumlah'] ?>
                                    </td>

                                    <td>
                                        <?= $data['tanggal_pesan'] ?>
                                    </td>

                                    <td>
                                        <?= $data['alamat'] ?>
                                    </td>

                                    <td>
                                        <?= $data['pengiriman'] ?>
                                    </td>
                                    <td>

                                        <?php

                                        if ($data['status'] == "Menunggu") {

                                            echo "<span class='badge bg-warning'>Menunggu</span>";
                                        } elseif ($data['status'] == "Diterima") {

                                            echo "<span class='badge bg-primary'>Diterima</span>";
                                        } elseif ($data['status'] == "Selesai") {

                                            echo "<span class='badge bg-success'>Selesai</span>";
                                        } else {

                                            echo "<span class='badge bg-danger'>Ditolak</span>";
                                        }

                                        ?>

                                    </td>

                                    <td>

                                        <?php

                                        if ($data['status'] == "Menunggu") {

                                        ?>

                                            <a href="update_status.php?id=<?= $data['id_pesanan']; ?>&status=Diterima"
                                                class="btn btn-primary btn-sm">
                                                Terima
                                            </a>

                                            <a href="update_status.php?id=<?= $data['id_pesanan']; ?>&status=Ditolak"
                                                class="btn btn-danger btn-sm">
                                                Tolak
                                            </a>

                                            <?php

                                        } elseif ($data['status'] == "Diterima") {

                                            if (empty($data['status_validasi'])) {

                                            ?>

                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    Belum Upload Pembayaran
                                                </button>

                                            <?php

                                            } elseif ($data['status_validasi'] == "Belum Valid") {

                                            ?>

                                                <button class="btn btn-warning btn-sm" disabled>
                                                    Menunggu Validasi Pembayaran
                                                </button>

                                            <?php

                                            } elseif ($data['status_validasi'] == "Valid") {

                                            ?>

                                                <span class="badge bg-success">
                                                    Pembayaran Valid
                                                </span>

                                            <?php

                                            }
                                        } elseif ($data['status'] == "Selesai") {

                                            ?>

                                            <span class="badge bg-success">
                                                Pesanan Selesai
                                            </span>

                                        <?php

                                        } elseif ($data['status'] == "Ditolak") {

                                        ?>

                                            <span class="badge bg-danger">
                                                Pesanan Ditolak
                                            </span>

                                        <?php

                                        }

                                        ?>

                                    </td>

                                </tr>

                            <?php } ?>

                        </table>
                    </div>
                </div>
            </main>

            <footer class="admin-footer">
                <div class="container-fluid">
                    <span>

                        Sistem Catering Admin Dashboard

                    </span>
                </div>
            </footer>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/js/main.js"></script>


</body>

</html>
