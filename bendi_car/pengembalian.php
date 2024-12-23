<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['id'];
$id_penyewaan = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_penyewaan) {
    // Ambil data penyewaan
    $query = "SELECT p.*, m.nama_mobil, m.plat_nomor, m.harga_sewa 
              FROM penyewaan p 
              JOIN mobil m ON p.id_mobil = m.id 
              WHERE p.id = '$id_penyewaan' AND p.id_pengguna = '$id_pengguna'";
    $result = mysqli_query($conn, $query);
    $penyewaan = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $tanggal_kembali = date('Y-m-d');
        $tanggal_kembali_seharusnya = $penyewaan['tanggal_kembali'];
        $denda_per_hari = 50000; // Denda per hari
        $terlambat = (strtotime($tanggal_kembali) - strtotime($tanggal_kembali_seharusnya)) / 86400;

        // Hitung denda jika terlambat
        $denda = ($terlambat > 0) ? $terlambat * $denda_per_hari : 0;

        // Hitung total biaya
        $tanggal_pinjam = strtotime($penyewaan['tanggal_pinjam']);
        $hari_sewa = ceil((strtotime($tanggal_kembali) - $tanggal_pinjam) / 86400);
        $total_biaya = $hari_sewa * $penyewaan['harga_sewa'] + $denda;

        // Perbarui status penyewaan
        $query_update = "UPDATE penyewaan 
                         SET status = 'dikembalikan', total_biaya = '$total_biaya' 
                         WHERE id = '$id_penyewaan'";
        mysqli_query($conn, $query_update);

        // Simpan data pengembalian
        $query_pengembalian = "INSERT INTO pengembalian (id_penyewaan, biaya_denda, keterangan) 
                               VALUES ('$id_penyewaan', '$denda', 'Pengembalian terlambat $terlambat hari')";
        mysqli_query($conn, $query_pengembalian);

        echo "<script>alert('Pengembalian berhasil! Total biaya: Rp" . number_format($total_biaya, 0, ',', '.') . "');</script>";
        header("Location: user_dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Pengembalian Mobil</title>
</head>
<body>
    <h1>Pengembalian Mobil</h1>
    <h2>Detail Penyewaan</h2>
    <p>Nama Mobil: <?= $penyewaan['nama_mobil'] ?></p>
    <p>Plat Nomor: <?= $penyewaan['plat_nomor'] ?></p>
    <p>Tanggal Pinjam: <?= $penyewaan['tanggal_pinjam'] ?></p>
    <p>Tanggal Kembali (Seharusnya): <?= $penyewaan['tanggal_kembali'] ?></p>
    <p>Harga Sewa/Hari: Rp<?= number_format($penyewaan['harga_sewa'], 0, ',', '.') ?></p>
    <form method="POST">
        <button type="submit">Kembalikan Mobil</button>
    </form>
</body>
</html>