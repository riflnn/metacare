<?php
// riwayatmood.php
include '../db.php';
session_start();

// Pastikan session user_id sudah ada
if (!isset($_SESSION['user_id'])) {
    // Jika session user_id belum ada, arahkan ke login atau tampilkan pesan error
    $message = "User belum login.";
    exit;
}

$user_id = $_SESSION['user_id'];  

// Mengambil data riwayat mood dari database
$query = "SELECT * FROM mood WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Menghapus riwayat mood
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Pastikan delete_id valid dan ada di tabel mood
    $check_query = "SELECT * FROM mood WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, 'ii', $delete_id, $user_id);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // Hapus data mood yang valid
        $delete_query = "DELETE FROM mood WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, 'ii', $delete_id, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: RiwayatEmoji.php");  // Refresh halaman setelah delete
        } else {
            $error_message = "Gagal menghapus riwayat.";
        }
    } else {
        $error_message = "Riwayat mood tidak ditemukan.";
    }
}

// Menutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MetaCare</title>
    <link rel="icon" href="../Gambar MetaCare/MetaCare-2.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Caveat:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b3d4ff;
            margin: 0;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        .container {
            width: 1300px;
            height: 300px;
            background-color: #ffffff;
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-top: 100px;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #4a4a4a;
            margin-left: 40px;
            margin-top: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .katakata {
            flex: 1;
            padding-right: 20px;
            padding-left: 20px;
            margin-top: -100px;
        }

        .gambarnya img {
            width: 500px;
            height: auto;
            margin-top: -110px;
            margin-right: -100px;
        }

        .header h1 {
            font-size: 4em;
            margin: 0;
            font-family: 'Caveat';
            color: #032F45;
        }

        .header p {
            color: #6b7c93;
            margin: 5px 0;
            font-size: 1em;
        }

        .history-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            justify-content: center;
            width: auto;
        }

        .history-item {
            display: flex;
            flex-direction: row;
            align-items: center;
            background-color: #f5faff;
            padding: 10px 15px;
            border-radius: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 80%;
            height: 50px;
        }

        .history-item img {
            width: 70px;
            height: 70px;
            margin-right: 10px;
        }

        .history-info {
            flex-grow: 1;
        }

        .history-info h3 {
            margin: 0;
            font-size: 1em;
            color: #333;
        }

        .history-info p {
            margin: 0;
            font-size: 0.8em;
            color: #666;
        }

        .history-actions {
            display: flex;
            gap: 5px;
        }

        .history-actions img {
            width: 18px;
            height: 18px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .history-actions img:hover {
            transform: scale(1.2); /* Animasi zoom */
            filter: brightness(1.2);
        }

        .history-actions img[alt="Edit"]:hover::after {
            content: 'Edit';
            font-size: 0.8em;
        }

        .dialog-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .dialog-box {
            background: #ffff;
            border-radius: 10px;
            padding: 20px;
            width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .dialog-box h1 {
            font-size: 1.5em;
            font-family: 'Poppins';
            color: #004080;
            margin-bottom: 20px;
        }

        .dialog-box p {
            font-size: 1.3em;
            font-family: 'Poppins', sans-serif;
            color: #000;
            margin-bottom: 15px;
        }

        .dialog-buttons {
            display: flex;
            justify-content: center;
        }

        .dialog-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            font-weight: bold;
            margin: 20px;
        }

        .dialog-button.confirm {
            background-color: #4CAF50;
            color: white;
        }

        .dialog-button.confirm1 {
            background-color: #4CAF50;
            color: white;
        }

        .dialog-button.cancel {
            background-color: #f44336;
            color: white;
        }

        .dialog-button:hover {
            opacity: 0.9;
        }

        .no-history {
            color: #666;
            font-size: 1.5em;
        }
    </style>
</head>
<body>
    <a href="MoodTracker.php">
        <div class="back-button"><img src="../Gambar MetaCare/back.png" alt="Back" style="width: 40px;"></div>
    </a>
    <div class="container">
        <div class="header">
        <div class="katakata">
            <h1>Riwayat</h1>
            <p>Keren! Kamu bisa melihat perkembangan <strong>mood</strong> mu di sini</p>
            <br>
            <p>Isi <strong>Mood Tracker</strong> secara rutin untuk memantau <strong>perkembangan emosional</strong> kamu dan dapatkan rekomendasi yang lebih akurat terkait kesehatan mental!</p>
        </div>
        <div class="gambarnya">
            <img src="../Gambar MetaCare/6.png" alt="Mood Tracker">
        </div>
    </div>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (isset($error_message)): ?>
            <div class="message"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="history-section">
        <?php if (mysqli_num_rows($result) > 0):?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="history-item">
                    <img src="../Gambar MetaCare/<?= $row['emoji'] ?>.png" alt="Emoji">
                    <div class="history-info">
                        <h3>Level: <?= $row['mood_level'] ?></h3>
                        <p><?= $row['created_at'] ?></p>
                    </div>
                    <div class="history-actions">
                        <!-- Edit Button -->
                        <a href="editMood.php?id=<?= $row['id'] ?>" class="button"><img src ="../Gambar MetaCare/Edit.png"></a>
                        <!-- Delete Button -->
                        <a href="RiwayatEmoji.php?delete_id=<?= $row['id'] ?>" class="button"><img src ="../Gambar MetaCare/Delete.png"></a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php else:?>
        <div class="no-history">Belum ada riwayat mood</div>
    <?php endif; ?>
</body>
</html>
