<?php
include 'auth.php';

if ($user['role'] === 'pelaksana') {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];
$conn->query("DELETE FROM tasks WHERE id=$id");
header("Location: dashboard.php");
