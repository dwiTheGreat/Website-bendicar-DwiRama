<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data mobil
$query_mobil = "SELECT * FROM mobil";
$result_mobil = mysqli_query($conn, $query_mobil);

// Tambah mobil
if (isset($_POST['tambah_mobil'])) {
    $nama_mobil = $_POST['nama_mobil'];
    $plat_nomor = $_POST['plat_nomor'];
    $harga_sewa = $_POST['harga_sewa'];

    $query_tambah = "INSERT INTO mobil (nama_mobil, plat_nomor, harga_sewa) 
                     VALUES ('$nama_mobil', '$plat_nomor', '$harga_sewa')";
    mysqli_query($conn, $query_tambah);
    header("Location: admin_dashboard.php");
}

// Hapus mobil
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query_hapus = "DELETE FROM mobil WHERE id='$delete_id'";
    mysqli_query($conn, $query_hapus);
    header("Location: admin_dashboard.php");
}

// Ambil data mobil yang sedang disewa
$query_penyewaan = "SELECT p.*, u.username, m.nama_mobil, m.plat_nomor, m.harga_sewa, 
                    p.tanggal_pinjam, p.tanggal_kembali 
                    FROM penyewaan p 
                    JOIN pengguna u ON p.id_pengguna = u.id 
                    JOIN mobil m ON p.id_mobil = m.id 
                    WHERE p.status = 'dipinjam'";
$result_penyewaan = mysqli_query($conn, $query_penyewaan);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Dashboard Admin</h1>
    
    <!-- Daftar Mobil -->
    <h2>Kelola Data Mobil</h2>
    <table border="1">
        <tr>
            <th>Nama Mobil</th>
            <th>Plat Nomor</th>
            <th>Harga Sewa/Hari</th>
            <th>Aksi</th>
        </tr>
        <?php while ($mobil = mysqli_fetch_assoc($result_mobil)) { ?>
        <tr>
            <td><?= $mobil['nama_mobil'] ?></td>
            <td><?= $mobil['plat_nomor'] ?></td>
            <td>Rp<?= number_format($mobil['harga_sewa'], 0, ',', '.') ?></td>
            <td>
                <a href="?delete_id=<?= $mobil['id'] ?>" class="delete-button">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h2>Tambah Mobil</h2>
    <form method="POST">
        <input type="text" name="nama_mobil" placeholder="Nama Mobil" required>
        <input type="text" name="plat_nomor" placeholder="Plat Nomor" required>
        <input type="number" name="harga_sewa" placeholder="Harga Sewa" required>
        <button type="submit" name="tambah_mobil">Tambah</button>
    </form>

    <!-- Daftar Mobil yang Sedang Disewa -->
    <h2>Mobil yang Sedang Disewa</h2>
    <table border="1">
        <tr>
            <th>Nama User</th>
            <th>Nama Mobil</th>
            <th>Plat Nomor</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali (Seharusnya)</th>
            <th>Harga Sewa/Hari</th>
            <th>Total Biaya</th>
        </tr>
        <?php while ($penyewaan = mysqli_fetch_assoc($result_penyewaan)) { 
            $tanggal_pinjam = strtotime($penyewaan['tanggal_pinjam']);
            $tanggal_kembali_seharusnya = strtotime($penyewaan['tanggal_kembali']);
            $hari_sewa = ceil(($tanggal_kembali_seharusnya - $tanggal_pinjam) / 86400);
            $total_biaya = $hari_sewa * $penyewaan['harga_sewa'];
        ?>
        <tr>
            <td><?= $penyewaan['username'] ?></td>
            <td><?= $penyewaan['nama_mobil'] ?></td>
            <td><?= $penyewaan['plat_nomor'] ?></td>
            <td><?= $penyewaan['tanggal_pinjam'] ?></td>
            <td><?= $penyewaan['tanggal_kembali'] ?></td>
            <td>Rp<?= number_format($penyewaan['harga_sewa'], 0, ',', '.') ?></td>
            <td>Rp<?= number_format($total_biaya, 0, ',', '.') ?></td>
        </tr>
        <?php } ?>
    </table>

    <script src="js/script.js"></script> <!-- Tambahkan jika ada konfirmasi -->
</body>
</html>