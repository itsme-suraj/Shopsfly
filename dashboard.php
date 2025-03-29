<?php
session_start();

include "includes/db_connection.php";

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user-specific data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Get products (adjust query as needed)
try {
    // Get products using prepared statement
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result(); // Properly initialize $result
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

?>

<script>
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', async () => {
        const productId = button.dataset.productId;
        
        try {
            const response = await fetch('add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ product_id: productId })
            });
            
            if (response.ok) {
                const data = await response.json();
                if(data.success) alert('Added to cart!');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ShopsFly</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<!--addding logo-->>
<a href="dashboard.php" class="site-logo">
        <img src="assets/images/logo.png" alt="ShopsFly Logo">
    </a>
<div class="container">
        <!-- Menu Container -->
        <div class="menu-container">
            <div class="menu-icon" onclick="toggleMenu()">
                <div class="menu-line"></div>
                <div class="menu-line"></div>
                <div class="menu-line"></div>
            </div>
            
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="includes/profile.php" class="menu-item">Account Information</a>
                <a href="cart.php" class="menu-item">
                    My Cart (<span id="cart-count">0</span>)
                </a>
                <a href="admin/orders.php" class="menu-item">My Orders</a>
                <div class="menu-divider"></div>
                <a href="includes/categories.php" class="menu-item">Product Categories</a>
                <a href="includes/wishlist.php" class="menu-item">Wishlist</a>
                <div class="menu-divider"></div>
                <a href="includes/settings.php" class="menu-item">Settings</a>
                <a href="logout.php" class="menu-item">Logout</a>
            </div>
        </div>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
    <div class="header-content">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        </div>
        
        <div class="header-actions">
            <form class="search-form" action="search.php" method="GET">
                <input type="text" name="query" placeholder="Search products..." class="search-input">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    </div>
        <!-- Product Grid -->
        <div class="product-grid">
            <?php while($product = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/<?php echo $product['image']; ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                    <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <div class="product-actions">
                            <button class="add-to-cart" data-product-id="<?= $product['product_id'] ?>">Add to Cart</button>
                            <button class="buy-now" data-product-id="<?= $product['product_id'] ?>">Buy Now</button>
                        </div>
                </div>
            </div>
            <?php endwhile; ?>
    </div>

    <script>
        // Toggle dropdown menu
        function toggleMenu() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('show');
        }

        // Close menu when clicking outside
        document.addEventListener('click', (event) => {
            const menu = document.getElementById('dropdownMenu');
            const menuIcon = document.querySelector('.menu-icon');
            
            if (!menu.contains(event.target) && !menuIcon.contains(event.target)) {
                menu.classList.remove('show');
            }
        });
    </script>
</body>
</html>