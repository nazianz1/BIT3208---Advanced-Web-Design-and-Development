<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ShopEase</title>
    <link rel="stylesheet" href="../week2/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href="../week2/index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="../week2/index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</div>

<!-- REGISTER FORM -->
<div class="form-container">
    <h2>Create Account</h2>

    <div id="successMsg" class="alert alert-success" style="display:none;">
        ✅ Account created successfully! <a href="login.php">Login here</a>
    </div>

    <div class="form-group">
        <label>Full Name</label>
        <input type="text" id="fullname" placeholder="Enter your full name">
        <span class="error" id="nameError">⚠️ Full name is required</span>
    </div>

    <div class="form-group">
        <label>Email Address</label>
        <input type="email" id="email" placeholder="Enter your email">
        <span class="error" id="emailError">⚠️ Valid email is required</span>
    </div>

    <div class="form-group">
        <label>Password</label>
        <input type="password" id="password" placeholder="Enter password">
        <span class="error" id="passError">⚠️ Password must be at least 6 characters</span>
        <div id="strengthBar" style="margin-top:8px; height:6px; border-radius:3px; background:#ddd;">
            <div id="strengthFill" style="height:6px; border-radius:3px; width:0%; transition:0.3s;"></div>
        </div>
        <small id="strengthText" style="font-size:12px; color:#777;"></small>
    </div>

    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" id="confirmPassword" placeholder="Confirm your password">
        <span class="error" id="confirmError">⚠️ Passwords do not match</span>
    </div>

    <button class="btn btn-primary" onclick="validateRegister()">Create Account</button>

    <p style="text-align:center; margin-top:20px; font-size:14px;">
        Already have an account? <a href="login.php" style="color:#1a73e8;">Login here</a>
    </p>
</div>

<script>
// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');

    if (val.length === 0) {
        fill.style.width = '0%';
        text.textContent = '';
    } else if (val.length < 4) {
        fill.style.width = '30%';
        fill.style.background = '#e53935';
        text.textContent = 'Weak';
        text.style.color = '#e53935';
    } else if (val.length < 8) {
        fill.style.width = '65%';
        fill.style.background = '#fb8c00';
        text.textContent = 'Medium';
        text.style.color = '#fb8c00';
    } else {
        fill.style.width = '100%';
        fill.style.background = '#43a047';
        text.textContent = 'Strong';
        text.style.color = '#43a047';
    }
});

// Form validation
function validateRegister() {
    let valid = true;

    // Hide all errors first
    document.querySelectorAll('.error').forEach(e => e.style.display = 'none');

    const fullname = document.getElementById('fullname').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirmPassword').value;

    if (fullname === '') {
        document.getElementById('nameError').style.display = 'block';
        valid = false;
    }

    if (email === '' || !email.includes('@')) {
        document.getElementById('emailError').style.display = 'block';
        valid = false;
    }

    if (password.length < 6) {
        document.getElementById('passError').style.display = 'block';
        valid = false;
    }

    if (password !== confirm) {
        document.getElementById('confirmError').style.display = 'block';
        valid = false;
    }

    if (valid) {
        document.getElementById('successMsg').style.display = 'block';
        window.scrollTo(0, 0);
    }
}
</script>

</body>
</html>