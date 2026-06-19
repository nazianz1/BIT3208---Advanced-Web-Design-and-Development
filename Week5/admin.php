<?php
session_start();
require '../Week3/config.php';

// 1. Fix redirect path to Week3 login if not authorized
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Week3/login.php"); 
    exit;
}

$message = '';
$edit_product = null;

// 2. Fetch all categories dynamically so the dropdown functions perfectly
$categories = [];
$cat_query = "SELECT * FROM categories";
$cat_result = mysqli_query($conn, $cat_query);
if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row;
    }
}

// 3. Handle Fetching Product for Editing
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_query = "SELECT * FROM products WHERE id = $edit_id";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_result && mysqli_num_rows($edit_result) > 0) {
        $edit_product = mysqli_fetch_assoc($edit_result);
    }
}

// 4. Handle Form Submission (Add or Update Product)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = (float)$_POST['price'];
    $category_id = (int)$_POST['category_id'];
    $image = isset($_POST['image']) ? mysqli_real_escape_string($conn, $_POST['image']) : 'default.jpg';

    if (empty($name) || empty($description)) {
        $message = "❌ Please fill in all fields!";
    } else {
        if (!empty($_POST['product_id'])) {
            // UPDATE Existing Product
            $product_id = (int)$_POST['product_id'];
            $update_query = "UPDATE products SET name='$name', description='$description', price=$price, category_id=$category_id WHERE id=$product_id";
            if (mysqli_query($conn, $update_query)) {
                $message = "✅ Product updated successfully!";
                // Refresh data in memory
                $edit_product['name'] = $name;
                $edit_product['description'] = $description;
                $edit_product['price'] = $price;
                $edit_product['category_id'] = $category_id;
            } else {
                $message = "❌ Error updating product: " . mysqli_error($conn);
            }
        } else {
            // INSERT New Product
            $insert_query = "INSERT INTO products (name, description, price, image, category_id) VALUES ('$name', '$description', $price, '$image', $category_id)";
            if (mysqli_query($conn, $insert_query)) {
                $message = "✅ Product added successfully!";
            } else {
                $message = "❌ Error adding product: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $edit_product ? 'Edit Product' : 'Add Product' ?> - ShopEase</title>
    <link rel="stylesheet" href="../week2/css/style.css">
</head>
<body>

<div class="navbar">
    <a href="../week2/index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="products.php">View Products</a>
        <a href="../Week3/login.php">Logout</a>
    </nav>
</div>

<div class="form-container" style="max-width:550px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h2><?= $edit_product ? 'Edit Product' : 'Add New Product' ?></h2>

    <?php if ($message): ?>
        <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background-color: #e8f5e9; color: #2e7d32; text-align: center; font-weight: bold;">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="admin.php">
        <input type="hidden" name="product_id" value="<?= $edit_product['id'] ?? '' ?>">

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Product Name</label>
            <input type="text" name="name" required maxlength="150" style="width:100%; padding: 8px; box-sizing: border-box;"
                   value="<?= htmlspecialchars($edit_product['name'] ?? $_POST['name'] ?? '') ?>"
                   placeholder="e.g. Wireless Headphones">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Description</label>
            <input type="text" name="description" required maxlength="255" style="width:100%; padding: 8px; box-sizing: border-box;"
                   value="<?= htmlspecialchars($edit_product['description'] ?? $_POST['description'] ?? '') ?>"
                   placeholder="Short product description">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Price (KSh)</label>
            <input type="number" name="price" required min="0.01" step="0.01" style="width:100%; padding: 8px; box-sizing: border-box;"
                   value="<?= htmlspecialchars($edit_product['price'] ?? $_POST['price'] ?? '') ?>"
                   placeholder="e.g. 3500">
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Category</label>
            <select name="category_id" required style="width:100%; padding: 8px; box-sizing: border-box;">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): 
                    $selected = (isset($edit_product) && $edit_product['category_id'] == $cat['id']) ? 'selected' : '';
                ?>
                    <option value="<?= $cat['id'] ?>" <?= $selected ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" style="width: 100%; padding: 10px; background-color: #1a73e8; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            <?= $edit_product ? 'Update Product' : 'Add Product' ?>
        </button>

        <?php if ($edit_product): ?>
            <a href="admin.php" style="margin-top:10px; display:block; text-align:center; color: #5f6368; text-decoration: none;">Cancel Edit</a>
        <?php endif; ?>
    </form>
</div>

</body>
</html>