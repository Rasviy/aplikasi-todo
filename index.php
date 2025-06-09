<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE username='$username'");
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
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('img/gradient-background-3840x2160-10786.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .login-container h2 {
            margin-bottom: 10px;
            font-size: 26px;
        }

        .login-container p {
            margin-bottom: 30px;
            font-size: 16px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: none;
            border-radius: 30px;
            background: rgba(255,255,255,0.2);
            color: white;
            font-size: 14px;
        }

        input::placeholder {
            color: #ddd;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background-color: #ffc0a0;
            color: #000;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #ffae8f;
        }

        .options {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 13px;
        }

        .social-login {
            margin-top: 30px;
        }

        .social-login button {
            width: 48%;
            margin: 5px 1%;
            background: white;
            color: #333;
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            background: rgba(255, 0, 0, 0.6);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>LOGIN</h2>
    <p>SELAMAT DATANG DI WEB RASVYY</p>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">SIGN IN</button>

</div>

</body>
</html>
