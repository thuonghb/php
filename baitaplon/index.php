<?php
include 'php/ketnoicsdl.php'; // Kết nối cơ sở dữ liệu
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
                <img src="img/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo number_format($row['price']); ?> VND</p>
            </div>
        <?php endwhile; ?>
    </div>

    <h2>Hàng Bán Chạy</h2>
    <div class="product-list">
        <?php while ($row = $result_banchay->fetch_assoc()): ?>
            <div class="product">
                <img src="img/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo number_format($row['price']); ?> VND</p>
            </div>
        <?php endwhile; ?>
    </div>

    <h2>Hàng Giảm Giá</h2>
    <div class="product-list">
        <?php while ($row = $result_giamgia->fetch_assoc()): ?>
            <div class="product">
                <img src="img/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo number_format($row['price']); ?> VND</p>
            </div>
        <?php endwhile; ?>
    </div>
</main>
