<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
$student = mysqli_fetch_assoc($res);

if (!$student) {
    header("Location: index.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $course   = trim($_POST['course']);
    $id       = (int)$_POST['id'];

    if (empty($fullname)) $errors[] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($course)) $errors[] = "Course is required.";

    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE students SET fullname=?, email=?, course=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sssi", $fullname, $email, $course, $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $errors[] = "Update failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
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
    <h2>✏️ Edit Student</h2>

    <?php if(!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach($errors as $e): ?>
            <p>⚠️ <?php echo $e; ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($student['fullname']); ?>" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>Course</label>
            <select name="course" required>
                <option value="">-- Select Course --</option>
                <option value="BSc Computer Science" <?php echo $student['course'] === 'BSc Computer Science' ? 'selected' : ''; ?>>BSc Computer Science</option>
                <option value="BSc Information Technology" <?php echo $student['course'] === 'BSc Information Technology' ? 'selected' : ''; ?>>BSc Information Technology</option>
                <option value="BSc Software Engineering" <?php echo $student['course'] === 'BSc Software Engineering' ? 'selected' : ''; ?>>BSc Software Engineering</option>
                <option value="Diploma in ICT" <?php echo $student['course'] === 'Diploma in ICT' ? 'selected' : ''; ?>>Diploma in ICT</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="index.php" style="display:block; text-align:center; margin-top:15px; color:#1a73e8; font-size:14px;">← Cancel</a>
    </form>
</div>

</body>
</html>