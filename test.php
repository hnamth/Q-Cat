<?php
// Memanggil file koneksi yang berada di dalam folder config
include 'config/koneksi.php';

echo "<h1>Halo! File test.php berhasil memanggil koneksi database.</h1>";

// Kita tes ambil data dari tabel users milikmu untuk membuktikan koneksi sukses
$query = mysqli_query($conn, "SELECT * FROM users");

if ($query) {
    echo "Koneksi ke tabel users sukses! Jumlah data saat ini: " . mysqli_num_rows($query);
} else {
    echo "Koneksi gagal membaca tabel users: " . mysqli_error($conn);
}
