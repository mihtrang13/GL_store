<?php
session_start();
ob_start();

if ($_SESSION['user']['is_admin'] === 1) {
    include 'view/header.php';
    include '../model/connect.php';
    include '../model/category.php';
    include '../model/product.php';
    include '../model/user.php';
    include '../model/order.php';
    include '../model/count_element.php';

    if (isset($_GET['act'])) {
        switch ($_GET['act']) {

            case 'categories':
                $categories = getAllcategories();
                include 'view/categories.php';
                break;
            case 'updateCategories':
                if (isset($_POST['suadm'])) {
                    $idCat = $_POST['idCat'];
                    $tendm = $_POST['tendm'];
                    $motadm = $_POST['motadm'];
                    $vitridm = $_POST['vitridm'];
                    updateCategory($idCat, $tendm, $motadm, $vitridm);

                    header('location: index.php?act=categories');
                }
                $id = $_GET['id'];
                if ($id) {
                    $category = getOneCategory($id);
                }

                include 'view/updateCategories.php';
                break;
            case 'delCategories':
                $id = $_GET['id'];
                if ($id) {
                    delCat($id);

                    header('location: index.php?act=categories');
                }
            case 'addCategories':
                if (isset($_POST['themdm'])) {
                    $tendm = $_POST['tendm'];
                    $vitridm = $_POST['vitridm'];
                    $motadm = $_POST['motadm'];

                    $id = addCategories($tendm, $motadm, $vitridm);

                    if ($id) {
                        echo  "Thêm danh mục thành công";
                    } else {
                        echo "Thêm danh mục không thành công";
                    }
                }
                $categories = getAllcategories();
                require_once 'view/categories.php';
                break;
            case 'products':
                $categories = getAllcategories();
                $products = get_all_sp();

                if (isset($_POST['thaydoi'])) {
                    // Nếu form được gửi đi
                    $productId = isset($_POST['selectedProductId']) ? $_POST['selectedProductId'] : null;
                    $newStatus = isset($_POST['status_' . $productId]) ? $_POST['status_' . $productId] : null;
                
                    // Cập nhật trạng thái sản phẩm
                    update_status_product($productId, $newStatus);
                }
                include 'view/products.php';
                break;
            case 'editProduct':
                if (isset($_POST['suasp']) && ($_POST['suasp'])) {
                    $idPro = $_POST['idPro'];
                    $name = $_POST['name'];
                    $price = $_POST['price'];
                    $description = $_POST['description'];
                    $category_id = $_POST['categories_id'];
                    $quantity = $_POST['quantity'];
                    $target_file = $_POST['oldImage'];
                    //lưu đường dẫn hình ảnh vào database
                    //upload hình ảnh lên host
                    if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
                        $target_dir = "";
                        // Hàm basename() dùng để trả về tên tập tin từ một đường dẫn.
                        $target_file = $target_dir . basename($_FILES["image"]["name"]);

                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                        // Kiểm tra ext của file có thoả png, jpeg, gif
                        if (
                            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                            && $imageFileType != "gif"
                        ) {
                            $uploadOk = 0;
                        }

                        if ($uploadOk == 0) {
                            //echo "Xin lỗi, tệp của bạn không được tải lên.";
                            $target_file = "";
                        } else {
                            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                        }
                    }
                    updateProduct($idPro, $category_id, $name, $description, $price, $quantity, $target_file);
                    header('location: index.php?act=products');
                } else {
                    $id = $_GET['id'];
                    $product = getOneProduct($id);
                    $categories = getAllcategories();
                    include 'view/updateProducts.php';
                }
                break;
                /* case 'delProduct':
                $id = $_GET['id'];
                if ($id) {
                    deleteProduct($id);
                }
                header('location: index.php?act=products');
                break; */
            case 'addProducts':
                if (isset($_POST['themsp'])) {
                    $name = $_POST['name'];
                    $price = $_POST['price'];
                    $description = $_POST['description'];
                    $category_id = $_POST['categories_id'];
                    $quantity = $_POST['quantity'];
                    $target_file = '';

                    // Lưu đường dẫn hình ảnh vào database và upload hình ảnh lên host
                    if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
                        $target_dir = "";
                        $target_file = $target_dir . basename($_FILES["image"]["name"]);

                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                        // Kiểm tra ext của file có thoả mãn định dạng ảnh
                        $allowedExtensions = array("jpg", "jpeg", "png", "gif");
                        if (!in_array($imageFileType, $allowedExtensions)) {
                            $uploadOk = 0;
                        }

                        if ($uploadOk == 0) {
                            // Handle lỗi khi tệp không hợp lệ
                            $target_file = "";
                        } else {
                            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                        }
                    }

                    // Thêm sản phẩm mới vào cơ sở dữ liệu
                    addProduct($category_id, $name, $description, $price, $quantity,  $target_file);
                    header('location: index.php?act=products');
                }

                // Lấy danh sách danh mục sản phẩm
                $categories = getAllcategories();

                // Hiển thị giao diện
                include 'view/products.php';
                break;
            case 'order':
                $orders = getAllOrders();
              
                include 'view/order.php';
                break;
            case 'updateOrder':
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $order = getOneOrder($id);
                    $order_detail = getOrderDetail($id);

                   
                    include "view/updateOrder.php";
                }

                //LƯU 1 RECORD đúng với params từ form submit
                if (isset($_POST['thaydoi']) && ($_POST['thaydoi'])) {
                    $id = $_POST['id'];
                    $status = $_POST['status'];
                    updateOrder($id, $status);
                    header('location: index.php?act=order');
                }
                break;
            case 'user':
                $users = getAllUser();
                if (isset($_POST['thaydoi'])) {
                    // Nếu form được gửi đi
                    $userId = isset($_POST['selectedUserId']) ? $_POST['selectedUserId'] : null;
                    $newStatus = isset($_POST['status_' . $userId]) ? $_POST['status_' . $userId] : null;
                
                    // Cập nhật trạng thái người dùng
                    update_status_users($userId, $newStatus);
                }
                include 'view/user.php';
                break;
            case 'addUser':
                if (isset($_POST['addUser'])) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $full_name = $_POST['full_name'];
                    $phone = $_POST['phone'];
                    $email = $_POST['email'];

                    addUser($email, $username, $password, $full_name, $phone, $shipping_address, $shipping_city, $billing_address, $billing_city);
                    header('location: index.php?act=user');
                }
                include 'view/user.php';
                break;

            case 'updateUser':
                if (isset($_POST['editUser'])) {
                    $idUser = $_POST['idUser'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $full_name = $_POST['full_name'];
                    $phone = $_POST['phone'];
                    $email = $_POST['email'];
                    $billing_address = $_POST['billing_address'];
                    $billing_city = $_POST['billing_city'];

                    updateUser($email, $username, $password, $full_name, $phone, $billing_address, $billing_city, $idUser);
                    header('location: index.php?act=user');
                }
                $id = $_GET['id'];
                $user = getOneUser($id);
                include 'view/updateUsers.php';
                break;
            case 'dangxuat':
                unset($_SESSION['user']);
                header('location: ../index.php');
                break;
            case 'admin':

                break;
            default:

                include 'view/home.php';
                break;
        }
    } else {
        if (!isset($_SESSION['user']) && ($_SESSION['user']['is_admin']) == 1) {

            header('location: home');
        } else {

            include 'view/home.php';
        }
    }
    include 'view/footer.php';
} else {
    header("Location: ../index.php");
}
