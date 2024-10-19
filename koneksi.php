<?php
// Koneksi ke database
$servername = "localhost"; // Sesuaikan dengan server database Anda
$username = "root"; // Sesuaikan dengan username database
$password = ""; // Sesuaikan dengan password database (biarkan kosong jika default XAMPP)
$database = "project_uts"; // Ganti dengan nama database yang sesuai

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $database);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
