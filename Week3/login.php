<?php
require_once 'config.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Query to find the user with plain text password matching
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect to homepage (Week 2 index)
        header("Location: ../Week2/index.php");
        exit();
    } else {
        $message = "<div class='alert alert-danger' style='display:block;'>❌ Invalid email or password!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShopEase</title>
    <link rel="stylesheet" href="../week2/css/style.css">
    <style>
        .error { display: none; color: #e53935; font-size: 13px; margin-top: 5px; }
        .alert-danger { background-color: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="../week2/index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="../week2/index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</div>

<div class="form-container">
    <h2>Login to ShopEase</h2>

    <div id="messageBox">
        <?php if (!empty($message)) echo $message; ?>
    </div>

    <form action="login.php" method="POST" onsubmit="return validateLogin(event)">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <span class="error" id="emailError">⚠️ Valid email is required</span>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" id="password" name="password" placeholder="Enter password" required>
            <span class="error" id="passError">⚠️ Password is required</span>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <p style="text-align:center; margin-top:20px; font-size:14px;">
        Don't have an account? <a href="register.php" style="color:#1a73e8;">Register here</a>
    </p>
</div>

<script>
function validateLogin(event) {
    let valid = true;
    document.querySelectorAll('.error').forEach(e => e.style.display = 'none');

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    if (email === '' || !email.includes('@')) {
        document.getElementById('emailError').style.display = 'block';
        valid = false;
    }

    if (password === '') {
        document.getElementById('passError').style.display = 'block';
        valid = false;
    }

    if (!valid) {
        event.preventDefault();
        return false;
    }
    return true;
}
</script>

</body>
</html>