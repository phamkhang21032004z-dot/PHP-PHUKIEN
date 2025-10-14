<?php
include 'classes/user.php';
$user = new user();
$userInfo = $user->get();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $result = $user->update($_POST);
    if ($result) {
        echo '<script type="text/javascript">alert("Cập nhật thông tin cá nhân thành công!"); history.back();</script>';
        header("Location:./info.php");
    } else {
        echo '<script type="text/javascript">alert("Cập nhật thông tin cá nhân thất bại!"); history.back();</script>';
        header("Location:./info.php");
    }
}
?>

<?php
include_once 'lib/session.php';
include_once 'classes/product.php';
include_once 'classes/cart.php';

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
    <title>Cập nhật thông tin cá nhân</title>
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
        <h1>Chỉnh sửa thông tin cá nhân</b></h1>
    </div>
    <div class="container-single">
        
        <div class="login">
            <form action="edit_info.php" method="post" class="form-login">
                <label for="fullName">Họ tên</label>
                <input type="text" id="fullName" value="<?= $userInfo['fullname'] ?>" name="fullName" placeholder="Họ tên..." required>

                <label for="email">Email</label>
                <p class="error"><?= !empty($result) ? $result : '' ?></p>
                <input type="email" id="email" value="<?= $userInfo['email'] ?>" name="email" placeholder="Email..." required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">

                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Mật khẩu..." required>

                <label for="repassword">Nhập lại mật khẩu</label>
                <input type="password" id="repassword" name="repassword" required placeholder="Nhập lại mật khẩu..." oninput="check(this)">

                <label for="address">Địa chỉ</label>
                <textarea name="address" id="address" cols="30" rows="5" required><?= $userInfo['address'] ?></textarea>

                <label for="dob">Ngày sinh</label>
                <input type="date" value="<?= $userInfo['dob'] ?>" name="dob" id="dob" required>

                <div>
                    <input type="submit" value="Cập nhật" name="submit">
                    <button><a href="./info.php">Hủy bỏ</a></button>
                </div>
            </form>
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
<script language='javascript' type='text/javascript'>
    function check(input) {
        if (input.value != document.getElementById('password').value) {
            input.setCustomValidity('Password Must be Matching.');
        } else {
            input.setCustomValidity('');
        }
    }
</script>

</html>