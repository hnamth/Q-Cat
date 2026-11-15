<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != "customer") {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

$id_pesanan = $_GET['id'];

//ambil data pesanan
$query = mysqli_query($conn, " SELECT pesanan.*, menu.nama_menu, menu.harga, pembayaran.status_validasi
                               FROM pesanan
                               JOIN menu
ON pesanan.id_menu = menu.id_menu
LEFT JOIN pembayaran
ON pembayaran.id_pesanan = pesanan.id_pesanan
WHERE pesanan.id_pesanan='$id_pesanan'
");

$data = mysqli_fetch_assoc($query);


// Harus sudah diterima admin
if ($data['status'] != "Diterima") {
    echo "<script>window.location='transaksi.php';</script>";
    exit;
}

// Sudah upload
if ($data['status_validasi'] == "belum valid") {
    echo "<script>
    alert('Bukti pembayaran sudah diupload dan sedang menunggu validasi admin.');
    window.location='transaksi.php';
    </script>";
    exit;
}

// Sudah divalidasi
if ($data['status_validasi'] == "valid") {
    echo "<script>
    alert('Pembayaran sudah divalidasi.');
    window.location='transaksi.php';
    </script>";
    exit;
}


// Upload
if(isset($_POST['upload'])){
    $file = $_FILES['bukti']['name'];
    $tmp  = $_FILES['bukti']['tmp_name'];

    $namaBaru = time()."_".$file;

    if(move_uploaded_file($tmp,"../uploads/".$namaBaru)){
        mysqli_query($conn," INSERT INTO pembayaran (id_pesanan,bukti_transfer,status_validasi)
                            VALUES ('$id_pesanan','$namaBaru','belum valid')");
        echo "<script> alert('Bukti pembayaran berhasil diupload.');
              window.location='transaksi.php'; </script>";

    }else{
        echo "<script>
        alert('Upload gagal.');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Pembayaran</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body">
            <h3>Upload Bukti Pembayaran</h3>

            <hr>

            <p><b>Menu :</b> <?= $data['nama_menu']; ?></p>
            <p><b>Jumlah :</b> <?= $data['jumlah']; ?></p>
            <p><b>Total :</b>
                Rp <?= number_format($data['jumlah'] * $data['harga'],0,",","."); ?>
            </p>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">
                        Bukti Transfer
                    </label>
                    <input type="file"
                           name="bukti"
                           class="form-control"
                           required>
                </div>

                <button class="btn btn-warning w-100" name="upload">
                    Upload Bukti Pembayaran</button>
            </form>

            <a href="transaksi.php"
               class="btn btn-secondary w-100 mt-2">
                Kembali
            </a>
        </div>
    </div>
</div>

<script src="js/website.js"></script>

</body>
</html>
