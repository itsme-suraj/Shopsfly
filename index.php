<?php
include "includes/db_connection.php";
$result = $conn->query("SELECT * FROM products LIMIT 6");
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopsFly - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<a href="dashboard.php" class="site-logo">
    <img src="assets/images/logo.png" alt="ShopsFly Logo">
</a>
    <div class="container">
        <div class="header">
        <h1>
            <span>Welcome to</span>
            SHOPSFLY
        </h1>
        <div class="auth-links">
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </div>
</div>

        <div class="products">
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="product">
                    <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <h2><?php echo $row['name']; ?></h2>
                    <p>₹<?php echo number_format($row['price'], 2); ?></p>
                    <a href="product.php?id=<?php echo $row['product_id']; ?>">View Details</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>