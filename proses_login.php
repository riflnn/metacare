<?php
// Hubungkan ke database
include 'db.php';

session_start();  // Memulai session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan password harus diisi!');</script>";
    } else {
        // Query untuk memeriksa username di database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $user['username'];  // Simpan username di session
                echo "<script>
                    alert('Login berhasil!');
                    window.location.href = 'dashboard.php';  // Arahkan ke halaman dashboard
                </script>";
            } else {
                echo "<script>alert('Password salah!');</script>";
            }
        } else {
            echo "<script>alert('Username tidak ditemukan!');</script>";
        }
        $stmt->close();
    }
    $conn->close();
}
?>
