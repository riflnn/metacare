<?php
session_start();
include '../db.php'; // Menghubungkan dengan koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username']; // Ambil username dari sesi
$id = $_GET['id']; // ID jadwal konseling

// Ambil data jadwal yang ingin dihapus milik pengguna yang login
$query_hapus = "SELECT * FROM jadwal_konseling WHERE id = ? AND username = ?";
$stmt = $conn->prepare($query_hapus);
$stmt->bind_param("is", $id, $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Jika data tidak ditemukan, arahkan kembali ke halaman riwayat
if (!$row) {
    echo "<script>alert('Data tidak ditemukan! Kembali ke Riwayat.'); window.location.href = 'riwayat_konseling.php';</script>";
    exit;
}

// Ambil nama dokter berdasarkan counselor_id
$counselor_id = $row['counselor_id'];
$query_doctor = "SELECT name FROM doctors WHERE id = ?";
$stmt_doctor = $conn->prepare($query_doctor);
$stmt_doctor->bind_param("i", $counselor_id);
$stmt_doctor->execute();
$result_doctor = $stmt_doctor->get_result();
$doctor = $result_doctor->fetch_assoc();
$doctor_name = $doctor ? $doctor['name'] : 'Dokter tidak ditemukan';

// Proses penghapusan jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete_stmt = $conn->prepare("DELETE FROM jadwal_konseling WHERE id = ? AND username = ?");
    $delete_stmt->bind_param("is", $id, $username);

    if ($delete_stmt->execute()) {
        echo "<script>alert('Jadwal berhasil dihapus!'); window.location.href = 'riwayat_konseling.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus jadwal. Silakan coba lagi.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Jadwal Konseling</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #bbdefb, #90caf9);
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            color: #333;
        }
        h2 {
            text-align: center;
            color: #90caf9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background: #90caf9;
            color: #fff;
        }
        table tr:nth-child(even) {
            background: #f7f7f7;
        }
        table tr:hover {
            background: #f0f0f0;
        }
        button {
            padding: 10px 15px;
            background: #90caf9;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #2e9bf4;
        }
        a {
            text-decoration: none;
            color: #90caf9;
            font-weight: bold;
            margin-left: 10px;
        }
        a:hover {
            color: #2e9bf4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-trash-alt"></i> Hapus Jadwal Konseling</h2>

        <p>Apakah Anda yakin ingin menghapus jadwal konseling berikut?</p>
        <table>
            <tr>
                <th>Dokter</th>
                <th>Tanggal Konseling</th>
                <th>Waktu</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($doctor_name); ?></td>
                <td><?php echo htmlspecialchars($row['session_date']); ?></td>
                <td><?php echo htmlspecialchars($row['time_slot']); ?></td>
            </tr>
        </table>

        <form method="POST">
            <button type="submit"><i class="fas fa-check"></i> Hapus Jadwal</button>
            <a href="riwayat_konseling.php"><i class="fas fa-times"></i> Batal</a>
        </form>
    </div>
</body>
</html>
