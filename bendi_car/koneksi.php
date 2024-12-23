<?php
$host = "localhost";
$user = "root";
$pass = "mysql";
$db   = "bendi_car";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
