<?php
require_once 'config.php';
require_once 'role_check.php';
require_role(['superuser']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $new_role = $_POST['new_role'];
    if (in_array($new_role, ['superuser','manager','client'])) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET role=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "si", $new_role, $user_id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: manage_users.php?msg=updated");
    exit();
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - ShopEase</title>
    <link rel="stylesheet" href="../Week2/css/style.css">
    <style>
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        th { background: #1a73e8; color: white; padding: 14px; text-align: left; font-size: 14px; }
        td { padding: 12px 14px; border-bottom: 1px solid #f0f0f0; font-size: 14px; }
        select { padding: 6px 10px; border-radius: 6px; border: 1px solid #ddd; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-superuser { background: #fde7e7; color: #c62828; }
        .badge-manager { background: #fff3e0; color: #ef6c00; }
        .badge-client { background: #e8f5e9; color: #2e7d32; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="../Week2/index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="../Week4/dashboard.php">Dashboard</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="../Week4/logout.php" style="background:white; color:#1a73e8; padding:6px 16px; border-radius:20px; font-weight:600;">Logout</a>
    </nav>
</div>

<div class="container">
    <h2 style="color:#1a73e8; margin-bottom:20px;">👥 Manage Users &amp; Roles</h2>

    <?php if(isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
    <div class="alert alert-success">✅ User role updated successfully!</div>
    <?php endif; ?>

    <table>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Current Role</th>
            <th>Change Role</th>
        </tr>
        <?php $i = 1; while($u = mysqli_fetch_assoc($users)): ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($u['fullname']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><span class="badge badge-<?php echo $u['role']; ?>"><?php echo ucfirst($u['role']); ?></span></td>
            <td>
                <form method="POST" style="display:flex; gap:8px;">
                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                    <select name="new_role">
                        <option value="client" <?php echo $u['role']==='client'?'selected':''; ?>>Client</option>
                        <option value="manager" <?php echo $u['role']==='manager'?'selected':''; ?>>Manager</option>
                        <option value="superuser" <?php echo $u['role']==='superuser'?'selected':''; ?>>Super User</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="width:auto; padding:6px 14px;">Save</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>