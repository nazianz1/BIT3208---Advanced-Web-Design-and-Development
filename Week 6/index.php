<?php
require_once 'config.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM students WHERE id=$id");
    header("Location: index.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="../week2/css/style.css">
    <style>
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-header h2 { color: #1a73e8; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        th { background: #1a73e8; color: white; padding: 14px; text-align: left; font-size: 14px; }
        td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        tr:hover { background: #f9f9f9; }
        .actions { display: flex; gap: 8px; }
        .empty { text-align: center; padding: 40px; color: #999; }
    </style>
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

<div class="container">

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] === 'added'): ?>
        <div class="alert alert-success">✅ Student added successfully!</div>
        <?php elseif($_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success">✅ Student updated successfully!</div>
        <?php elseif($_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-error">🗑️ Student deleted successfully!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="page-header">
        <h2>📋 Student Records</h2>
        <a href="add.php" class="btn btn-primary" style="width:auto; padding:10px 25px;">➕ Add New Student</a>
    </div>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM students ORDER BY id DESC");
    $count = mysqli_num_rows($result);
    ?>

    <?php if($count === 0): ?>
        <div class="empty">
            <p style="font-size:48px;">📭</p>
            <p>No students found. <a href="add.php" style="color:#1a73e8;">Add the first one!</a></p>
        </div>
    <?php else: ?>
    <table>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Date Added</th>
            <th>Actions</th>
        </tr>
        <?php $i = 1; while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['course']); ?></td>
            <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
            <td class="actions">
                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-success" style="width:auto; padding:6px 14px;">✏️ Edit</a>
                <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger" style="width:auto; padding:6px 14px;" onclick="return confirm('Are you sure you want to delete this student?')">🗑️ Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php endif; ?>

</div>

</body>
</html>