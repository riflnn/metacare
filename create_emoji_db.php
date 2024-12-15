<?php
   include 'db.php';

   $sql = "CREATE TABLE emoji (
   id_emoji INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   id_pengguna INT(6) UNSIGNED,
   username VARCHAR(30) NOT NULL,
   emoji VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
   waktu TIMESTAMP
   )";
   
   $result = $conn->query($sql);
    if ($result) {
          echo "Tabel berhasil dibuat!";
    } else {
          echo "Error: ". $conn->error;
    }
    echo "Koneksi Sukses"
?>
