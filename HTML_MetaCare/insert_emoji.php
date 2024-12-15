<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "metacare";
    
    // Membuat koneksi ke database
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Periksa apakah koneksi berhasil
    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
    }
    
    if (isset($_POST['selectedEmoji'])) {
        $emoji = $conn->real_escape_string($_POST['selectedEmoji']); // Escape for security

        $id_emoji = 1; 
        $id_pengguna = 1; 
        $username = "Fani";
        date_default_timezone_set('Asia/Jakarta');
        $waktu = date('Y-m-d H:i:s');
        
        $sql = "INSERT INTO emoji (id_pengguna, username, emoji, waktu) 
        VALUES (?, ?, ?, now())";



        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("iss", $id_pengguna, $username, $emoji);
            if ($stmt->execute()) {
                echo "Emoji berhasil ditambahkan!";
            } else {
                echo "Gagal menambahkan emoji: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Gagal menyiapkan statement: " . $conn->error;
        }
    } else {
        echo "Emoji tidak ditemukan!";
    }
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $conn->close();
}
?>