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
        <form action="" method="post"> <!-- Để cùng trang xử lý, sử dụng action="" -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        <?php
        session_start();

        // Kiểm tra xem yêu cầu là POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kết nối cơ sở dữ liệu
            include 'ketnoicsdl.php'; // Đảm bảo rằng đường dẫn đúng

            // Lấy dữ liệu từ form
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Truy vấn cơ sở dữ liệu để kiểm tra tên người dùng và mật khẩu
            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($user['password'] == $password) { // So sánh mật khẩu
                    $_SESSION['user'] = $user['username']; // Lưu tên người dùng vào phiên
                    header('Location: index.php'); // Chuyển hướng đến trang chính
                    exit; // Kết thúc script
                } else {
                    echo "Sai mật khẩu!";
                }
            } else {
                echo "Tài khoản không tồn tại!";
            }

            // Đóng kết nối
            $conn->close();
        }

        // Hiển thị thông báo thành công nếu có
        if (isset($_GET['success'])) {
            echo "<p>Đăng ký thành công! Bạn có thể đăng nhập ngay.</p>";
        }
        ?>
    </div>
</body>
</html>
