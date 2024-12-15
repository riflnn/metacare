<?php
// Mulai sesi
session_start();

// Hapus semua sesi
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <script>
        // Menampilkan notifikasi logout berhasil
        Swal.fire({
            icon: 'success',
            title: 'Logout Berhasil',
            text: 'Anda telah keluar dari akun.',
            showConfirmButton: false,
            timer: 3000
        }).then(() => {
            // Arahkan ke halaman login setelah notifikasi
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
