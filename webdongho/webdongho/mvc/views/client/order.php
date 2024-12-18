<?php require APP_ROOT . '/views/client/inc/head.php'; ?>

<body>
  <?php
  $cart = new cart();
  if (!isset($_SESSION['cart'])) {
    $total = (isset($cart->getTotalQuantitycart()['total']) ? $cart->getTotalQuantitycart()['total'] : 0);
  } else {
    $total = $cart->getTotal();
  }

  $category = $this->model("categoryModel");
  $result = $category->getAllClient();
  $listCategory = $result->fetch_all(MYSQLI_ASSOC);
  ?>
  <nav class="navbar">
    <div class="logo">NHÓM 10 STORE</div>
    <div class="search-container">
      <form action="<?= URL_ROOT ?>/product/search" method="get">
        <input type="text" class="search" placeholder="Tìm kiếm.." name="keyword">
        <button type="submit"><i class="fa fa-search"></i></button>
      </form>
    </div>
    <ul class="nav-links">
      <input type="checkbox" id="checkbox_toggle" />
      <label for="checkbox_toggle" class="hamburger">&#9776;</label>
      <div class="menu">
        <li><a href="<?= URL_ROOT ?>">Trang chủ <i class="fa fa-home"></i></a></li>
        <li class="cate">
          <a href="#">Danh mục <i class="fa fa-list-ul"></i></a>
          <ul class="sub-menu">
            <?php
            foreach ($listCategory as $key) { ?>
              <li><a href="<?= URL_ROOT . '/product/category/' . $key['id'] ?>?page=1"><?= $key['name'] ?></a></li>
            <?php }
            ?>
          </ul>
        </li>
        <li><a href="<?= URL_ROOT . "/blog" ?>">Blog <i class="fa fa-book"></i></a></li>

        <?php
        if (isset($_SESSION['user_id'])) { ?>
          <li class="cate menu-active">
            <a href="#"><?= $_SESSION['user_name'] ?> <i class="fa fa-user-circle"></i></a>
            <ul class="sub-menu">
              <li><a href="<?= URL_ROOT . "/user/info" ?>">Thông tin tài khoản <i class="fa fa-user"></i></a></li>
              <li><a href="<?= URL_ROOT . "/product/favorite" ?>">Sản phẩm yêu thích <i class="fa fa-heart"></i></a></li>
              <li><a href="<?= URL_ROOT . "/product/viewed" ?>">Đã xem <i class="fa fa-history"></i></a></li>
              <li><a href="<?= URL_ROOT . "/order/checkout" ?>">Đơn hàng của tôi <i class="fa fa-list-alt"></i></a></li>
              <li><a href="<?= URL_ROOT . "/user/logout" ?>">Đăng xuất <i class="fa fa-sign-out"></i></a></li>
            </ul>
          </li>
        <?php  } else { ?>
          <li><a href="<?= URL_ROOT . "/user/register" ?>">Đăng ký <i class="fa fa-pencil-square"></i></a></li>
          <li><a href="<?= URL_ROOT . "/user/login" ?>">Đăng nhập <i class="fa fa-sign-in"></i></a></li>
        <?php  }
        ?>
        <li><a href="<?= URL_ROOT . "/cart/checkout" ?>" id="bag">Giỏ hàng <i class="fa fa-shopping-bag"></i> (<?= is_null($total) ? 0 : $total ?>)</a></li>
      </div>
    </ul>
  </nav>
  <div class="banner">

  </div>
  <div class="title">Đơn đặt hàng của tôi</div>
  <table id="table">
    <?php
    $count = 0;
    if (count($data['orderList']) > 0) { ?>
      <tr>
        <th>STT</th>
        <th>Mã HD</th>
        <th>Ngày đặt</th>
        <th>Ngày giao</th>
        <th>Giảm giá</th>
        <th>Tổng</th>
        <th>Tình trạng</th>
        <th>Phương thức thanh toán</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
      </tr>
      <?php foreach ($data['orderList'] as $key => $value) {
      ?>
        <tr>
          <td><?= ++$count ?></td>
          <td><?= $value['id'] ?></td>
          <td><?= date("d/m/Y", strtotime($value['createdDate'])) ?></td>
          <?php
          if (date("d/m/Y", strtotime($value['receivedDate']))) { ?>
            <?php if ($value['status'] == "received") { ?>
              <td><?= date("d/m/Y", strtotime($value['receivedDate'])) ?></td>
            <?php } else if ($value['status'] == "delivery") { ?>
              <td><?= date("d/m/Y", strtotime($value['receivedDate'] . ' + 3 days')) ?> Dự kiến</td>
            <?php } else { ?>
              <td>Chờ xác nhận...</td>
            <?php } ?>
          <?php } else { ?>
            <td>3 ngày sau khi đơn hàng được xác nhận</td>
          <?php }
          ?>
          <?php
          if ($value['discount'] > 0) { ?>
            <td>-<?= $value['discount'] ?>%</td>
          <?php   } else { ?>
            <td>Không có</td>
          <?php  }
          ?>
          <?php
          if ($value['discount'] > 0) { ?>
            <td><del> <?= number_format($value['total'], 0, '', ',') ?>₫</del> <?= number_format($value['total']-($value['total']/100*($value['discount'])), 0, '', ',') ?>₫ 
          <?php   } else { ?>
          <td><?= number_format($value['total'], 0, '', ',') ?>₫ 
          <?php  }
          ?>
        </td>
          <?php
          if ($value['status'] == "delivery") { ?>
            <td>Đang giao
              <a href="<?= URL_ROOT . '/order/received/' . $value['id'] ?>">(Click vào nếu đã nhận được hàng)</a>
            </td>
          <?php } else if ($value['status'] == "processing") { ?>
            <td>Chưa xác nhận</td>
          <?php } else if ($value['status'] == "processed") { ?>
            <td>Đã xác nhận</td>
          <?php } else if ($value['status'] == "received") { ?>
            <td>Hoàn thành</td>
          <?php } else if ($value['status'] == "cancel") { ?>
            <td>Đã hủy</td>
          <?php }
          ?>
          <td><?= $value['paymentMethod'] ?></td>
          <?php
          if ($value['paymentStatus']) { ?>
            <td>Đã thanh toán</td>
          <?php } else if ($value['status'] != "cancel") { ?>
            <td>Chưa thanh toán
            </td>
          <?php } else { ?>
            <td>Không thể thanh toán</td>
          <?php }
          ?>
          <td>
            <a href="<?= URL_ROOT . '/order/detail/' . $value['id'] ?>" class="cart-btn">Chi tiết</a>
          <?php
            if ($value['paymentMethod'] == "COD" && $value['status'] == "processing") { ?>
              <a href="<?= URL_ROOT . '/order/cancel/' . $value['id'] ?>" class="cart-btn cancel">Hủy đơn</a>
            <?php  }else { ?>
             Không thể hủy
             <?php }
            ?>
          </td>
        </tr>
      <?php }
      ?>
    <?php } else {  ?>
      <h3>Chưa có đơn đặt hàng...</h3>
    <?php }  ?>
  </table>
  <?php require APP_ROOT . '/views/client/inc/footer.php'; ?>
</body>

</html>