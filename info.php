<?php
include_once 'lib/session.php';
include_once 'classes/product.php';
include_once 'classes/cart.php';
include 'classes/user.php';

$user = new user();
$userInfo = $user->get();

$cart = new cart();
$totalQty = $cart->getTotalQtyByUserId();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://use.fontawesome.com/2145adbb48.js"></script>
    <script src="https://kit.fontawesome.com/a42aeb5b72.js" crossorigin="anonymous"></script>
    <title>Trang chủ</title>
    <style>
        table,
        tr,
        td {
            border: none;
            /* background-color: #fff; */
            margin: 0;
            padding: 0;
            text-align: left;
            font-size: 18px;
        }

        td {
            margin: 10px;
            padding: 10px;
        }

        .container-info {
            width: 60%;
            display: flex;
            justify-content: center;
            flex: 1;
        }
    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script>
        $(function() {
            $('.fadein img:gt(0)').hide();
            setInterval(function() {
                $('.fadein :first-child').fadeOut().next('img').fadeIn().end().appendTo('.fadein');
            }, 5000);
        });
    </script>
</head>

<body>
    <nav>
        <label class="logo">HKT-SHOP.com</label>
        <ul>
            <li><a href="index.php">Trang chủ</a></li>
            <li><a href="productList.php">Sản phẩm</a></li>

            <li><a href="order.php" id="order">Đơn hàng</a></li>
            <li>
                <a href="checkout.php">
                    Giỏ hàng
                    <i class="fa fa-shopping-bag"></i>
                    <sup class="sumItem">
                        <?= ($totalQty['total']) ? $totalQty['total'] : "0" ?>
                    </sup>
                </a>
            </li>
            <?php
            if (isset($_SESSION['user']) && $_SESSION['user']) { ?>
                <li><a href="info.php" id="signin" class="active">Thông tin cá nhân</a></li>
                <li><a href="logout.php" id="signin">Đăng xuất</a></li>
            <?php } else { ?>
                <li><a href="register.php" id="signup">Đăng ký</a></li>
                <li><a href="login.php" id="signin">Đăng nhập</a></li>
            <?php } ?>
        </ul>
    </nav>
    <section class="banner">
        <div class="fadein">
            <?php
            // display images from directory
            // directory path
            $dir = "./images/slider/";

            $scan_dir = scandir($dir);
            foreach ($scan_dir as $img) :
                if (in_array($img, array('.', '..')))
                    continue;
            ?>
                <img src="<?php echo $dir . $img ?>" alt="<?php echo $img ?>">
            <?php endforeach; ?>
        </div>
    </section>
    <div class="featuredProducts">
        <h1>Thông tin cá nhân</h1>
    </div>
    <div class="container-single">
        <div class="container-info">
            <div class="image-info">
                <img src="./images/avt.png" alt="">
            </div>
            <div class="info">
                <table>
                    <tr>
                        <td>Họ và tên: </td>
                        <td><?= $userInfo['fullname'] ?></td>
                    </tr>
                    <tr>
                        <td>Email: </td>
                        <td><?= $userInfo['email'] ?></td>
                    </tr>
                    <tr>
                        <td>Ngày sinh: </td>
                        <td><?= $userInfo['dob'] ?></td>
                    </tr>
                    <tr>
                        <td>Địa chỉ: </td>
                        <td><?= $userInfo['address'] ?></td>
                    </tr>
                    <tr>
                        <td>Chức vụ: </td>
                        <td><?php if ($userInfo['role_id'] == 1) {
                                echo "Admin";
                            } else echo "Khách hàng" ?></td>
                    </tr>
                </table>
                <?php if ($userInfo['role_id'] == 1) {
                    echo '<div><a href="./admin/index.php">Chuyển sang trang Admin</a></div>';
                } else echo '<div><a href="edit_info.php">Chỉnh sửa thông tin cá nhân</a></div>'; ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="social">
            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
            <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
        </div>
        <ul class="list">
            <li>
                <a href="./">Trang Chủ</a>
            </li>
            <li>
                <a href="productList.php">Sản Phẩm</a>
            </li>
        </ul>
        <p class="copyright">copy by HKT-SHOP.com 2022</p>
    </footer>
</body>

</html>