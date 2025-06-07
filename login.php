<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $res->fetch_assoc();

    // Langsung bandingkan password biasa (plaintext)
    if ($user && $password == $user['password']) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Login gagal!";
    }
}
?>
<center>
    <br><br>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    Username: <input name="username"><br><br>
    Password: <input name="password" type="password"><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
</center>
