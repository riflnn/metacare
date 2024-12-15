<?php
// Hubungkan ke database
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($email) || empty($password)) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Email dan password harus diisi.',
                showConfirmButton: true
            });
        </script>";
    } else {
        // Query untuk mencari user berdasarkan email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil, set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect ke halaman utama setelah login sukses
            header("Location: Utama.html"); // Pengalihan ke halaman utama
            exit();
        } else {
            // Login gagal
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: 'Email atau password salah.',
                    showConfirmButton: true
                });
            </script>";
        }

        $stmt->close();
    }

    // Tutup koneksi
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/csslogin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="beranda-title">Your Well-Being is Our Priority</div>
    <div class="container">
        <div class="welcoming">
            <a href=""><img src="img/MetaCare-2 1.png" alt="MetaCare"></a>
            <div class="katakata">
                <h1>WELCOME BACK</h1>
                <p>Silakan login untuk melanjutkan</p>
            </div>
        </div>
        <div class="isiform">
            <form action="login.php" method="post">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">LOGIN</button>
            </form>
            <div class="gugel">
                <button type="button" class="google-button">
                    <img src="img/logogoogle.png" alt="Google logo" class="google-logo"> LOGIN DENGAN GOOGLE
                </button>
            </div>
            <div class="no-account">
                <p>Belum punya akun? <a href="regist.php">Daftar di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>
