<?php
include 'auth.php';

$user = $_SESSION['user'];
$role = $user['role'];
$user_id = $user['id'];

if ($role === 'pelaksana') {
    $query = "SELECT tasks.*, u1.username AS creator, u2.username AS assignee
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        h2, h3 {
            margin-top: 0;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .top-bar h2 {
            font-size: 22px;
        }

        .top-bar a {
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 25px;
            margin: 5px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            color: #fff;
        }

        .logout {
            background-color: #e74c3c;
        }

        .add-task {
            background-color: #3498db;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            min-width: 700px;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        th {
            background-color: rgba(255, 255, 255, 0.2);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 20px;
            text-decoration: none;
            font-size: 13px;
            margin-right: 5px;
            cursor: pointer;
            display: inline-block;
        }

        .btn-warning {
            background-color: #f1c40f;
            color: #000;
        }

        .btn-secondary {
            background-color: #7f8c8d;
            color: white;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px 10px;
            }

            table, th, td {
                font-size: 13px;
            }
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

    <h3>Daftar Tugas</h3>
    <div class="table-responsive">
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
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td>
                        <?php if ($role === 'pelaksana' && $task['assigned_to'] == $user_id): ?>
                            <a href="update_status.php?id=<?= $task['id'] ?>" class="btn btn-warning">Ubah Status</a>
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
