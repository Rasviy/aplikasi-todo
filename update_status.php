<?php
include 'auth.php';

if ($user['role'] === 'pelaksana') {
    header("Location: dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND assigned_to = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

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
<br><br>
<center>
<!DOCTYPE html>
<html>
<head>
    <title>Update Status</title>
</head>
<body>
    <h2>Update Status Tugas</h2>
    <form method="POST">
        <!-- <p><strong>Judul:</strong> <?= htmlspecialchars($task['title']) ?></p> -->
        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="">-- Pilih Status --</option>
            <option value="selesai">Selesai</option>
            <option value="ditolak">Ditolak</option>
            <option value="tidak dikerjakan">Tidak Dikerjakan</option>
        </select><br><br>
        <button type="submit">Simpan</button>
    </form>
    <br>
    <a href="dashboard.php">Kembali</a>
</body>
</html>
</center>
