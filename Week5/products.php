<?php
session_start();
require '../Week3/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Week3/login.php");
    exit;
}

$message = '';

// Handle Product Deletion
if (isset($_GET['delete'])) {
    if ($_SESSION['role'] !== 'admin') {
        $message = "❌ Only administrators can delete products!";
    } else {
        $delete_id = (int)$_GET['delete'];
        $delete_query = "DELETE FROM products WHERE id = $delete_id";
        if (mysqli_query($conn, $delete_query)) {
            $message = "✅ Product deleted successfully!";
        } else {
            $message = "❌ Error deleting product: " . mysqli_error($conn);
        }
    }
}

// Fetch all products from the database
$products = [];
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products - ShopEase</title>
    <link rel="stylesheet" href="../week2/css/style.css">
    <style>
        .products-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-family: Arial, sans-serif; }
        .products-table th, .products-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .products-table th { background-color: #f4f4f4; font-weight: bold; }
        .products-table tr:hover { background-color: #f9f9f9; }
        .action-btns a { text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 13px; font-weight: bold; margin-right: 5px; }
        .edit-btn { background-color: #fb8c00; color: white; }
        .delete-btn { background-color: #e53935; color: white; }
        .add-btn { background-color: #1a73e8; color: white; text-decoration: none; padding: 10px 15px; border-radius: 4px; display: inline-block; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="../week2/index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="products.php">View Products</a>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="admin.php" style="background: #e1f5fe; color: #0288d1; padding: 5px 10px; border-radius: 4px;">+ Add Product</a>
        <?php endif; ?>
        <a href="../Week3/login.php">Logout</a>
    </nav>
</div>

<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    <h2>Product Catalog Management</h2>

    <?php if ($message): ?>
        <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #e8f5e9; color: #2e7d32; font-weight: bold; text-align: center;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="admin.php" class="add-btn">➕ Add New Product</a>
    <?php endif; ?>

    <table class="products-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #777; padding: 20px;">No products found in the database.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td><?= $prod['id'] ?></td>
                        <td><strong><?= htmlspecialchars($prod['name']) ?></strong></td>
                        <td><?= htmlspecialchars($prod['description']) ?></td>
                        <td>KSh <?= number_format($prod['price'], 2) ?></td>
                        <td><?= htmlspecialchars($prod['category_name'] ?? 'Uncategorized') ?></td>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <td class="action-btns">
                                <a href="admin.php?edit=<?= $prod['id'] ?>" class="edit-btn">✏️ Edit</a>
                                <a href="products.php?delete=<?= $prod['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">🗑️ Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>