<?php
session_start();
ob_start();

require "model/connect.php";
require "model/product.php";
require "model/user.php";
include "model/order.php";
include "model/category.php";


include "view/header.php";

if (isset($_GET['act'])) {
    switch ($_GET['act']) {

        case 'order_detail':
            if (isset($_GET['id'])) {
                $orderId = $_GET['id'];
                $order_detail = getOrderDetails($orderId);
                $orders = getAllOrders();
                // Kiểm tra xem $order_detail tồn tại và có trạng thái là 4 không

                if (isset($order) && $order['is_paid'] == 4) {
                    if (isset($_POST['thaydoi']) && ($_POST['thaydoi'])) {
                        $id = $_POST['id'];
                        $status = $_POST['status'];
                        updateOrder($id, $status);
                    } else {
                        $thongbao = "đơn hàng đang vẫn chuyển không thể hủy đơn hàng";
                    }
                }
            }
            include 'view/order_detail.php';


            break;
        case 'lichsu':
            $lsdh = all_order($_SESSION['user']);
            
            include 'view/lichsu.php';
            break;
        case "binhluan":
            if (isset($_POST['submit'])) {
                $_SESSION['user'];
                $productId = isset($_POST['id']) ? (int) $_POST['id'] : 0;
                $comment = isset($_POST['noidung']) ? (int) $_POST['noidung'] : 0;
                $userId = isset($_SESSION['user']) ? $_SESSION['user'] : 0;


                if ($productId && $userId) {
                    $addCmt($userId, $productId, $comment);
                    $comment = getAllcmt($userId, $productId, $comment);
                    header('location: index.php?act=chitietsp?id=' . $productId);
                }
            }
            include 'binhluan.php';
            break;
        case 'pay':
            if (isset($_POST['thanh_toan'])) {
                addOrder();
            }
            include 'view/pay.php';
            break;
        case "vnpay":
            include "vnpay/index.php";
            break;
        case "momo":
            include "view/momo.php";
            break;
        case "thanhtoan":

            include "view/thanhtoan.php";
            break;
        case "quenmk":
            if (isset($_POST['quenmk']) && ($_POST['quenmk'])) {
                $email = $_POST['email'];
                $checkmail = checkemail($email);
                if (is_array($checkmail)) {
                    $thongbao = "<p style='font-size: 16px;color: red;padding: 10px 0;'> Mật khẩu của bạn là: " . $checkmail['password'] . "</p>";
                } else {
                    $thongbao = "<p style='font-size: 16px;color: red;padding: 10px 0;'> Email này không tồn tại!</p>";
                }
            }
            include "view/quenmk.php";
            break;
        case "updateUser":
            if (isset($_POST['btn-update'])) {
                $id = $_POST['id'];
                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $fullname = $_POST['fullname'];
                $phone = $_POST['phone'];

                $billing_address = $_POST['billing_address'];
                $billing_city = $_POST['billing_city'];
                $update = updateUser($email, $username, $password,  $fullname, $phone, $billing_address, $billing_city, $id);

                header('location: index.php');
            }

            include 'view/updateUser.php';
            break;
        case "dangky":
            if (isset($_POST['dangky']) && ($_POST['dangky'])) {
                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $fullname = $_POST['fullname'];
                $phone = $_POST['phone'];
                
                $billing_address = $_POST['billing_address'];
                $billing_city = $_POST['billing_city'];
                $getuser = addUser($email, $username, $password, $fullname, $phone, $billing_address, $billing_city);
            }
            include "view/dangky.php";
            break;
        case 'dangxuat':
            unset($_SESSION['user']);
            header('location: index.php');
            break;
        case 'dangnhap':
            if (isset($_POST['dangnhap'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];


                $user = login($username, $password);
                if ($user) {
                    if ($user['is_admin'] == 1) {
                        $_SESSION['user'] = $user;
                        header('location: admin/index.php');
                    } else {
                        if ($user['status'] == 1) {
                            $tb = 'Tài khoản đã bị khóa';
                        } else {
                            $_SESSION['user'] = $user;
                            header('location: index.php');
                        }
                    }
                }
            }
            include 'view/dangnhap.php';
            break;
        case 'sanpham':
            $dsdm = get_all_dm();
            if (!isset($_GET['id'])) {
                $iddm = 0;
            } else {
                $iddm = $_GET['id'];
            }
            $dssp = get_all_dm_sp($kyw, $iddm, 12);
            include 'view/sanpham.php';
            break;
        case 'lienhe':
            include "view/lienhe.php";
            break;
        case 'delCart':
            //lấy giá trị
            $id = $_GET['id'];
            deleteCart($id);
            include "view/cart.php";
            break;
        case 'updateCart':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                if (isset($_POST['add'])) {
                    // Nếu người dùng ấn nút '+' thì tăng số lượng sản phẩm lên 1
                    $_SESSION['cart'][$id]['quantity']++;
                } elseif (isset($_POST['subtract'])) {
                    // Nếu người dùng ấn nút '-' thì giảm số lượng sản phẩm đi 1
                    $_SESSION['cart'][$id]['quantity']--;
                    if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                        // Nếu số lượng giảm xuống dưới 0, xóa sản phẩm khỏi giỏ hàng
                        unset($_SESSION['cart'][$id]);
                    }
                }
                // Chuyển hướng về trang giỏ hàng sau khi cập nhật số lượng sản phẩm
                header("Location: index.php?act=cart");
                exit();
            }
            break;

        case 'cart':
            if (isset($_POST['dathang']) && ($_POST['dathang'])) {
                $img = $_POST['img'];
                $tensp = $_POST['tensp'];
                $gia = $_POST['gia'];
                $id = $_POST['id'];
                $soLuong = $_POST['soluong'];

                // Kiểm tra nếu giỏ hàng chưa được khởi tạo, thì khởi tạo
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = array();
                }

                // Kiểm tra sản phẩm đã tồn tại trong giỏ hàng hay chưa
                if (isset($_SESSION['cart'][$id])) {
                    // Nếu sản phẩm đã tồn tại, cập nhật số lượng
                    $_SESSION['cart'][$id]['quantity'] += $soLuong;
                } else {
                    // Nếu sản phẩm chưa tồn tại, thêm sản phẩm mới vào giỏ hàng
                    $_SESSION['cart'][$id] = array(
                        "id" => $id,
                        "img" => $img,
                        "name" => $tensp,
                        "quantity" => $soLuong,
                        "price" => $gia
                    );
                }

                // Chuyển hướng đến trang giỏ hàng sau khi thực hiện thêm sản phẩm
                header("Location: index.php?act=shop");
                exit(); // Kết thúc xử lý
            }
            include "view/cart.php";
            break;
        case "chitietsp":
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                $product = getOneProduct($_GET['id']);
            }
            include "view/chitietsp.php";
            break;
        case "shop":
            $dsdm = get_all_dm();
            if (!isset($_GET['id'])) {
                $iddm = 0;
            } else {
                $iddm = $_GET['id'];
            }
            if (isset($_POST['timkiem']) && ($_POST['timkiem'])) {
                $kyw = $_POST['kyw'];
            } else {
                $kyw = "";
            }
            $dssp = get_all_dm_sp($kyw, $iddm, 12); //$iddm,12
            include "view/shop.php";
            break;
        default:

            $getallsp = sp_all();
            include "view/home.php";
            break;
    }
} else {

    $getallsp = sp_all();
    include "view/home.php";
}

include "view/footer.php";
