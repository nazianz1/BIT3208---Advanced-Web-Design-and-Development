<?php
require_once 'config.php';

$result = mysqli_query($conn, "SELECT * FROM products LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEase - Online Store</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href="index.php" class="logo">🛒 ShopEase</a>
    <nav>
        <a href="index.php">Home</a>
        <a href="../week3/login.php">Login</a>
        <a href="../week3/register.php">Register</a>
    </nav>
</div>

<!-- HERO SECTION -->
<div class="hero">
    <h1>Welcome to ShopEase</h1>
    <p>Discover amazing products at unbeatable prices</p>
    <a href="#products">Shop Now</a>
</div>

<!-- PRODUCTS SECTION -->
<h2 class="section-title" id="products">Our Products</h2>

<div class="products-grid">
    <?php while($product = mysqli_fetch_assoc($result)): ?>
    <div class="product-card">
        <div style="background:#e8f0fe; height:200px; display:flex; align-items:center; justify-content:center;">
            <span style="font-size:60px;">
                <?php
                    $icons = ['Electronics'=>'📱','Clothing'=>'👕','Home & Kitchen'=>'🏠'];
                    $cat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name FROM categories WHERE id=".$product['category_id']));
                    echo $icons[$cat['name']] ?? '🛍️';
                ?>
            </span>
        </div>
        <div class="card-body">
            <h3><?php echo $product['name']; ?></h3>
            <p class="description"><?php echo $product['description']; ?></p>
            <p class="price">KSh <?php echo number_format($product['price'], 2); ?></p>
            <a href="../week3/login.php" class="btn btn-primary">Add to Cart</a>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<!-- FOOTER -->
<footer>
    <p>&copy; 2025 ShopEase. Built by Abdinasir Adow | BSCCS/2024/74116</p>
</footer>

</body>
</html>