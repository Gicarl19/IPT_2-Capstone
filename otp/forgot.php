<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot-password'])) {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p class='error-message'>Invalid email format.</p>";
        exit;
    }

    $isEmailExist = true; 

    if ($isEmailExist) {
        echo "<p class='success-message'>Recovery email sent to $email.</p>";
    } else {
        echo "<p class='error-message'>Email not found.</p>";
    }
}
?>
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <p>Enter your email to receive a password reset OTP.</p>
            
            <?php
            if (isset($_SESSION['forgot_error'])) {
                echo '<div class="error">' . $_SESSION['forgot_error'] . '</div>';
                unset($_SESSION['forgot_error']);
            }
            
            if (isset($_SESSION['success_message'])) {
                echo '<div class="success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            ?>
            
            <form action="process.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" name="forgot_password">Send Reset OTP</button>
            </form>
            
            <div class="back-to-login">
                <p>Remember your password? <a href="/index.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>