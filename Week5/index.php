<?php
require "db.php";

$products = $pdo->query("SELECT p.*, c.name AS category FROM products p JOIN categories c ON p.category_id = c.id LIMIT 6")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEase - Online Store</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</div>

<div class="hero">
    <h1>Welcome to ShopEase</h1>
    <p>Discover amazing products at unbeatable prices</p>
    <a href="#products">Shop Now</a>
</div>

<h2 class="section-title" id="products">Our Products</h2>

<div class="products-grid">
    <?php foreach ($products as $product):
        $icons = ['Electronics' => '📱', 'Clothing' => '👕', 'Home & Kitchen' => '🏠'];
        $icon = $icons[$product['category']] ?? '🛍️';
    ?>
    <div class="product-card">
        <div style="background:#e8f0fe; height:200px; display:flex; align-items:center; justify-content:center;">
            <span style="font-size:60px;"><?= $icon ?></span>
        </div>
        <div class="card-body">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p class="description"><?= htmlspecialchars($product['description']) ?></p>
            <p class="price">KSh <?= number_format($product['price'], 2) ?></p>
            <a href="login.php" class="btn btn-primary">Add to Cart</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<footer>
    <p>&copy; 2025 ShopEase. Built by Abdinasir Adow | BSCCS/2024/74116</p>
</footer>

</body>
</html>
