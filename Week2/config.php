<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "ecommerce_db";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("<h2 style='color:red;'>❌ Connection Failed: " . mysqli_connect_error() . "</h2>");
} else {
    echo "<h2 style='color:green;'>✅ Database Connected Successfully!</h2>";
    echo "<p>Connected to: <strong>$database</strong></p>";
}
?>