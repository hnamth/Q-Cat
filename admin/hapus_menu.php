<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

// Cek apakah menu masih digunakan pada pesanan yang aktif
$cek = mysqli_query($conn, "
SELECT *
FROM pesanan
WHERE id_menu = '$id'
AND status IN ('Menunggu','Diterima')
");

if (mysqli_num_rows($cek) > 0) {

    echo "<script>
    alert('Menu tidak dapat dihapus karena masih digunakan pada pesanan yang aktif.');
    window.location='menu.php';
    </script>";

    exit;
}

// Jika tidak digunakan, baru hapus menu
mysqli_query($conn, "
DELETE FROM menu
WHERE id_menu='$id'
");

echo "<script>
alert('Menu berhasil dihapus.');
window.location='menu.php';
</script>";
