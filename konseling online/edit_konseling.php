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

// Ambil data jadwal yang ingin diedit milik pengguna yang login
$query_edit = "SELECT * FROM jadwal_konseling WHERE id = ? AND username = ?";
$stmt = $conn->prepare($query_edit);
$stmt->bind_param("is", $id, $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Jika data tidak ditemukan, arahkan kembali ke halaman riwayat
if (!$row) {
    header("Location: riwayat_konseling.php");
    exit;
}

// Ambil daftar dokter untuk dropdown
$query_doctors = "SELECT * FROM doctors";
$result_doctors = $conn->query($query_doctors);

// Proses penyimpanan perubahan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $counselor_id = $_POST['konselor'];
    $session_date = $_POST['jadwal'];
    $time_slot = $_POST['waktu'];

    // Query untuk memperbarui data jadwal
    $update_stmt = $conn->prepare("UPDATE jadwal_konseling SET counselor_id = ?, session_date = ?, time_slot = ? WHERE id = ? AND username = ?");
    $update_stmt->bind_param("sssis", $counselor_id, $session_date, $time_slot, $id, $username);

    if ($update_stmt->execute()) {
        header("Location: riwayat_konseling.php");
        exit;
    } else {
        echo "Error: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal Konseling</title>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            font-weight: 600;
        }
        select, input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        select:focus, input:focus {
            border-color: #90caf9;
            outline: none;
        }
        button {
            padding: 12px 20px;
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
        .buttons {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            margin-top: 20px;
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
        <h2><i class="fas fa-edit"></i> Edit Jadwal Konseling</h2>
        <form method="POST">
            <p>Nama Pengguna: <strong><?php echo htmlspecialchars($username); ?></strong></p>

            <label for="konselor"><i class="fas fa-user-md"></i> Pilih Dokter:</label>
            <select name="konselor" id="konselor" required>
                <option value="">--Pilih Dokter--</option>
                <?php while ($doctor = $result_doctors->fetch_assoc()): ?>
                    <option value="<?php echo $doctor['id']; ?>" <?php echo ($doctor['id'] == $row['counselor_id']) ? 'selected' : ''; ?>>
                        <?php echo $doctor['name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="jadwal"><i class="fas fa-calendar-alt"></i> Tanggal Konseling:</label>
            <input type="date" name="jadwal" id="jadwal" value="<?php echo $row['session_date']; ?>" required>

            <label for="waktu"><i class="fas fa-clock"></i> Waktu Konseling:</label>
            <input type="time" name="waktu" id="waktu" value="<?php echo $row['time_slot']; ?>" required>

            <button type="submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>

        <!-- Tombol Navigasi -->
        <div class="buttons">
            <a href="riwayat_konseling.php" class="button"><i class="fas fa-arrow-left"></i> Kembali ke Riwayat</a>
            <a href="pilih_jadwal.php" class="button"><i class="fas fa-arrow-left"></i> Kembali ke Pilih Jadwal</a>
            <a href="../Utama.html" class="button"><i class="fas fa-home"></i> Kembali ke Halaman Utama</a>
        </div>
    </div>
</body>
</html>
