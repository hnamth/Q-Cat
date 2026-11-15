<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_GET['id'])) {
    die("Data tidak ditemukan.");
}

$id_pembayaran = intval($_GET['id']);

// Ambil data pembayaran + status pesanan
$query = mysqli_query($conn, "
SELECT pembayaran.id_pesanan,
       pembayaran.status_validasi,
       pesanan.status
FROM pembayaran
JOIN pesanan
ON pembayaran.id_pesanan = pesanan.id_pesanan
WHERE pembayaran.id_pembayaran='$id_pembayaran'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {

    die("Data pembayaran tidak ditemukan.");
}

$id_pesanan = $data['id_pesanan'];

// Jangan validasi dua kali
if ($data['status_validasi'] == "Valid") {

    echo "<script>
    alert('Pembayaran sudah divalidasi.');
    window.location='pembayaran.php';
    </script>";

    exit;
}

// Pesanan harus sudah diterima
if ($data['status'] != "Diterima") {

    echo "<script>
    alert('Pesanan belum diterima sehingga pembayaran tidak dapat divalidasi.');
    window.location='pembayaran.php';
    </script>";

    exit;
}

// Validasi pembayaran
$update = mysqli_query($conn,"
UPDATE pembayaran
SET status_validasi='Valid'
WHERE id_pembayaran='$id_pembayaran'
");

if($update){

    echo "<script>
    alert('Pembayaran berhasil divalidasi');
    window.location='pembayaran.php';
    </script>";

}else{

    die(mysqli_error($conn));

};

// Otomatis ubah status pesanan
mysqli_query($conn,"
UPDATE pesanan
SET status='Selesai'
WHERE id_pesanan='$id_pesanan'
");

echo "<script>
alert('Pembayaran berhasil divalidasi. Pesanan otomatis selesai.');
window.location='pembayaran.php';
</script>";

