<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            color: #555;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .login-container p {
            color: #777;
            font-size: 0.9em;
        }

        .login-container p a {
            color: #4CAF50;
            text-decoration: none;
        }

        .login-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="#">Sign up</a></p>
        <?php
        // Kiểm tra nếu người dùng đã gửi form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy giá trị từ form
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Tạo tài khoản mẫu (trong thực tế, thông tin này lấy từ cơ sở dữ liệu)
            $valid_username = 'admin';
            $valid_password = 'password123';

            // Kiểm tra thông tin đăng nhập
            if ($username === $valid_username && $password === $valid_password) {
                $_SESSION['user'] = $user['username'];
                header('Location: index.php');
                //echo 'Login successful! Welcome, ' . htmlspecialchars($username);
            } else {
                echo 'Invalid username or password.';
            }
        }
        ?>
    </div>
    <!-- <?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'php/db_connection.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header('Location: index.php');
        } else {
            echo "Sai mật khẩu!";123
        }
    } else {
        echo "Tài khoản không tồn tại!";
    }

    $conn->close();
}
?> -->

</body>
</html>
