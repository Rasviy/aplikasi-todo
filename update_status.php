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
