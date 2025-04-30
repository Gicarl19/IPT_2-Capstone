<?php
session_start();

// Redirect if no email in session
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Reset Password</h2>
            <p>Enter the OTP sent to <strong><?php echo $_SESSION['email']; ?></strong> and your new password.</p>
            
            <?php
            if (isset($_SESSION['reset_error'])) {
                echo '<div class="error">' . $_SESSION['reset_error'] . '</div>';
                unset($_SESSION['reset_error']);
            }
            ?>
            
            <form action="process.php" method="post">
                <div class="form-group">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <small>Must contain at least 8 characters, including uppercase, lowercase, and a number.</small>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
            
            <div class="resend-otp">
                <p>Didn't receive the OTP?</p>
                <form action="process.php" method="post">
                    <button type="submit" name="resend_otp">Resend OTP</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>