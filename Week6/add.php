<?php
require_once 'config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $course   = trim($_POST['course']);

   
    if (empty($fullname)) $errors[] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($course)) $errors[] = "Course is required.";

   
    $check = mysqli_prepare($conn, "SELECT id FROM students WHERE email = ?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    if (mysqli_stmt_num_rows($check) > 0) $errors[] = "Email already exists.";

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO students (fullname, email, course) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $fullname, $email, $course);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=added");
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="../week2/css/style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">🎓 Student MS</a>
    <nav>
        <a href="index.php">All Students</a>
        <a href="add.php">Add Student</a>
        <a href="../week2/index.php">Back to Shop</a>
    </nav>
</div>

<div class="form-container">
    <h2>➕ Add New Student</h2>

    <?php if(!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach($errors as $e): ?>
            <p>⚠️ <?php echo $e; ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="add.php">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" placeholder="e.g. John Kamau" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" placeholder="e.g. john@students.mku.ac.ke" required>
        </div>

        <div class="form-group">
            <label>Course</label>
            <select name="course" required>
                <option value="">-- Select Course --</option>
                <option value="BSc Computer Science" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BSc Computer Science') ? 'selected' : ''; ?>>BSc Computer Science</option>
                <option value="BSc Information Technology" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BSc Information Technology') ? 'selected' : ''; ?>>BSc Information Technology</option>
                <option value="BSc Software Engineering" <?php echo (isset($_POST['course']) && $_POST['course'] === 'BSc Software Engineering') ? 'selected' : ''; ?>>BSc Software Engineering</option>
                <option value="Diploma in ICT" <?php echo (isset($_POST['course']) && $_POST['course'] === 'Diploma in ICT') ? 'selected' : ''; ?>>Diploma in ICT</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Student</button>
        <a href="index.php" style="display:block; text-align:center; margin-top:15px; color:#1a73e8; font-size:14px;">← Back to Students</a>
    </form>
</div>

</body>
</html>