<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ban_dong_ho";

// Kết nối MySQL với PHP
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>