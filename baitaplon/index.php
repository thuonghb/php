<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        /* Thiết lập cho danh sách sản phẩm */
.product-list {
    display: flex;
    flex-wrap: wrap; /* Để sản phẩm có thể xuống dòng */
    margin: 20px 0; /* Khoảng cách giữa các phần danh sách */
}

/* Thiết lập cho từng sản phẩm */
.product {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px; /* Bo tròn các góc */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Đổ bóng cho sản phẩm */
    margin: 10px; /* Khoảng cách giữa các sản phẩm */
    padding: 15px; /* Padding bên trong sản phẩm */
    text-align: center; /* Căn giữa nội dung */
    width: calc(25% - 20px); /* Mỗi sản phẩm chiếm 1/4 chiều rộng */
    transition: transform 0.2s; /* Hiệu ứng chuyển động */
}

/* Hiệu ứng khi hover */
.product:hover {
    transform: translateY(-5px); /* Nâng lên một chút khi hover */
}

/* Thiết lập cho hình ảnh sản phẩm */
.product img {
    max-width: 100%; /* Hình ảnh chiếm toàn bộ chiều rộng sản phẩm */
    height: auto; /* Chiều cao tự động */
    border-radius: 5px; /* Bo tròn các góc cho hình ảnh */
}

/* Thiết lập cho tiêu đề sản phẩm */
.product h3 {
    font-size: 1.2em; /* Kích thước font tiêu đề */
    margin: 10px 0; /* Khoảng cách trên dưới */
}

/* Thiết lập cho giá sản phẩm */
.product p {
    font-size: 1.1em; /* Kích thước font cho giá */
    color: #d9534f; /* Màu sắc cho giá */
    font-weight: bold; /* In đậm */
}

    </style>
</body>
</html>

<?php
include 'ketnoicsdl.php'; // Kết nối cơ sở dữ liệu
// Truy vấn lấy sản phẩm theo loại
$sql_moi = "SELECT id, name, price, image FROM products WHERE category='moi'";
$sql_banchay = "SELECT id, name, price, image FROM products WHERE category='banchay'";
$sql_giamgia = "SELECT id, name, price, image FROM products WHERE category='giamgia'";

$result_moi = $conn->query($sql_moi);
$result_banchay = $conn->query($sql_banchay);
$result_giamgia = $conn->query($sql_giamgia);
?>
<main>
    <h2>Hàng Mới</h2>
    <div class="product-list">
        <?php while ($row = $result_moi->fetch_assoc()): ?>
            <div class="product">
                <img src="dong ho/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo number_format($row['price']); ?> VND</p>
            </div>
        <?php endwhile; ?>
    </div>

    <h2>Hàng Bán Chạy</h2>
    <div class="product-list">
        <?php while ($row = $result_banchay->fetch_assoc()): ?>
            <div class="product">
                <img src="dong ho/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo number_format($row['price']); ?> VND</p>
            </div>
        <?php endwhile; ?>
    </div>

    <h2>Hàng Giảm Giá</h2>
    <div class="product-list">
        <?php while ($row = $result_giamgia->fetch_assoc()): ?>
            <div class="product">
                <img src="dong ho/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo number_format($row['price']); ?> VND</p>
            </div>
        <?php endwhile; ?>
    </div>
</main>
