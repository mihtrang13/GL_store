<?php
$dssp = get_all_sp();
$dsdm = get_all_dm();

?>


<!-- Navbar Start -->
<div class="container-fluid">
    <div class="row border-top px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0">Danh Mục</h6>
                <i class="fa fa-angle-down text-dark"></i>
            </a>
            <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
                <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link" data-toggle="dropdown">Áo sơ mi <i class="fa fa-angle-down float-right mt-1"></i></a>
                        <div class="dropdown-menu position-absolute bg-secondary border-0 rounded-0 w-100 m-0">
                            <a href="" class="dropdown-item">Áo nam</a>
                            <a href="" class="dropdown-item">Áo nữ</a>
                            <a href="" class="dropdown-item">Áo trẻ em</a>
                        </div>
                    </div> -->
                    <?php foreach ($dsdm as $dm) : ?>
                        <a href="" class="nav-item nav-link"><?= $dm['name'] ?></a>
                    <?php endforeach; ?>

                </div>
            </nav>
        </div>
        <div class="col-lg-9">
            <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                <a href="" class="text-decoration-none d-block d-lg-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto py-0">
                        <a href="index.php" class="nav-item nav-link active">Trang Chủ</a>
                        <a href="index.php?act=shop" class="nav-item nav-link">Cửa Hàng</a>
                        <a href="index.php?act=lienhe" class="nav-item nav-link">Liên Hệ</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Khác</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="index.php?act=cart" class="dropdown-item">Giỏ Hàng</a>
                                <a href="index.php?act=thanhtoan" class="dropdown-item">Thanh Toán</a>
                            </div>
                        </div>

                    </div>
                    <div class="navbar-nav ml-auto py-0">
                        <?php
                        if (isset($_SESSION['user']) && ($_SESSION['user'] > 0)) {
                            echo ' <div class="nav-item dropdown" style= "display: flex; align-items: center;">
                                            <a href="#" class="nav-item nav-link" style=" padding: 10px 3px">' . $_SESSION['user']['username'] . '</a>
                                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" style=" padding: 10px 3px" > 
                                            <img src="layout/img/user.jpg" alt="" style=" width: 40px; height: 40px" >
                                            </a>
                                            <div class="dropdown-menu rounded-0 m-0">
                                                <a href="index.php?act=updateUser" class="dropdown-item">Cập Nhật</a>
                                                <a href="index.php?act=lichsu" class="dropdown-item">Lịch Sử Đặt Hàng </a>
                                                <a href="index.php?act=dangxuat" class="dropdown-item">Đăng Xuất</a>
                                              
                                            </div>          
                                        </div>';
                        } else {
                            echo ' <a href="index.php?act=dangnhap" class="nav-item nav-link">Đăng Nhập</a>
                                <a href="index.php?act=dangky" class="nav-item nav-link">Đăng Ký</a>';
                        }
                        ?>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- Navbar End -->


<!-- Page Header -->
<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 100px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3 text-h1">Giỏ Hàng</h1>

    </div>
</div>
<!-- Page Header End -->


<!-- Cart Start -->
<div class="container-fluid pt-5 yp-51">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">


            <table class="table table-bordered text-center mb-0">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php
                    $total = 0;
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $id => $item) {
                            $subtotal = $item['price'] * $item['quantity']; // Tính tổng tiền cho từng sản phẩm
                            $total += $subtotal;
                            echo ' 
                            <tr>
                                <td class="align-middle"><img src="layout/img/' . $item['img'] . '" alt="" style="width: 50px;"> ' . $item['name'] . '</td>
                                <td class="align-middle">' . number_format($item['price'], 0, '', '.') . 'đ</td>
                                <td class="align-middle">
                                    <form action="index.php?act=updateCart" method="post">
                                        <input type="hidden" name="id" value="' . $id . '">
                                        <div class="input-group quantity mx-auto" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-minus" type="submit" name="subtract">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input type="text" min="1"  class="form-control form-control-sm bg-secondary text-center" value="' . $item['quantity'] . '">
                                            <div class="input-group-btn">
                                                <button class="btn btn-sm btn-primary btn-plus" type="submit" name="add">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                                <td class="align-middle">' . number_format($subtotal, 0, '', '.') . 'đ</td>
                                <td class="align-middle"><button class="btn btn-sm btn-primary">
                                <a class="fa fa-times" href="index.php?act=delCart&id=' . $id . '"</button></td>
                        </tr>';
                        }
                    }
                    ?>



                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <form class="mb-5" action="" method="post">
                <div class="input-group">
                    <input type="text" name='discount_code' class="form-control p-4" placeholder="Nhập mã giảm giá">
                    <div class="input-group-append">
                        <input type="submit" name="submit" class="btn btn-primary" value='Nhập'>
                    </div>
                </div>
            </form>
            <?php
            // Kiểm tra xem nút gửi đã được nhấn hay chưa
            if (isset($_POST["submit"])) {
                // Nhận mã giảm giá từ form
                $discount_code = $_POST["discount_code"];

                // Kiểm tra mã giảm giá có hợp lệ hay không
                if ($discount_code == "MAGIAMGIA123") {
                    // Mã giảm giá hợp lệ, thực hiện các hành động cần thiết (ví dụ: giảm giá số tiền)
                    $discount_amount = 10; // Số tiền giảm giá
                    echo "<p class='sale_product'>Áp dụng mã giảm giá thành công. Giảm giá $discount_amount%</p>";
                } else {
                    // Mã giảm giá không hợp lệ
                    echo "<p class='sale_product'>Mã giảm giá không hợp lệ. Vui lòng thử lại.</p>";
                }
            }
            ?>
            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Tổng đơn hàng</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 pt-1">
                        <h6 class="font-weight-medium">Giá</h6>
                        <h6 class="font-weight-medium">
                            <div><?php echo number_format($total, 0, '', '.') ?>đ</div>
                        </h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Giảm giá</h6>
                        <h6 class="font-weight-medium">
                            <?php
                            if (isset($discount_code) && $discount_code == "MAGIAMGIA123") {
                                $phantram = '10%';
                                echo $phantram; // Hiển thị giá sau khi áp dụng triết khấu
                            } else {
                                $phantram = '0%';
                                echo $phantram;
                            }
                            ?>

                        </h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Phí ship</h6>
                        <h6 class="font-weight-medium">0</h6>
                    </div>

                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Tổng</h5>
                        <h5 class="font-weight-bold">



                            <?php
                            $tilesTrietKhau = 0.1;

                            // Hiển thị giá gốc

                            if (isset($discount_code) && $discount_code == 'MAGIAMGIA123') {
                                $_SESSION['discount_code'] = $discount_code;

                                // Assuming $total and $tilesTrietKhau are previously defined
                                $gia = $total - ($total * $tilesTrietKhau);
                                $_SESSION['gia'] = $gia;

                                echo number_format($gia, 0, '', '.'); // Display the discounted price
                            } else {
                                // If no discount code or an invalid code, display the original price
                                $_SESSION['discount_code'] = null;
                                $_SESSION['gia'] = $total;
                                echo number_format($total, 0, '', '.') . "đ";
                            }
                            ?>
                    </div>
                    </h5>
                    
                </div>
                <?php

                if (isset($_SESSION['user']) && ($_SESSION['user'] != '')) {
                    if (isset($id)) {
                        if (isset($_SESSION['cart'][$id])) {
                            echo '<a class="dongydathang" href="index.php?act=thanhtoan"><button class="btn btn-block btn-primary my-3 py-3">ĐỒNG Ý ĐẶT HÀNG</button></a>';
                        } else {
                            echo '<a class="dongydathang" href="index.php?act=cart"><button disabled  class="btn btn-block btn-primary my-3 py-3">ĐỒNG Ý ĐẶT HÀNG</button></a>';
                        }
                    } else {
                        echo '<a class="dongydathang" href="index.php?act=cart"><button disabled  class="btn btn-block btn-primary my-3 py-3">ĐỒNG Ý ĐẶT HÀNG</button></a>';
                    }
                } else {
                    
                    echo '<a class="dongydathang" href="index.php?act=cart"><button disabled  class="btn btn-block btn-primary my-3 py-3">ĐỒNG Ý ĐẶT HÀNG</button></a>';
                    echo '<div style="text-align: center; padding: 10px ">Vui lòng đăng nhập để mua hàng, <a href = "index.php?act=dangnhap"> Đăng Nhập</a></div>';
                }
                ?>

            </div>

        </div>
    </div>
</div>
<!-- Cart End -->