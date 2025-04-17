<?php
include 'auth.php';

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
}
?>
<center>
    <br>
<!DOCTYPE html>
<html>
<head><title>Edit Tugas</title></head>
<body>
<h2>Edit Tugas</h2>
<form method="POST">
    Judul: <input name="title" value="<?= htmlspecialchars($task['title']) ?>" required><br><br>
    Deskripsi: <textarea name="description"><?= htmlspecialchars($task['description']) ?></textarea><br><br>
    Tugas untuk:
    <select name="assigned_to" required>
        <?php while ($p = $pelaksana->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>" <?= $task['assigned_to'] == $p['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['username']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>
    <button type="submit">Update</button>
</form>
<a href="dashboard.php">Kembali</a>
</body>
</html>
</center>