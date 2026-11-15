<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

// Total Menu
$q1 = mysqli_query($conn, "SELECT COUNT(*) total FROM menu");
$total_menu = mysqli_fetch_assoc($q1)['total'];

// Total Customer
$q2 = mysqli_query($conn, "SELECT COUNT(*) total FROM users WHERE role='customer'");
$total_customer = mysqli_fetch_assoc($q2)['total'];

// Total Pesanan
$q3 = mysqli_query($conn, "
SELECT COUNT(*) total
FROM pesanan
");
$total_pesanan = mysqli_fetch_assoc($q3)['total'];

// Menunggu
$q4 = mysqli_query($conn, "
SELECT COUNT(*) total
FROM pesanan
WHERE status='Menunggu'
");
$menunggu = mysqli_fetch_assoc($q4)['total'];

// Diterima
$q5 = mysqli_query($conn, "
SELECT COUNT(*) total
FROM pesanan
WHERE status='Diterima'
");
$diterima = mysqli_fetch_assoc($q5)['total'];

// Selesai
$q6 = mysqli_query($conn, "
SELECT COUNT(*) total
FROM pesanan
WHERE status='Selesai'
");
$selesai = mysqli_fetch_assoc($q6)['total'];

// Ditolak
$q7 = mysqli_query($conn, "
SELECT COUNT(*) total
FROM pesanan
WHERE status='Ditolak'
");
$ditolak = mysqli_fetch_assoc($q7)['total'];

// Pembayaran Valid
$q8 = mysqli_query($conn, "SELECT COUNT(*) total FROM pembayaran WHERE status_validasi='Valid'");
$valid = mysqli_fetch_assoc($q8)['total'];

// Pembayaran Belum Valid
$q9 = mysqli_query($conn, "SELECT COUNT(*) total FROM pembayaran WHERE status_validasi='Belum Valid'");
$belum = mysqli_fetch_assoc($q9)['total'];

// Data transaksi selesai
$q10 = mysqli_query($conn, "
SELECT
users.nama,
menu.nama_menu,
pesanan.jumlah,
menu.harga,
(pesanan.jumlah * menu.harga) AS total_harga,
pesanan.tanggal_pesan
FROM pesanan
JOIN users ON pesanan.id_user = users.id_user
JOIN menu ON pesanan.id_menu = menu.id_menu
WHERE pesanan.status='Selesai'
ORDER BY pesanan.tanggal_pesan DESC
");

// Total pendapatan
$q11 = mysqli_query($conn, "
SELECT
SUM(pesanan.jumlah * menu.harga) AS pendapatan
FROM pesanan
JOIN menu ON pesanan.id_menu = menu.id_menu
WHERE pesanan.status='Selesai'
");

$pendapatan = mysqli_fetch_assoc($q11)['pendapatan'];

if ($pendapatan == null) {
    $pendapatan = 0;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Q'Cat Catering</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <style>
        body {

            background: #f5f6fa;

        }

        .card-report {

            border: none;

            border-radius: 15px;

            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);

        }

        .table td {

            font-size: 16px;

        }

        @media print {

            .btn {
                display: none;
            }

            body {
                background: white;
            }

        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="card card-report">
            <div class="card-body">
                <h2 class="text-center mb-4">

                    ðŸ½ï¸ Laporan Sistem Q'Cat Catering

                </h2>
                <p class="text-center text-muted">
                    Tanggal Cetak :
                    <?= date("d-m-Y H:i"); ?>
                </p>
                <hr>
                <table class="table table-bordered">

                    <tr>

                        <th width="60%">Total Menu</th>

                        <td><?= $total_menu ?></td>

                    </tr>

                    <tr>

                        <th>Total Customer</th>

                        <td><?= $total_customer ?></td>

                    </tr>

                    <tr>

                        <th>Total Pesanan</th>

                        <td><?= $total_pesanan ?></td>

                    </tr>

                    <tr>

                        <th>Pesanan Menunggu</th>

                        <td><?= $menunggu ?></td>

                    </tr>

                    <tr>

                        <th>Pesanan Diterima</th>

                        <td><?= $diterima ?></td>

                    </tr>

                    <tr>

                        <th>Pesanan Selesai</th>

                        <td><?= $selesai ?></td>

                    </tr>

                    <tr>

                        <th>Pesanan Ditolak</th>

                        <td><?= $ditolak ?></td>

                    </tr>

                    <tr>

                        <th>Pembayaran Valid</th>

                        <td><?= $valid ?></td>

                    </tr>

                    <tr>

                        <th>Pembayaran Belum Valid</th>

                        <td><?= $belum ?></td>

                    </tr>

                </table>
                <hr>

                <h4 class="text-success mb-4">

                    Total Pendapatan :
                    <strong>
                        Rp <?= number_format($pendapatan, 0, ',', '.') ?>
                    </strong>

                </h4>

                <h4 class="mt-4 mb-3">

                    Daftar Transaksi Selesai

                </h4>

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">

                        <tr>

                            <th>No</th>
                            <th>Customer</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                            <th>Tanggal</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($r = mysqli_fetch_assoc($q10)) {
                        

                        ?>

                            <tr>
                                <td><?= $no++; ?></td>

                                <td><?= $r['nama']; ?></td>

                                <td><?= $r['nama_menu']; ?></td>

                                <td><?= $r['jumlah']; ?></td>

                                <td>Rp <?= number_format($r['harga'], 0, ',', '.'); ?></td>

                                <td>

                                    Rp <?= number_format($r['total_harga'], 0, ',', '.'); ?>

                                </td>

                                <td><?= $r['tanggal_pesan']; ?></td>

                            </tr>

                        <?php } ?>

                        

                    </tbody>

                </table>

                <div class="text-center mt-4">
                    <button onclick="window.print()" class="btn btn-success">

                        <i class="bi bi-printer"></i>

                        Cetak Laporan

                    </button>

                    <a href="index.php" class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
