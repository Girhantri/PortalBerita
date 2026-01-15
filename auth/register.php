<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Register User</title>

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: Arial, sans-serif;
            }

            body {
                min-height: 100vh;
                background: #f2f2f2;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .register-box {
                background: white;
                padding: 30px;
                width: 350px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }

            .register-box h2 {
                text-align: center;
                margin-bottom: 20px;
            }

            .register-box label {
                display: block;
                margin-bottom: 5px;
                font-size: 14px;
            }

            .register-box input {
                width: 100%;
                padding: 8px;
                margin-bottom: 15px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            .register-box button {
                width: 100%;
                padding: 10px;
                background: #28a745;
                border: none;
                color: white;
                border-radius: 4px;
                cursor: pointer;
            }

            .register-box button:hover {
                background: #218838;
            }
        </style>
    </head>
        <body>
            <div class="register-box">
                <h2>Register User</h2>
                <p id="msg" style="color:red; text-align:center; display:none;"></p>
                <br><br>
                <form action="proses_register.php" method="POST">

                    <label>Username</label>
                    <input type="text" name="username" required>

                    <label>Password</label>
                    <input type="password" name="password" required>

                    <button type="submit" onclick="validasi()">Daftar</button>
                </form>
            </div>
            <script src="validasi.js"></script>
        </body>
</html>
