<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../metacare/css/cssregist.css">
    <style>
        .notif {
            color: white;
            background-color: red;
            padding: 10px;
            margin: 10px 0;
            text-align: center;
            border-radius: 5px;
            display: none;
        }
        .notif.success {
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="beranda-title">Your Well-Being is Our Priority</div>
    <div class="container">
        <div class="welcoming">
            <a href=""><img src="img/MetaCare-2 1.png" alt="MetaCare"></a>
            <div class="katakata">
                <h1>WELCOME</h1>
                <p>Daftar sekarang dan temukan <br> berbagai fitur menarik di dalamnya</p>
            </div>
        </div>
        <div class="isiform">
            <?php if (!empty($notif)): ?>
                <div class="notif <?php echo strpos($notif, 'berhasil') !== false ? 'success' : ''; ?>">
                    <?php echo $notif; ?>
                </div>
            <?php endif; ?>

            <form action="proses_registrasi.php" method="post">
                <label for="nama">Nama Lengkap: </label>
                <input type="text" id="nama" name="nama" required>

                <label for="dob">Tanggal Lahir:</label>
                <input type="date" id="dob" name="dob" required>

                <label for="telepon">Nomor Telepon:</label>
                <input type="text" id="telepon" name="telepon" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Kata Sandi:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Konfirmasi Kata Sandi:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit">DAFTAR</button>
            </form>

            <div class="already-account">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </div>
</body>
</html>