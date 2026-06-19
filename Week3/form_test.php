<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Test</title>
    <link rel="stylesheet" href="../week2/css/style.css">
</head>
<body>

<div class="form-container">
    <h2>Test Form</h2>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <div class="alert alert-success">
        <strong>✅ Form Submitted Successfully!</strong><br>
        Name: <strong><?php echo htmlspecialchars($_POST['name']); ?></strong><br>
        Email: <strong><?php echo htmlspecialchars($_POST['email']); ?></strong><br>
        Message: <strong><?php echo htmlspecialchars($_POST['message']); ?></strong>
    </div>
    <?php endif; ?>

    <form method="POST" action="form_test.php">
        <div class="form-group">
            <label>Your Name</label>
            <input type="text" name="name" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label>Message</label>
            <input type="text" name="message" placeholder="Type something" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

</body>
</html>