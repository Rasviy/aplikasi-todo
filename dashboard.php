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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Marker Felt', 'Comic Sans MS', cursive;
        }

        body {
            background: url('img/e69a4f74b1b0fbdc70c9a6428aa4221e.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 40px 10px;
        }

        .task-wrapper {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 30px 20px;
            width: 100%;
            max-width: 700px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            color: #fff;
            font-size: 24px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            color: #fff;
        }

        .top-bar a {
            background-color: #5d3c2c;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
            margin-left: 8px;
        }

        .task-item {
            background-color: #8d5c44;
            color: #fff;
            padding: 16px;
            border-radius: 15px;
            margin-bottom: 15px;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .task-item.done {
            background-color: #bfaea4;
            text-decoration: line-through;
        }

        .task-item .title {
            font-size: 18px;
            font-weight: bold;
        }

        .task-item .description {
            font-size: 14px;
            margin-top: 5px;
        }

        .task-item .info {
            font-size: 12px;
            margin-top: 8px;
            color: #ddd;
        }

        .btn-group {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .btn-group a {
            background-color: #fff;
            color: #333;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .btn-group {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="task-wrapper">
        <div class="top-bar">
            <h2>Halo, <?= htmlspecialchars($user['username']) ?> (<?= $role ?>)</h2>
            <div>
                <a href="logout.php">Logout</a>
                <?php if ($role !== 'pelaksana'): ?>
                    <a href="add_task.php">+ Buat Tugas</a>
                <?php endif; ?>
            </div>
        </div>

        <?php while ($task = $result->fetch_assoc()): ?>
            <div class="task-item <?= $task['status'] === 'selesai' ? 'done' : '' ?>">
                <div class="title"><?= htmlspecialchars($task['title']) ?></div>
                <div class="description"><?= htmlspecialchars($task['description']) ?></div>
                <div class="info">
                    Dibuat oleh: <?= htmlspecialchars($task['creator'] ?? '-') ?> | 
                    Ditugaskan ke: <?= htmlspecialchars($task['assignee'] ?? '-') ?> | 
                    Status: <?= htmlspecialchars($task['status']) ?>
                </div>
                <div class="btn-group">
                    <?php if ($role === 'pelaksana' && $task['assigned_to'] == $user_id): ?>
                        <a href="update_status.php?id=<?= $task['id'] ?>">Ubah Status</a>
                    <?php elseif ($role !== 'pelaksana'): ?>
                        <a href="edit_task.php?id=<?= $task['id'] ?>">Edit</a>
                        <a href="delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Yakin hapus tugas ini?')">Hapus</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
