<?php
session_start();
include '../db.php'; // Menghubungkan dengan koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username']; // Ambil username dari sesi

// Query untuk mengambil jadwal konseling pengguna
$query_riwayat = "SELECT j.id, d.name AS counselor_name, j.session_date, j.time_slot 
                  FROM jadwal_konseling j
                  JOIN doctors d ON j.counselor_id = d.id
                  WHERE j.username = ?";
$stmt = $conn->prepare($query_riwayat);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Konseling</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #bbdefb, #90caf9);
            color: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
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
        table thead {
            background: #90caf9;
            color: #fff;
        }
        table th, table td {
            padding: 10px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background: #f7f7f7;
        }
        table tr:hover {
            background: #f0f0f0;
        }
        .actions a {
            margin: 0 5px;
            text-decoration: none;
            color: #90caf9;
            font-weight: bold;
        }
        .actions a:hover {
            color:#2e9bf4;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }
        .button {
            padding: 10px 15px;
            background: #90caf9;
            color: #fff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
            text-align: center;
        }
        .button:hover {
            background: #2e9bf4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-history"></i> Riwayat Konseling</h2>

        <!-- Tabel Riwayat Konseling -->
        <table>
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Tanggal Konseling</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['counselor_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['session_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time_slot']); ?></td>
                        <td class="actions">
                            <a href="edit_konseling.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                            <a href="hapus_konseling.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Tombol Navigasi -->
        <div class="buttons">
            <a href="pilih_jadwal.php" class="button"><i class="fas fa-arrow-left"></i> Kembali ke Pilih Jadwal</a>
            <a href="../Utama.html" class="button"><i class="fas fa-home"></i> Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>
