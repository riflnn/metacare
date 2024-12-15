<?php
   $servername = "localhost";
   $username = "";
   $password = "";
   $dbname = "mood_tracker";
   $conn = new mysqli($servername, $username, $password, $dbname);
   if ($conn->connect_error) {
        die("Error!". $conn->connect_error);
   }
   echo"Koneksi Sukses!";
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
         $conn->close();
?>
