<?php
// editMood.php
include '../db.php';
session_start();

// Pastikan session user_id sudah ada
if (!isset($_SESSION['user_id'])) {
    $message = "User belum login.";
    exit;
}

$user_id = $_SESSION['user_id'];  

// Ambil id mood yang akan diedit
if (isset($_GET['id'])) {
    $mood_id = $_GET['id'];

    // Ambil data mood berdasarkan id dan user_id
    $query = "SELECT * FROM mood WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $mood_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Pastikan data ditemukan
    if (mysqli_num_rows($result) > 0) {
        $mood = mysqli_fetch_assoc($result);
    } else {
        $message = "Mood tidak ditemukan.";
        exit;
    }

    // Menangani pengiriman form edit mood
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $emoji = $_POST['emoji'];
        $mood_level = $_POST['mood_level'];

        // Update data mood ke database
        $update_query = "UPDATE mood SET emoji = ?, mood_level = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, 'siii', $emoji, $mood_level, $mood_id, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: RiwayatEmoji.php"); // Redirect setelah update
        } else {
            $message = "Gagal memperbarui mood.";
        }
    }
} else {
    $message = "ID mood tidak ditemukan.";
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
            background-color: #d0e7ff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
        }

        .container {
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #FFF 46.62%, rgba(147, 196, 255, 0.70) 100%);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: auto;
            text-align: left;
            position: relative;
        }

        .header {
            background-color: #b3d4ff;
            display: flex;
            height: 44%;
            justify-content: space-around;
            align-items: center;
        }

        .header h1 {
            margin-left: 40px;
            font-size: 2em;
            color: #004080;
            font-family: 'Poppins';
        }

        .header p {
            font-size: 1.2em;
            color: #032F45;
            font-family: 'Poppins';
            margin-left: 40px;
        }

        .emojis {
            padding: 20px;
            border-top-left-radius: 40px;
            border-top-right-radius: 40px;
            background: linear-gradient(180deg, #FFF 46.62%, rgba(147, 196, 255, 0.70) 100%);
            margin-top: -31px;
            height: 60%;
        }

        .emojis h2 {
            margin-bottom: 15px;
            padding-left: 60px;
            color: #0b0b0b;
            justify-content: space-evenly;
            font-family: 'Poppins';
            font-size: 30px;
        }

        .emojis-riwayat {
            display: flex;
            padding-right: 60px;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0;
        }

        .emojis-right {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .emoji-row {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin: 20px 0;
        }

        .emoji {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.9em;
        }

        .emoji img {
            width: 100px;
            height: 100px;
            margin-bottom: 5px;
        }

        .emoji-label {
            display: block;
            margin-top: 5px; 
            font-size: 14px;
            text-align: center;
        }

        .slider-container {
            margin: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .slider-label {
            font-weight: bold;
            margin: 20px;
            margin-top: 50px;
            display: flex;
            align-items: left;
            justify-content: left;
            gap: 10px;
        }

        .slider-container input[type="range"] {
            width: 85%;
        }

        .button {
            background-color: #f7c847;
            color: #000;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            margin: 20px;
        }

        .button:hover {
            background-color: #e0b33b;
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

        .dialog-button.cancel {
            background-color: #f44336;
            color: white;
        }

        .dialog-button:hover {
            opacity: 0.9;
        }

        .no-underline {
            text-decoration: none;
            color: #032F45;
        }

        .search-button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            margin-left: 30px;
            margin-bottom: 50px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: center;
                text-align: center;
                height: auto;
                padding: 10px;
            }

            .header h1 {
                font-size: 1.5em;
                margin: 10px 0;
            }

            .header p {
                font-size: 1em;
                margin: 10px 0;
            }

            .header img {
                width: 150px;
                height: auto;
                margin: 10px 0;
            }

            .emoji-row {
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
            }

            .emoji {
                margin: 5px;
            }

            .emoji img {
                width: 50px;
                height: 50px;
            }

            .slider-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
            }

            .slider-container input[type="range"] {
                width: 80%;
            }

            .button {
                width: 80%;
                padding: 8px 10px;
                font-size: 0.9em;
                margin: 5px auto;
            }
        }
    </style>
</head>
</head>
<body>
    <audio controls autoplay hidden loop>
        <source src="../Lagu MetaCare/lagu relaksasi.mp3" type="audio/mp3">
    </audio>
    <div class="container">
        <div class="header">
            <div>
                <div class="search-container">
                    <a href="RiwayatEmoji.php">
                        <button class="search-button">
                            <img src="../Gambar MetaCare/back.png" alt="Search Icon" style="width: 40px;">
                        </button>
                    </a>
                    
                </div>
                <h1>Ayo, ekspresikan perasaanmu hari ini!</h1>
                <p>Pilih emoji yang sesuai dengan suasana hatimu dan lihat bagaimana perubahan emosi harianmu. <br> Bersama kita ciptakan hari yang lebih baik!</p>
            </div>
            <div>
                <img src="../Gambar MetaCare/4.png" width="350px" height="300px" alt="Mood Tracker">
            </div>
        </div>
        <div class="emojis">
            <div class="emojis-riwayat">
                <h2>Bagaimana perasaanmu saat ini?</h2>
                <div class="emojis-right">
                    <img src="../Gambar MetaCare/Happy.png" alt="Emoji" width="20px" height="20px">
                    <a href="RiwayatEmoji.php" class="no-underline">
                        <span>Riwayat Emoji</span>
                    </a>
                </div>
            </div>

        <!-- Tampilkan pesan error jika ada -->
        <?php if (isset($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <!-- Form untuk mengedit mood -->
        <form method="POST">
            <div class="emoji-row">
                <div class="emoji">
                    <label for="emoji_sangat_buruk">
                        <img src="../Gambar MetaCare/sangat buruk.png" alt="Sangat Buruk">
                        <span class = "emoji-label">Sangat Buruk</span>
                    </label>
                    <input type="radio" id="emoji_sangat_buruk" name="emoji" value="sangat buruk">
                </div>
                <div class="emoji">
                    <label for="emoji_buruk">
                        <img src="../Gambar MetaCare/buruk.png" alt="Buruk">
                        <span class = "emoji-label">Buruk</span>
                    </label>
                    <input type="radio" id="emoji_buruk" name="emoji" value="buruk">
                </div>
                <div class="emoji">
                    <label for="emoji_biasa">
                        <img src="../Gambar MetaCare/biasa.png" alt="Biasa">
                        <span class = "emoji-label">Biasa</span>
                    </label>
                    <input type="radio" id="emoji_biasa" name="emoji" value="biasa">
                </div>
                <div class="emoji">
                    <label for="emoji_baik">
                        <img src="../Gambar MetaCare/baik.png" alt="Baik">
                        <span class = "emoji-label">Baik</span>
                    </label>
                    <input type="radio" id="emoji_baik" name="emoji" value="baik">
                </div>
                <div class="emoji">
                    <label for="emoji_sangat_baik">
                        <img src="../Gambar MetaCare/sangat baik.png" alt="Sangat Baik">
                        <span class = "emoji-label">Sangat Baik</span>
                    </label>
                    <input type="radio" id="emoji_sangat_baik" name="emoji" value="sangat baik">
                </div>
            </div>
            <div class="slider-label">
                <span>Mood Level (1-10):</span>
            </div>
            <div class="slider-container">
                <input type="range" name="mood_level" id="mood_slider" min="1" max="10" value="5">
                <span id="mood_level_value">5</span>
            </div>
            <div style="display: flex; justify-content: center; align-items: center; height: 15vh;">
            <button class="button" type="submit">Update Emoji</button>
            </div>
        </form>
    </div>
</body>
</html>
