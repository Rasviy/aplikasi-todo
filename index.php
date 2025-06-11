<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && $password == $user['password']) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Login gagal! Username atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@500&display=swap');

        * {
            box-sizing: border-box;
            font-family: 'Fredoka', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('img/e69a4f74b1b0fbdc70c9a6428aa4221e.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            padding: 40px;
            border-radius: 25px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: #fff;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        }

        .login-container h2 {
            margin-bottom: 10px;
            font-size: 28px;
        }

        .login-container p {
            margin-bottom: 25px;
            font-size: 15px;
            color: #f5f5f5;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            margin: 10px 0;
            border: none;
            border-radius: 14px;
            background: rgba(255,255,255,0.2);
            color: white;
            font-size: 14px;
        }

        input::placeholder {
            color: #ddd;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 20px;
            background-color: #5d3c2c;
            color: #fff;
            font-weight: bold;
            font-size: 15px;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #8d5c44;
        }

        .error {
            background: rgba(255, 0, 0, 0.2);
            color: #fff;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>LOGIN</h2>
    <p>Selamat datang di Web Rasvyy</p>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">SIGN IN</button>
    </form>
</div>

</body>
</html>
