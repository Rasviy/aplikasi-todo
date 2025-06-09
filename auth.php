<?php
session_start();
include 'config.php';


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}


$user_id = $_SESSION['user'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


if (!$user) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
