<?php
// Konfigurasi untuk menghubungkan ke database
$servername = "localhost";  // Ganti dengan server Anda (misalnya, localhost)
$username = "root";         // Ganti dengan username database Anda
$password = "";             // Ganti dengan password database Anda jika ada
$dbname = "metacare";       // Ganti dengan nama database Anda

// Membuat koneksi ke database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Memeriksa koneksi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
