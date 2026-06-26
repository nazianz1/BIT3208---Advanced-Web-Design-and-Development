<?php
session_start();
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['superuser','manager'])) {
    header("Location: ../Week3/login.php");
    exit();
}

$message = '';
$edit_product = null;

// If we are in EDIT mode, fetch the existing product details (MySQLi style)
if (isset($_GET['edit'])) {
    $product_id = (int)$_GET['edit'];
    $edit_query = "SELECT * FROM products WHERE id = $product_id";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_result && mysqli_num_rows($edit_result) > 0) {
        $edit_product = mysqli_fetch_assoc($edit_result);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $image = 'default.jpg'; // Default value

    if (empty($name) || empty($description)) {
        $message = "❌ Please fill in all fields!";
    } else {
        if (!empty($_POST['product_id'])) {
            // UPDATE Product functionality
            $product_id = (int)$_POST['product_id'];
            $update_query = "UPDATE products SET name='$name', description='$description', price=$price, category_id=$category_id WHERE id=$product_id";
            if (mysqli_query($conn, $update_query)) {
                $message = "✅ Product updated successfully!";
                // Refresh data
                $edit_product['name'] = $name;
                $edit_product['description'] = $description;
                $edit_product['price'] = $price;
                $edit_product['category_id'] = $category_id;
            } else {
                $message = "❌ Error updating product: " . mysqli_error($conn);
            }
        } else {
            // INSERT Product functionality (CREATE)
            $insert_query = "INSERT INTO products (name, description, price, image, category_id) VALUES ('$name', '$description', $price, '$image', $category_id)";
            if (mysqli_query($conn, $insert_query)) {
                $message = "✅ Product added successfully!";
            } else {
                $message = "❌ Error adding product: " . mysqli_error($conn);
            }
        }
    }
}

// Fetch all categories for the dropdown selector
$categories = [];
$cat_query = "SELECT * FROM categories ORDER BY name ASC";
$cat_result = mysqli_query($conn, $cat_query);
if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ShopEase</title>
    <link rel="stylesheet" href="../week2/css/style.css">
    <style>
        .admin-container { max-width: 600px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); font-family: Arial, sans-serif; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #1a73e8; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background-color: #1557b0; }
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 4px; font-weight: bold; text-align: center; }
    </style>
</head>
<body>

<div class="navbar">
    <a href="../week2/index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="products.php">View Products</a>
        <a href="admin.php">Add Product</a>
        <a href="../Week3/login.php">Logout</a>
    </nav>
</div>

<div class="admin-container">
    <h2><?= $edit_product ? '✏️ Edit Product' : '➕ Add New Product' ?></h2>

    <?php if ($message): ?>
        <div class="alert" style="background-color: <?= strpos($message, '✅') !== false ? '#e8f5e9' : '#ffebee' ?>; color: <?= strpos($message, '✅') !== false ? '#2e7d32' : '#c62828' ?>;">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="admin.php<?= $edit_product ? '?edit=' . $edit_product['id'] : '' ?>" method="POST">
        <?php if ($edit_product): ?>
            <input type="hidden" name="product_id" value="<?= $edit_product['id'] ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($edit_product['name'] ?? '') ?>" placeholder="e.g. Wireless Headphones" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Describe the item features..." required><?= htmlspecialchars($edit_product['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Price (KSh)</label>
            <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($edit_product['price'] ?? '') ?>" placeholder="e.g. 3500" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($edit_product['category_id']) && $edit_product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn-submit"><?= $edit_product ? 'Update Product Details' : 'Save Product to Catalog' ?></button>
    </form>
</div>

</body>
</html>