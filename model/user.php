<?php
function update_status_users($userId, $newStatus)
{
    // Assuming that the connect() function is implemented correctly
    $conn = connect();

    // Use parameterized query to prevent SQL injection
    $sql = "UPDATE users SET `status`=:status WHERE id=:id";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(":status", $newStatus, PDO::PARAM_STR); // Assuming status is a string, change to PDO::PARAM_INT if it's an integer
    $stmt->bindParam(":id", $userId, PDO::PARAM_INT); // Fix the parameter name here

    // Execute the query
    $stmt->execute();
}
function addUser($email, $username, $password, $full_name, $phone, $billing_address, $billing_city)
{
    $conn = connect();
    $sql = "INSERT INTO `users` (`email`, `username`, `password`, `full_name`, `phone`,  `billing_address`, `billing_city`, `is_admin`) 
        VALUES ('" . $email . "', '" . $username . "', '" . $password . "', '" . $full_name . "', '" . $phone . "', '" . $billing_address . "', '" . $billing_city . "', '0')";
    $conn->exec($sql);
    return $conn->lastInsertId();
}

function updateUser($email, $username, $password,  $fullname, $phone, $billing_address, $billing_city, $id)
{
    $conn = connect();

    $sql = "UPDATE users SET email='" . $email . "', username='" . $username . "', password='" . $password . "', full_name='" . $fullname . "', phone='" . $phone . "', billing_address='" . $billing_address . "', billing_city='" . $billing_city . "' WHERE id = " . $id;

    $conn->exec($sql);
    return $conn->lastInsertId();
}
function login($username, $password)
{
    $conn = connect();
    $sql = "SELECT * FROM `users` WHERE username = '" . $username . "' AND password = '" . $password . "'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch(); // Lấy 1 dòng
    return $result;
}

function getUser($id)
{
    $conn = connect();
    $sql = "SELECT * FROM `users` WHERE id = " . $id;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch(); // Lấy 1 dòng
    return $result;
}

function getAllUser()
{
    $conn = connect();
    $sql = "SELECT * FROM `users`";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetchAll(); // lấy nhiều dòng
    return $kq;
}

function getOneUser($id)
{
    $conn = connect();
    $sql = "SELECT * FROM `users` WHERE id = " . $id;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch(); // Lấy 1 dòng
    return $result;
}



function checkemail($email)
{
    $sql = "Select * from users WHERE email='" . $email . "'";
    $sp = pd_query_one($sql);
    return $sp;
}


function checkGmail($email)
{
    $conn = connect();
    $sql = "SELECT * FROM `users` WHERE email=".$email;
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $kq = $stmt->fetch(); // lấy nhiều dòng
    return $kq;
}
