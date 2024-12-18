<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>

<body>rch
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">
        <main>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Thêm mới sản phẩm</h3>
                        <div class="form">
                            <form action="<?= URL_ROOT . '/productManage/add' ?>" method="POST" enctype="multipart/form-data">
                                <p class="<?= $data['cssClass'] ?>"><?= isset($data['message']) ? $data['message'] : "" ?></p>
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" id="name" name="name" required>
                                <label for="cate">Danh mục</label>
                                <select name="cateId" id="cate">
                                    <?php
                                    foreach ($data['categoryList'] as $key => $value) { ?>
                                        <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                    <?php }
                                    ?>
                                </select>
                                <label for="image">Hình ảnh 1</label>
                                <input type="file" id="image" name="image" required>
                                <label for="image">Hình ảnh 2</label>
                                <input type="file" id="image2" name="image2" required>
                                <label for="image">Hình ảnh 3</label>
                                <input type="file" id="image3" name="image3" required>
                                <label for="originalPrice">Giá gốc</label>
                                <input type="number" id="originalPrice" name="originalPrice" required>
                                <label for="promotionPrice">Giá khuyến mãi</label>
                                <input type="number" id="promotionPrice" name="promotionPrice" required>
                                <label for="qty">Số lượng</label>
                                <input type="number" id="qty" name="qty" required>


                                <label for="trademark">Thương hiệu</label>
                                <input type="text" id="trademark" name="trademark" required>
                                <label for="faceshape">Hình dạng mặt</label>
                                <input type="text" id="faceshape" name="faceshape" required>
                                <label for="material">Chất liệu</label>
                                <input type="text" id="material" name="material" required>
                                <label for="color">Màu</label>
                                <input type="text" id="color" name="color" required>
                                <label for="energyused">Năng lương sử dụng</label>
                                <input type="text" id="energyused" name="energyused" required>



                                <input type="submit" value="Lưu">
                                <a href="<?= URL_ROOT . '/productManage' ?>" class="back">Trở về</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</body>

</html>