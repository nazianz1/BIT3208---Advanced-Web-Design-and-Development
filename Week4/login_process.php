<?php
session_start();
require_once 'config.php';

$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header("Location: ../week3/login.php?error=empty");
    exit();
}

$email = mysqli_real_escape_string($conn, $email);
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['fullname'];
    $_SESSION['user_role'] = $user['role'];
    header("Location: ../week2/index.php");
    exit();
} else {
    header("Location: ../week3/login.php?error=invalid");
    exit();
}
?>