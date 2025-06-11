<?php
include 'auth.php';

$user = $_SESSION['user'];

if ($user['role'] === 'pelaksana') {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$task = $conn->query("SELECT * FROM tasks WHERE id = $id")->fetch_assoc();
$pelaksana = $conn->query("SELECT id, username FROM users WHERE role = 'pelaksana'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $assigned_to = $_POST['assigned_to'];
    $conn->query("UPDATE tasks SET title='$title', description='$desc', assigned_to='$assigned_to' WHERE id=$id");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tugas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            align-items: start;
        }

        .container {
            margin-top: 60px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            padding: 40px;
            max-width: 500px;
            width: 95%;
            color: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-bottom: 25px;
            text-align: center;
            font-size: 26px;
        }

        form {
            text-align: left;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: none;
            border-radius: 14px;
            background: rgba(255,255,255,0.2);
            color: white;
            font-size: 14px;
        }

        input::placeholder,
        textarea::placeholder {
            color: #ddd;
        }

        button {
            background-color: #5d3c2c;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 20px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
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
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #ffc0a0;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Tugas</h2>
        <form method="POST">
            <label for="title">Judul:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>

            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($task['description']) ?></textarea>

            <label for="assigned_to">Tugas untuk:</label>
            <select id="assigned_to" name="assigned_to" required>
                <?php while ($p = $pelaksana->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>" <?= $task['assigned_to'] == $p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['username']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Update</button>
        </form>
        <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
