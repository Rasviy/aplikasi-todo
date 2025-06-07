<?php
include 'auth.php';

$user = $_SESSION['user'];
$user_id = $user['id'];

if ($user['role'] !== 'pelaksana') {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND assigned_to = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    echo "Tugas tidak ditemukan atau tidak ditugaskan ke Anda.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $status = $_POST['status'];

    $allowed_statuses = ['selesai', 'ditolak', 'tidak dikerjakan'];
    if (!in_array($status, $allowed_statuses)) {
        echo "Status tidak valid.";
        exit();
    }

    $update_stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?");
    $update_stmt->bind_param("sii", $status, $id, $user_id);
    $update_stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Update Status</title>
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
            max-width: 1100px;
            width: 95%;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            color: #fff;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        select {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
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
            background-color:rgb(70, 72, 74);
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

        .task-title {
            font-weight: bold;
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Status Tugas</h2>
        <div class="task-title"><?= htmlspecialchars($task['title']) ?></div>
        <form method="POST">
            <label for="status">Status:</label><br>
            <select name="status" id="status" required>
                <option value="">-- Pilih Status --</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
                <option value="tidak dikerjakan">Tidak Dikerjakan</option>
            </select><br>
            <button type="submit">Simpan</button>
        </form>
        <a href="dashboard.php" class="back-link">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
