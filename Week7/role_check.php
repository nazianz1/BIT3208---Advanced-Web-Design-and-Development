<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_role($allowed_roles) {
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowed_roles)) {
        die("<h2 style='color:red; font-family:Arial; padding:40px;'>❌ Access Denied. Your role is: " . 
            (isset($_SESSION['user_role']) ? htmlspecialchars($_SESSION['user_role']) : 'NOT SET') . 
            "</h2><a href='../Week3/login.php'>Go to Login</a>");
    }
}
?>