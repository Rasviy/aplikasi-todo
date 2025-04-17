<?php
include 'auth.php';


$user = $_SESSION['user'];
$role = $user['role'];
$user_id = $user['id'];


if ($role === 'pelaksana') {
    $query = "SELECT tasks.*, u.username AS creator 
              FROM tasks 
              JOIN users u ON tasks.created_by = u.id 
              WHERE tasks.assigned_to = $user_id";
} else {
    $query = "SELECT tasks.*, u1.username AS creator, u2.username AS assignee 
              FROM tasks 
              LEFT JOIN users u1 ON tasks.created_by = u1.id 
              LEFT JOIN users u2 ON tasks.assigned_to = u2.id 
              ORDER BY tasks.created_at DESC";
}

$result = $conn->query($query);
?>

<center>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Halo, <?= htmlspecialchars($user['username']) ?> (<?= $role ?>)</h2>
    <a href="logout.php">Logout</a>
    <?php if ($role !== 'pelaksana'): ?>
        | <a href="add_task.php">+ Buat Tugas</a>
    <?php endif; ?>
    <hr>

    <h3>Daftar Tugas</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Dibuat oleh</th>
            <th>Ditugaskan ke</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php while ($task = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td><?= htmlspecialchars($task['description']) ?></td>
                <td><?= htmlspecialchars($task['creator'] ?? '-') ?></td>
                <td><?= htmlspecialchars($task['assignee'] ?? '-') ?></td>
                <td><?= htmlspecialchars($task['status']) ?></td>
                <td>
                    <?php if ($role === 'pelaksana' && $task['assigned_to'] == $user_id): ?>
                        <a href="update_status.php">Ubah Status</a>
                    <?php elseif ($role !== 'pelaksana'): ?>
                        <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a> | 
                        <a href="delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Yakin hapus tugas ini?')">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
</center>
