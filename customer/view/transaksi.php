<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != "customer") {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';
$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, " SELECT pesanan.*, menu.nama_menu, menu.harga, pembayaran.status_validasi
                               FROM pesanan JOIN menu ON pesanan.id_menu = menu.id_menu
                               LEFT JOIN pembayaran ON pesanan.id_pesanan = pembayaran.id_pesanan
                               WHERE pesanan.id_user='$id_user'
                               ORDER BY pesanan.id_pesanan DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Saya</title>
    <link rel="stylesheet" href="css/transaction.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <header>
        <nav class="navbar fixed-top navbar-laman">
            <div class="container-fluid">
                <div class="logo" style="color: rgb(185,57,35);">🌶️ Riwayat</div>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Transaksi Saya</h3>
            <a href="index.php" class="btn btn-outline-primary">Kembali ke Beranda</a>

        </div>

        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <div class="col-md-6 mt-3">
                    <div class="card shadow p-3">

                        <h5><?= $row['nama_menu']; ?></h5>

                        <p>Jumlah : <?= $row['jumlah']; ?></p>

                        <p>Total :
                            Rp <?= number_format($row['harga'] * $row['jumlah']); ?>
                        </p>

                        <p>
                            Status Pesanan :
                            <b><?= $row['status']; ?></b>
                        </p>

                        <p>
                            Alamat :
                            <?= $row['alamat']; ?>
                        </p>

                        <?php
                        if ($row['status'] == "Menunggu") {
                        ?>
                            <button class="btn btn-warning w-100" disabled>
                                Menunggu Konfirmasi Admin
                            </button>

                        <?php
                        }
                        elseif ($row['status'] == "Ditolak") {
                        ?>

                            <button class="btn btn-danger w-100" disabled>
                                Pesanan Ditolak
                            </button>

                            <?php

                        }

                        elseif ($row['status'] == "Diterima") {
                            if (empty($row['status_validasi'])) {
                            ?>
                                <a href="pembayaran.php?id=<?= $row['id_pesanan']; ?>"
                                    class="btn btn-success w-100">Bayar Sekarang</a>
                            <?php
                            }

                            elseif ($row['status_validasi'] == "Belum Valid") {
                            ?>

                                <button class="btn btn-secondary w-100" disabled>
                                    Menunggu Validasi Pembayaran
                                </button>

                            <?php
                            }
                            elseif ($row['status_validasi'] == "Valid") {
                            ?>

                                <button class="btn btn-primary w-100" disabled>
                                    Pembayaran Berhasil
                                </button>
                            <?php
                            }
                        }

                        elseif ($row['status'] == "Selesai") {
                            ?>

                            <button class="btn btn-success w-100" disabled>
                                Pesanan Selesai</button>

                        <?php
                        }
                        ?>

                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <footer class="py-5 px-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-white">LOCATION</h5>

                    <ul class="list-unstyled">
                        <li>Umban Sari</li>
                        <li>Arifin Ahmad</li>
                        <li>Soekarno Hatta</li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h5 class="text-white">CONTACT</h5>

                    <ul class="list-unstyled">
                        <li>email@gmail.com</li>
                        <li>+62 822 2222 2222</li>
                    </ul>
                </div>
            </div>

            <hr>

            <div class="text-center text-white-50">
                &copy; Politeknik Caltex Riau, 2026
            </div>
        </div>
    </footer>
<script src="js/website.js"></script>
</body>
</html>