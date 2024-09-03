<?php
function updateOrder($id, $status)
{
    $conn = connect();
    $sql = "UPDATE orders SET `is_paid`='" . $status . "' WHERE id=" . $id;
    // Prepare statement
    $stmt = $conn->prepare($sql);
    // execute the query
    $stmt->execute();
}
function updateOrder1($id, $status)
{
    $conn = connect();
    $sql = "UPDATE orders SET `is_paid`='" . $status . "' WHERE id=" . $id;
    // Prepare statement
    $stmt = $conn->prepare($sql);
    // execute the query
    $stmt->execute();
}
function addOrder() {
    try {
        $conn = connect();

        // Thêm vào bảng order
        $sqlOrder = "INSERT INTO orders (`is_paid`, `payment_method`, `user_id`) 
                     VALUES (0, 'Tiền mặt', :user_id)";
        $stmtOrder = $conn->prepare($sqlOrder);
        $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
        $stmtOrder->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtOrder->execute();

        $orderId = $conn->lastInsertId();

        // Thêm vào bảng orders_detail
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $item) {
                // Sử dụng giá từ giỏ hàng thay vì giá trong câu lệnh SQL
                $discountedPrice = isset($_SESSION['gia']) ? $_SESSION['gia'] : $item['price'];

                // Output the discounted price for debugging
                // echo "Discounted Price: " . $discountedPrice . "<br>";

                $sqlDetail = "INSERT INTO orders_detail (`amount`, `order_id`, `product_id`, `id_user`) 
                              VALUES (:amount, :order_id, :product_id, :id_user)";
                $stmtDetail = $conn->prepare($sqlDetail);

                // Check if discountedPrice is not null
                if ($discountedPrice !== null) {
                    $stmtDetail->bindParam(':amount', $discountedPrice, PDO::PARAM_STR);
                } else {
                    // Handle the case where discountedPrice is null (you might want to set a default value)
                    // For now, let's assume 0 as the default value
                    $stmtDetail->bindValue(':amount', 0, PDO::PARAM_STR);
                }

                $stmtDetail->bindParam(':order_id', $orderId, PDO::PARAM_INT);
                $stmtDetail->bindParam(':product_id', $id, PDO::PARAM_INT);
                $stmtDetail->bindParam(':id_user', $userId, PDO::PARAM_INT); // Fix this line
                $stmtDetail->execute();
            }

            // Hủy giỏ hàng
            unset($_SESSION['cart']);

            // echo "Order successfully added to the database."; // Optional success message
        }
    } catch (PDOException $e) {
        echo "Lỗi SQL: " . $e->getMessage();
        echo "<pre>";
        print_r($e->errorInfo);
        echo "</pre>";
    }
}

function getOneOrder($id)
{
    $conn = connect();
    $sql = "SELECT o.id, o.is_paid, o.payment_method, u.id as user_id, u.full_name
            FROM orders as o
            INNER JOIN users as u ON o.user_id = u.id WHERE o.id=" . $id;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch(); // Lấy 1 dòng
    return $result;
}

function getOrderDetail($id)
{
    $conn = connect();
    $sql = "SELECT o.id, o.amount, p.id, p.name, p.image
            FROM orders_detail as o
            INNER JOIN products as p ON o.product_id = p.id WHERE o.order_id=" . $id;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetchAll(); // Lấy 1 dòng
    return $result;
}
function getAllOrders()
{
    $conn = connect();
    $sql = "SELECT o.id, o.is_paid, o.payment_method, u.id as user_id, u.full_name
            FROM orders as o
            INNER JOIN users as u ON o.user_id = u.id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll();
    return $kq;
}



function getUserOrders($user_id) {
    $conn = connect(); // Hàm connect() là giả định, bạn cần thay thế nó bằng kết nối thực của bạn.

    try {
        $sql = "SELECT * FROM orders_detail WHERE id_user = :user_id ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Lấy tất cả các dòng dữ liệu từ kết quả truy vấn
        $user_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $user_orders;
    } catch (PDOException $e) {
        // Xử lý lỗi nếu có
        echo "Lỗi truy vấn: " . $e->getMessage();
        return false;
    } finally {
        // Đóng kết nối sau khi sử dụng
        $conn = null;
    }
}

function all_order($user_id = 1) {
    $conn = connect();

    // Kiểm tra kết nối
    if (!$conn) {
        echo "chưa có sản phẩm oder";
        return [];
    }

    // Đảm bảo $user_id là một giá trị scalar
    if (is_array($user_id)) {
       // echo "User ID is an array. Using the first element.";
        $user_id = reset($user_id); // Lấy giá trị đầu tiên của mảng
    }

    // Sử dụng dấu chấm hỏi để thực hiện binding giá trị cho biến $user_id
   // echo "User ID used in the query: " . $user_id;

    // Sử dụng bindParam để có lợi ích của tham chiếu
    $sql = "SELECT * FROM orders_detail WHERE id_user = ? ORDER BY updation_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Lấy kết quả
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
function getOrderDetails($orderId)
{
    $conn = connect();
    $sql = "SELECT o.id, o.amount, o.id_user, p.id as product_id, p.name, o.creation_date
            FROM orders_detail as o
            INNER JOIN products as p ON o.product_id = p.id 
            WHERE o.order_id = :orderId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

