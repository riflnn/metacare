<?php
// Hubungkan ke database
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $dob = trim($_POST['dob']);
    $telepon = trim($_POST['telepon']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validasi input
    if (empty($nama) || empty($dob) || empty($telepon) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Password tidak cocok!');</script>";
    } elseif (!preg_match('/^\d{10,15}$/', $telepon)) {
        echo "<script>alert('Nomor telepon harus 10-15 digit angka.');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!');</script>";
    } else {
        // Cek apakah username atau email sudah ada
        $cek_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $cek_stmt = $conn->prepare($cek_sql);
        $cek_stmt->bind_param("ss", $username, $email);
        $cek_stmt->execute();
        $result = $cek_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Username atau email sudah terdaftar!');</script>";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Query untuk memasukkan data
            $sql = "INSERT INTO users (name, dob, phone_number, email, username, password) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            $stmt->bind_param("ssssss", $nama, $dob, $telepon, $email, $username, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Registrasi berhasil! Silakan login.');
                    window.location.href = 'login.php';
                </script>";
            } else {
                echo "<script>alert('Terjadi kesalahan. Coba lagi nanti.');</script>";
            }
            $stmt->close();
        }

        $cek_stmt->close();
    }

    $conn->close();
}
?>