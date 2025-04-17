<?php
include 'auth.php';

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
}
?>
<center>
<br>
<!DOCTYPE html>
<html>
<head><title>Buat Tugas</title></head>
<body>
<h2>Buat Tugas Baru</h2>
<form method="POST">
    Judul: <input name="title" required><br><br>
    Deskripsi: <textarea name="description"></textarea><br><br>
    Tugas untuk:
    <select name="assigned_to" required>
        <option value="">-- Pilih Pelaksana --</option>
        <?php while ($p = $pelaksana->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['username']) ?></option>
        <?php endwhile; ?>
    </select><br><br>
    <button type="submit">Simpan</button>
</form>
<a href="dashboard.php">Kembali</a>
</body>
</html>
        </center>