<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("Data tidak ditemukan.");
}

$id = intval($_GET['id']);
$status = $_GET['status'];

// Ambil status pesanan sekarang
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT *
FROM pesanan
WHERE id_pesanan='$id'
"));

if (!$pesanan) {
    die("Pesanan tidak ditemukan.");
}

$statusSekarang = $pesanan['status'];

// ==============================
// MENUNGGU -> DITERIMA / DITOLAK
// ==============================
if ($status == "Diterima") {

    if ($statusSekarang != "Menunggu") {
        echo "<script>
        alert('Status tidak dapat diubah.');
        window.location='pesanan.php';
        </script>";
        exit;
    }
}

// ==============================
// DITERIMA -> SELESAI
// ==============================
if ($status == "Selesai") {

    if ($statusSekarang != "Diterima") {

        echo "<script>
        alert('Pesanan belum diterima.');
        window.location='pesanan.php';
        </script>";

        exit;
    }

    // Cek pembayaran sudah valid?
    $cekBayar = mysqli_query($conn, "
    SELECT *
    FROM pembayaran
    WHERE id_pesanan='$id'
    AND status_validasi='Valid'
    ");

    if (mysqli_num_rows($cekBayar) == 0) {

        echo "<script>
        alert('Pembayaran belum divalidasi.');
        window.location='pesanan.php';
        </script>";

        exit;
    }
}

// ==============================
// UPDATE STATUS
// ==============================

mysqli_query($conn, "
UPDATE pesanan
SET status='$status'
WHERE id_pesanan='$id'
");

echo "<script>
alert('Status berhasil diperbarui.');
window.location='pesanan.php';
</script>";

