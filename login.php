<?php
session_start();
include "includes/db_connection.php";

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $sql = "SELECT id, username, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $email;
                
                // Redirect to protected page
                header("Location: /ecommerce/dashboard.php");
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "Email not found!";
        }
    } catch (Exception $e) {
        $error = "Login error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ShopsFly</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<a href="dashboard.php" class="site-logo">
    <img src="assets/images/logo.png" alt="ShopsFly Logo">
</a>

    <div class="container">
        <div class="header_register">
            <h1>
                <span class="welcome-text">Welcome Back</span>
                SHOPSFLY
            </h1>
        </div>

        <div class="registration-container">
            <div class="registration-form">
                <?php if($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <button type="submit" class="auth-links a" style="width: 100%; padding: 12px; border: none;">
                        Login Now
                    </button>
                </form>

                <div class="login-link">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>