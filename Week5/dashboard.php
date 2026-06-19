<?php
session_start();
require "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); exit;
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];

$products = $pdo->query(
    "SELECT p.*, c.name AS category FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC"
)->fetchAll();

$total_products = count($products);
$total_users    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_cats     = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShopEase</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">ShopEase</a>
    <nav>
        <a href="index.php">Home</a>
        <?php if ($user_role === 'admin'): ?>
        <a href="products.php">Manage Products</a>
        <?php endif; ?>
        <a href="logout.php" class="nav-logout">Logout</a>
    </nav>
</div>

<div class="dashboard">
    <div class="welcome-bar">
        <h2>Welcome back, <?= htmlspecialchars($user_name) ?>!</h2>
        <p>Role: <strong><?= ucfirst($user_role) ?></strong> &nbsp;|&nbsp; <?= date('l, d F Y') ?></p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-num"><?= $total_products ?></div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $total_users ?></div>
            <div class="stat-label">Registered Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $total_cats ?></div>
            <div class="stat-label">Categories</div>
        </div>
    </div>

    <?php if ($user_role === 'admin'): ?>
    <div class="admin-links">
        <a href="products.php" class="btn btn-primary">Manage Products</a>
        <a href="admin.php" class="btn btn-success">Add New Product</a>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3>Product Catalog</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price (KSh)</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($products as $p): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['category']) ?></td>
                    <td>KSh <?= number_format($p['price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
