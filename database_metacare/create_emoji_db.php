<?php
   $servername = "localhost";
   $username = "";
   $password = "rahasia";
   $dbname = "metacare";
   $conn = new mysqli($servername, $username, $password, $dbname);
   if ($conn->connect_error) {
        die("Error!". $conn->connect_error);
   }

   $sql = "CREATE TABLE emoji (
   id_emoji INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
   id_pengguna INT(6) UNSIGNED,
   username VARCHAR(30) NOT NULL,
   emoji VARCHAR(30) NOT NULL,
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
