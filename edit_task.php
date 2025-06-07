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
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('img/gradient-background-3840x2160-10786.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: start;
        }

        .container {
             margin-top: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 95%;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            color: #fff;
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
           background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: rgba(255, 255, 255, 0.1);
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
