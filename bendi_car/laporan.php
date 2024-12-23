<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data penyewaan
$query = "SELECT p.*, u.username, m.nama_mobil, m.plat_nomor 
          FROM penyewaan p 
          JOIN pengguna u ON p.id_pengguna = u.id 
          JOIN mobil m ON p.id_mobil = m.id";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Laporan Penyewaan</title>
</head>
<body>
    <h1>Laporan Penyewaan</h1>
    <table border="1">
        <tr>
            <th>Nama Penyewa</th>
            <th>Nama Mobil</th>
            <th>Plat Nomor</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Total Biaya</th>
            <th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['username'] ?></td>
            <td><?= $row['nama_mobil'] ?></td>
            <td><?= $row['plat_nomor'] ?></td>
            <td><?= $row['tanggal_pinjam'] ?></td>
            <td><?= $row['tanggal_kembali'] ?: '-' ?></td>
            <td><?= $row['total_biaya'] ?></td>
            <td><?= ucfirst($row['status']) ?></td>
        </tr>
        <?php } ?>
    </table>
    <a href="admin_dashboard.php" class="button">Kembali</a>
</body>
</html>
