<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$id_pengguna = $_SESSION['id'];

// Ambil daftar mobil yang bisa disewa
$query_mobil = "SELECT * FROM mobil";
$result_mobil = mysqli_query($conn, $query_mobil);

// Ambil daftar mobil yang sedang disewa
$query_penyewaan = "SELECT p.*, m.nama_mobil, m.plat_nomor, m.harga_sewa 
                    FROM penyewaan p 
                    JOIN mobil m ON p.id_mobil = m.id 
                    WHERE p.id_pengguna = '$id_pengguna' AND p.status = 'dipinjam'";
$result_penyewaan = mysqli_query($conn, $query_penyewaan);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard User</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Dashboard User</h1>
    
    <!-- Daftar Mobil yang Bisa Disewa -->
    <h2>Daftar Mobil yang Tersedia</h2>
    <table border="1">
        <tr>
            <th>Nama Mobil</th>
            <th>Plat Nomor</th>
            <th>Harga Sewa/Hari</th>
            <th>Pilihan</th>
        </tr>
        <?php while ($mobil = mysqli_fetch_assoc($result_mobil)) { ?>
        <tr>
            <td><?= $mobil['nama_mobil'] ?></td>
            <td><?= $mobil['plat_nomor'] ?></td>
            <td>Rp<?= number_format($mobil['harga_sewa'], 0, ',', '.') ?></td>
            <td>
                <a href="penyewaan.php?id=<?= $mobil['id'] ?>">Sewa</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <!-- Daftar Mobil yang Sedang Disewa -->
    <h2>Mobil yang Sedang Disewa</h2>
    <table border="1">
        <tr>
            <th>Nama Mobil</th>
            <th>Plat Nomor</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali (Seharusnya)</th>
            <th>Harga Sewa/Hari</th>
            <th>Total Biaya</th>
            <th>Pilihan</th>
        </tr>
        <?php while ($penyewaan = mysqli_fetch_assoc($result_penyewaan)) { 
            $tanggal_pinjam = strtotime($penyewaan['tanggal_pinjam']);
            $tanggal_kembali_seharusnya = strtotime($penyewaan['tanggal_kembali']);
            $hari_sewa = ceil(($tanggal_kembali_seharusnya - $tanggal_pinjam) / 86400);
            $total_biaya = $hari_sewa * $penyewaan['harga_sewa'];
        ?>
        <tr>
            <td><?= $penyewaan['nama_mobil'] ?></td>
            <td><?= $penyewaan['plat_nomor'] ?></td>
            <td><?= $penyewaan['tanggal_pinjam'] ?></td>
            <td><?= $penyewaan['tanggal_kembali'] ?></td>
            <td>Rp<?= number_format($penyewaan['harga_sewa'], 0, ',', '.') ?></td>
            <td>Rp<?= number_format($total_biaya, 0, ',', '.') ?></td>
            <td>
                <a href="pengembalian.php?id=<?= $penyewaan['id'] ?>">Kembalikan</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>