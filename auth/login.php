<?php
    session_start();
    $error = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 30px;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-box label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .login-box input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .login-box button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-box button:hover {
            background: #0056b3;
        }

        p a {
            color: blue;
            text-decoration: none;
            font-weight: bold;
            margin-left: 5px;
        }
        p a:hover {
            text-decoration: underline;
        }

    </style>

</head>
<body>

<div class="login-box">
    <h2>Login</h2>
    <p id="msg" style="color:red; text-align:center; display:none;"></p>
        <p id="msg" style="color:red; text-align:center;">
        <?= $error ?>
    </p>
    <br><br>
    <form action="proses_login.php" method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" onclick="validasi()">Login</button>
        <br><br>
        <p>
            Belum punya akun?
            <a href="register.php">Register</a>
        </p>
    </form>
</div>
<script src="validasi.js"></script>
</body>
</html>
