<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("location:../login.php");
    exit;
}

include "../config/koneksi.php";

$id = intval($_GET['id']);

$data = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT pesanan.*, users.nama, users.role
FROM pesanan
LEFT JOIN users ON pesanan.id_user = users.id_user
WHERE pesanan.id_pesanan = '$id'
"));

if (!$data) {
    echo "<script>alert('Data pesanan tidak ditemukan.'); window.location='pesanan.php';</script>";
    exit;
}

if (strtolower((string) $data['role']) == "customer") {
    echo "<script>alert('Pesanan dari akun customer terdaftar tidak dapat diedit manual.'); window.location='pesanan.php';</script>";
    exit;
}

$menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY nama_menu ASC");

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $id_menu = intval($_POST['id_menu']);
    $jumlah = intval($_POST['jumlah']);
    $tanggal_pesan = mysqli_real_escape_string($conn, $_POST['tanggal_pesan']);
    $pengiriman = mysqli_real_escape_string($conn, $_POST['pengiriman']);
    $alamat = $pengiriman == "antar" ? mysqli_real_escape_string($conn, $_POST['alamat']) : "";
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if (empty($data['id_user'])) {
        mysqli_query($conn, "
            INSERT INTO users (nama, email, password, role)
            VALUES ('$nama', NULL, NULL, NULL)
        ");
        $id_user = mysqli_insert_id($conn);
    } else {
        $id_user = $data['id_user'];
        mysqli_query($conn, "
            UPDATE users SET nama = '$nama'
            WHERE id_user = '$id_user'
        ");
    }

    mysqli_query($conn, "
        UPDATE pesanan SET
            id_user = '$id_user',
            id_menu = '$id_menu',
            jumlah = '$jumlah',
            tanggal_pesan = '$tanggal_pesan',
            alamat = '$alamat',
            pengiriman = '$pengiriman',
            status = '$status'
        WHERE id_pesanan = '$id'
    ");

    header("location:pesanan.php?pesan=Pesanan manual berhasil diedit.");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan Manual</title>
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
                    <h4>Edit Pesanan Manual</h4>
                </div>
            </nav>

            <main class="dashboard-content">
                <div class="container-fluid p-4">
                    <div class="panel p-4">
                        <form method="post">
                            <div class="alert alert-info">
                                Data ini adalah pesanan manual tanpa akun login customer.
                            </div>

                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama" class="form-control mb-3" value="<?= htmlspecialchars((string) $data['nama']); ?>" required>

                            <label>Menu</label>
                            <select name="id_menu" class="form-control mb-3" required>
                                <?php while ($m = mysqli_fetch_assoc($menu)) { ?>
                                    <option value="<?= $m['id_menu']; ?>" <?= $m['id_menu'] == $data['id_menu'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($m['nama_menu']); ?> - Rp <?= number_format($m['harga']); ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <label>Jumlah</label>
                            <input type="number" name="jumlah" class="form-control mb-3" min="1" value="<?= htmlspecialchars($data['jumlah']); ?>" required>

                            <label>Tanggal Pesan</label>
                            <input type="date" name="tanggal_pesan" class="form-control mb-3" value="<?= htmlspecialchars($data['tanggal_pesan']); ?>" required>

                            <label>Pengiriman</label>
                            <select name="pengiriman" id="pengiriman" class="form-control mb-3" required>
                                <option value="antar" <?= $data['pengiriman'] == 'antar' ? 'selected' : ''; ?>>Antar</option>
                                <option value="jemput" <?= $data['pengiriman'] == 'jemput' ? 'selected' : ''; ?>>Jemput</option>
                            </select>

                            <div id="fieldAlamat">
                                <label>Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control mb-3" required><?= htmlspecialchars($data['alamat']); ?></textarea>
                            </div>

                            <label>Status</label>
                            <select name="status" class="form-control mb-3" required>
                                <option value="Menunggu" <?= $data['status'] == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="Diterima" <?= $data['status'] == 'Diterima' ? 'selected' : ''; ?>>Diterima</option>
                                <option value="Selesai" <?= $data['status'] == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Ditolak" <?= $data['status'] == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                            </select>

                            <button type="submit" name="update" class="btn btn-primary">
                                Update Pesanan
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
