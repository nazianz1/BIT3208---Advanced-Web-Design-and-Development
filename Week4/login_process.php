<?php
session_start();
$host = "localhost";
$username = "root";
$password_db = "";
$database = "ecommerce_db";

$conn = mysqli_connect($host, $username, $password_db, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email) || empty($password)) {
    header("Location: ../Week3/login.php?error=empty");
    exit();
}

$email_safe = mysqli_real_escape_string($conn, $email);
$sql = "SELECT * FROM users WHERE email = '$email_safe' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: ../Week3/login.php?error=invalid");
    exit();
}

$user = mysqli_fetch_assoc($result);

if (password_verify($password, $user['password'])) {
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['fullname'];
    $_SESSION['user_role'] = $user['role'];

    header("Location: ../Week2/index.php");
    exit();
} else {
    header("Location: ../Week3/login.php?error=invalid");
    exit();
}