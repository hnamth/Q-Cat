<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("location:../login.php");
    exit;
}

include "../config/koneksi.php";

$id = intval($_GET['id']);

$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT pesanan.*, users.role
FROM pesanan
LEFT JOIN users ON pesanan.id_user = users.id_user
WHERE pesanan.id_pesanan = '$id'
"));

if (!$pesanan) {
    echo "<script>alert('Data pesanan tidak ditemukan.'); window.location='pesanan.php';</script>";
    exit;
}

if (strtolower((string) $pesanan['role']) == "customer" && strtolower($pesanan['status']) != "selesai") {
    echo "<script>alert('Pesanan tidak dapat dihapus. Data hanya boleh dihapus jika status pesanan sudah Selesai.'); window.location='pesanan.php';</script>";
    exit;
}

mysqli_query($conn, "DELETE FROM pembayaran WHERE id_pesanan = '$id'");
mysqli_query($conn, "DELETE FROM pesanan WHERE id_pesanan = '$id'");

if (strtolower((string) $pesanan['role']) != "customer" && !empty($pesanan['id_user'])) {
    mysqli_query($conn, "DELETE FROM users WHERE id_user = '" . $pesanan['id_user'] . "'");
}

header("location:pesanan.php?pesan=Pesanan berhasil dihapus.");
exit;
