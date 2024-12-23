<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['id'];
$id_mobil = isset($_GET['id']) ? $_GET['id'] : null;

if ($id_mobil) {
    // Ambil informasi mobil
    $query_mobil = "SELECT * FROM mobil WHERE id='$id_mobil'";
    $result_mobil = mysqli_query($conn, $query_mobil);
    $mobil = mysqli_fetch_assoc($result_mobil);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id_mobil) {
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    // Query untuk menyimpan data penyewaan
    $query = "INSERT INTO penyewaan (id_pengguna, id_mobil, tanggal_pinjam, tanggal_kembali) 
              VALUES ('$id_pengguna', '$id_mobil', '$tanggal_pinjam', '$tanggal_kembali')";
    if (mysqli_query($conn, $query)) {
        header("Location: user_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Gagal menyimpan data penyewaan.');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Penyewaan Mobil</title>
</head>
<body>
    <h1>Form Penyewaan</h1>
    <h2>Mobil yang Dipilih</h2>
    <p>Nama Mobil: <?= $mobil['nama_mobil'] ?></p>
    <p>Plat Nomor: <?= $mobil['plat_nomor'] ?></p>
    <p>Harga Sewa: Rp<?= number_format($mobil['harga_sewa'], 0, ',', '.') ?>/hari</p>
    <form method="POST" id="form-sewa">
        <label>Tanggal Pinjam:</label>
        <input type="date" name="tanggal_pinjam" required>
        <label>Tanggal Kembali:</label>
        <input type="date" name="tanggal_kembali" required>
        <button type="submit" name="sewa">Sewa</button>
    </form>
    <script src="js/script.js"></script>
</body>
</html>