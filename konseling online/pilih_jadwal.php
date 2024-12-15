<?php
session_start();
include '../db.php'; // Menghubungkan dengan koneksi database

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit;
}

$username = $_SESSION['username']; // Ambil username dari sesi

// Ambil daftar dokter dari database
$query_doctors = "SELECT * FROM doctors";
$result_doctors = $conn->query($query_doctors);

// Proses penyimpanan data booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $counselor_id = $_POST['konselor'];      // ID dokter yang dipilih
    $session_date = $_POST['jadwal'];        // Tanggal konseling
    $time_slot = $_POST['waktu'];            // Waktu sesi

    // Query untuk memasukkan data booking ke jadwal_konseling
    $stmt = $conn->prepare("INSERT INTO jadwal_konseling (username, counselor_id, session_date, time_slot) 
                            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $counselor_id, $session_date, $time_slot);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika booking berhasil, arahkan ke halaman riwayat konseling
        header("Location: riwayat_konseling.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Konseling Jadwal</title>
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
            padding: 0;
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
        .icon {
            margin-right: 10px;
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
            background: #90caf9;
        }
        .icon-text {
            display: flex;
            align-items: center;
            color: #90caf9;
        }
        .date-time-container {
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }
        .date-time-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 15px;
            background: #f7f7f7;
            border-radius: 10px;
            border: 2px solid #ddd;
            transition: box-shadow 0.3s;
        }
        .date-time-box:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-color: #90caf9;
        }
        .date-time-box label {
            font-weight: 600;
            color: #90caf9;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #ddd;
        }
        select {
            color: #90caf9; /* Warna teks di dropdown */
            border-color: #90caf9; /* Opsional: jika ingin border dropdown juga biru */
        }

        option {
            color: #90caf9; /* Warna teks di dalam dropdown */
            background-color: #fff; /* Opsional: latar belakang tetap putih */
        }
    </style>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-calendar-check"></i> Book Jadwal Konseling</h2>
        <form method="POST" action="pilih_jadwal.php">
            <div class="icon-text">
                <i class="fas fa-user-circle icon"></i>
                <p>Nama Pengguna: <strong><?php echo htmlspecialchars($username); ?></strong></p>
            </div>

            <label for="konselor" style="color: #90caf9;"><i class="fas fa-user-md"></i> Pilih Dokter:</label>
            <select name="konselor" id="konselor" required>
                <option value="" disabled selected>--Pilih Dokter--</option>
                <?php while ($doctor = $result_doctors->fetch_assoc()): ?>
                    <option value="<?php echo $doctor['id']; ?>"><?php echo $doctor['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <!-- Container Tanggal dan Waktu -->
            <div class="date-time-container">
                <!-- Box untuk Tanggal -->
                <div class="date-time-box">
                    <label for="jadwal"><i class="fas fa-calendar-alt"></i> Tanggal Konseling:</label>
                    <input type="text" name="jadwal" id="jadwal" class="date-picker" required>
                </div>
                <!-- Box untuk Waktu -->
                <div class="date-time-box">
                    <label for="waktu"><i class="fas fa-clock"></i> Waktu Konseling:</label>
                    <input type="text" name="waktu" id="waktu" class="time-picker" required>
                </div>
            </div>

            <button type="submit"><i class="fas fa-check"></i> Book Appointment</button>
        </form>
    </div>
    <footer>&copy; 2024 - MetaCare Konseling Online</footer>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Inisialisasi Date Picker
        flatpickr(".date-picker", {
            dateFormat: "Y-m-d",
            minDate: "today",
        });
        // Inisialisasi Time Picker
        flatpickr(".time-picker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
        });
    </script>
</body>
</html>
