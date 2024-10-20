<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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

        .signup-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .signup-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .signup-container label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            color: #555;
        }

        .signup-container input[type="text"],
        .signup-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .signup-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .signup-container input[type="submit"]:hover {
            background-color: #45a049;
        }

        .signup-container p {
            color: #777;
            font-size: 0.9em;
        }

        .signup-container p a {
            color: #4CAF50;
            text-decoration: none;
        }

        .signup-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="" method="post"> <!-- Để cùng trang xử lý, sử dụng action="" -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Sign Up">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <?php
        session_start();

        // Kiểm tra xem yêu cầu là POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Kết nối cơ sở dữ liệu
            include 'ketnoicsdl.php'; // Đảm bảo rằng đường dẫn đúng

            // Lấy dữ liệu từ form
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Kiểm tra xem tên người dùng đã tồn tại chưa
            $sql_check = "SELECT * FROM users WHERE username='$username'";
            $result_check = $conn->query($sql_check);

            if ($result_check->num_rows > 0) {
                // Nếu tên người dùng đã tồn tại, yêu cầu người dùng đăng ký lại
                echo "Tên người dùng đã tồn tại! Vui lòng chọn tên khác.";
                echo "<br><a href='signup.php'>Trở lại trang đăng ký</a>";
            } else {
                // Thêm người dùng vào cơ sở dữ liệu mà không mã hóa mật khẩu
                $sql_insert = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
                
                if ($conn->query($sql_insert) === TRUE) {
                    // Đăng ký thành công, chuyển hướng đến trang đăng nhập
                    header('Location: login.php?success=1');
                    exit; // Kết thúc script
                } else {
                    echo "Có lỗi xảy ra: " . $conn->error;
                    echo "<br><a href='signup.php'>Trở lại trang đăng ký</a>";
                }
            }

            // Đóng kết nối
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
