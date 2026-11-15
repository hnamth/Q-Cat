<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("location:../login.php");
    exit;
}

include "../config/koneksi.php";

$menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY nama_menu ASC");

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $id_menu = intval($_POST['id_menu']);
    $jumlah = intval($_POST['jumlah']);
    $tanggal_pesan = mysqli_real_escape_string($conn, $_POST['tanggal_pesan']);
    $pengiriman = mysqli_real_escape_string($conn, $_POST['pengiriman']);
    $alamat = $pengiriman == "antar" ? mysqli_real_escape_string($conn, $_POST['alamat']) : "";
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "
        INSERT INTO users (nama, email, password, role)
        VALUES ('$nama', NULL, NULL, NULL)
    ");

    $id_user = mysqli_insert_id($conn);

    mysqli_query($conn, "
        INSERT INTO pesanan (id_user, id_menu, jumlah, tanggal_pesan, alamat, pengiriman, status)
        VALUES ('$id_user', '$id_menu', '$jumlah', '$tanggal_pesan', '$alamat', '$pengiriman', '$status')
    ");

    header("location:pesanan.php?pesan=Pesanan manual berhasil ditambahkan.");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pesanan Manual</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="sidebar-header p-3">
                <h4>Q'Cat Catering</h4>
            </div>
            <nav class="sidebar-nav">
                <a class="nav-link" href="index.php">Dashboard</a>
                <a class="nav-link" href="menu.php">Menu Catering</a>
                <a class="nav-link active" href="pesanan.php">Pesanan</a>
                <a class="nav-link" href="pembayaran.php">Pembayaran</a>
                <a class="nav-link" href="../logout.php">Logout</a>
                <a href="pesanan.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </nav>
        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar bg-white">
                <div class="container-fluid">
                    <h4>Tambah Pesanan Manual</h4>
                </div>
            </nav>

            <main class="dashboard-content">
                <div class="container-fluid p-4">
                    <div class="panel p-4">
                        <form method="post">
                            <div class="alert alert-info">
                                Pesanan manual menyimpan nama pelanggan tanpa membuat akun login customer.
                            </div>

                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama" class="form-control mb-3" required>

                            <label>Menu</label>
                            <select name="id_menu" class="form-control mb-3" required>
                                <option value="">-- Pilih Menu --</option>
                                <?php while ($m = mysqli_fetch_assoc($menu)) { ?>
                                    <option value="<?= $m['id_menu']; ?>">
                                        <?= htmlspecialchars($m['nama_menu']); ?> - Rp <?= number_format($m['harga']); ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control mb-3" min="1" required>

                            <label>Tanggal Pesan</label>
                            <input type="date" name="tanggal_pesan" class="form-control mb-3" value="<?= date('Y-m-d'); ?>" required>

                            <label>Pengiriman</label>
                            <select name="pengiriman" id="pengiriman" class="form-control mb-3" required>
                                <option value="antar">Antar</option>
                                <option value="jemput">Jemput</option>
                            </select>

                            <div id="fieldAlamat">
                                <label>Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control mb-3" required></textarea>
                            </div>

                            <label>Status</label>
                            <select name="status" class="form-control mb-3" required>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Diterima">Diterima</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>

                            <button type="submit" name="simpan" class="btn btn-primary">
                                Simpan Pesanan
                            </button>
                            <a href="pesanan.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        const pengiriman = document.getElementById('pengiriman');
        const fieldAlamat = document.getElementById('fieldAlamat');
        const alamat = document.getElementById('alamat');

        function toggleAlamat() {
            if (pengiriman.value === 'antar') {
                fieldAlamat.style.display = 'block';
                alamat.required = true;
            } else {
                fieldAlamat.style.display = 'none';
                alamat.required = false;
                alamat.value = '';
            }
        }

        pengiriman.addEventListener('change', toggleAlamat);
        toggleAlamat();
    </script>
</body>

</html>
