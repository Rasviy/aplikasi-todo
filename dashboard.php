<?php
include 'auth.php';

$user = $_SESSION['user'];
$role = $user['role'];
$user_id = $user['id'];

if ($role === 'pelaksana') {
    $query = "SELECT tasks.*, 
                     u1.username AS creator,
                     u2.username AS assignee
              FROM tasks 
              JOIN users u1 ON tasks.created_by = u1.id 
              JOIN users u2 ON tasks.assigned_to = u2.id
              WHERE tasks.assigned_to = $user_id";
} else {
    $query = "SELECT tasks.*, u1.username AS creator, u2.username AS assignee
              FROM tasks 
              LEFT JOIN users u1 ON tasks.created_by = u1.id 
              LEFT JOIN users u2 ON tasks.assigned_to = u2.id 
              ORDER BY tasks.id DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Tugas</title>
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
            max-width: 1100px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        h2 {
            margin-bottom: 10px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar a {
            text-decoration: none;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            margin-left: 10px;
            font-size: 14px;
        }

        .logout {
            background-color: #dc3545;
        }

        .add-task {
            background-color: #007bff;
        }

        .card {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color:rgb(72, 84, 97);
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h2>Halo, <?= htmlspecialchars($user['username']) ?> (<?= $role ?>)</h2>
            <div>
                <a href="logout.php" class="logout">Logout</a>
                <?php if ($role !== 'pelaksana'): ?>
                    <a href="add_task.php" class="add-task">+ Buat Tugas</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <h3>Daftar Tugas</h3>
            <table>
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
                        <!-- <td><?= htmlspecialchars($task['assigned_to'] ?? '-') ?></td> -->
                        <td><?= htmlspecialchars($task['status']) ?></td>
                        <td>
                            <?php if ($role === 'pelaksana' && $task['assigned_to'] == $user_id): ?>
                                <a href="update_status.php?id=<?= $task['id'] ?>" class="btn btn-warning">Ubah Status</a><br>
                            <?php elseif ($role !== 'pelaksana'): ?>
                                <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-secondary">Edit</a>
                                <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus tugas ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
