<?php
include 'auth.php';

$user = $_SESSION['user'];

if ($user['role'] === 'pelaksana') {
    header("Location: dashboard.php");
    exit();
}

$pelaksana = $conn->query("SELECT id, username FROM users WHERE role = 'pelaksana'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $assigned_to = $_POST['assigned_to'];
    $created_by = $user['id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, assigned_to, created_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $title, $desc, $assigned_to, $created_by);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Tugas</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
            text-align: left;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Buat Tugas Baru</h2>
        <form method="POST">
            <label for="title">Judul:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <label for="assigned_to">Tugas untuk:</label>
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
