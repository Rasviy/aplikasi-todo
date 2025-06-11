<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';
require 'auth.php';

$user = $_SESSION['user'];

if ($user['role'] === 'pelaksana') {
    header("Location: dashboard.php");
    exit();
}

$pelaksana = $conn->query("SELECT id, username FROM users WHERE role = 'pelaksana'");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = $user['id'];

    if (!empty($title) && !empty($assigned_to)) {
        $stmt = $conn->prepare("INSERT INTO tasks (title, description, assigned_to, created_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $title, $description, $assigned_to, $created_by);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Gagal menambahkan tugas: " . $stmt->error;
        }
    } else {
        $error = "Judul dan Pelaksana wajib diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Tugas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@500&display=swap');

        * {
            box-sizing: border-box;
            font-family: 'Fredoka', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('img/e69a4f74b1b0fbdc70c9a6428aa4221e.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 10px;
        }

        .container {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 30px 25px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            color: #fff;
        }

        h2 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        form label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            background-color: rgba(255,255,255,0.2);
            color: #fff;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #5d3c2c;
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            width: 100%;
            font-size: 14px;
            cursor: pointer;
        }

        button:hover {
            background-color: #8d5c44;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #fff;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error {
            background: rgba(255, 0, 0, 0.2);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Buat Tugas Baru</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="title">Judul</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <label for="assigned_to">Tugaskan ke</label>
            <select id="assigned_to" name="assigned_to" required>
                <option value="">-- Pilih Pelaksana --</option>
                <?php while ($p = $pelaksana->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['username']) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Simpan</button>
        </form>
        <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
