
<?php
function count_categories() {
    $sql = "SELECT COUNT(*) FROM categories";
    $conn = connect();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}
function count_coments() {
    $sql = "SELECT COUNT(*) FROM comments";
    $conn = connect();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}
function count_order() {
    $sql = "SELECT COUNT(*) FROM orders";
    $conn = connect();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}
function count_products() {
    $sql = "SELECT COUNT(*) FROM products";
    $conn = connect();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}
function count_users() {
    $sql = "SELECT COUNT(*) FROM users WHERE is_admin = 0";
    $conn = connect();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    return $count;
}




?>