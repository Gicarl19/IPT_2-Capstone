<?php
session_start();

// Redirect if no email in session
if (!isset($_SESSION['email'])) {
    header("Location: /index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Email Verification</h2>
            <p>An OTP has been sent to: <strong><?php echo $_SESSION['email']; ?></strong></p>
            
            <?php
            if (isset($_SESSION['otp_error'])) {
                echo '<div class="error">' . $_SESSION['otp_error'] . '</div>';
                unset($_SESSION['otp_error']);
            }
            
            if (isset($_SESSION['success_message'])) {
                echo '<div class="success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            ?>
            
            <form action="process.php" method="post">
                <div class="form-group">
                    <label for="otp">Enter OTP:</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <button type="submit" name="verify_otp">Verify</button>
            </form>
            
            <div class="otp-timer">
                <p>OTP expires in: <span id="timer">10:00</span></p>
            </div>
            
            <div class="resend-otp">
                <p>Didn't receive the OTP?</p>
                <form action="process.php" method="post">
                    <button type="submit" name="resend_otp" id="resend-btn" disabled>Resend OTP</button>
                    <span id="resend-timer">(Available in 60 seconds)</span>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // OTP expiry timer - 10 minutes
        let timeLeft = 10 * 60;
        const timerElement = document.getElementById('timer');
        
        const otpTimer = setInterval(function() {
            const minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            
            timerElement.textContent = minutes + ':' + seconds;
            
            if (timeLeft <= 0) {
                clearInterval(otpTimer);
                timerElement.textContent = 'Expired';
            }
            timeLeft--;
        }, 1000);
        
        // Resend OTP timer - 60 seconds
        let resendTimeLeft = 60;
        const resendBtn = document.getElementById('resend-btn');
        const resendTimer = document.getElementById('resend-timer');
        
        const resendOtpTimer = setInterval(function() {
            resendTimer.textContent = `(Available in ${resendTimeLeft} seconds)`;
            
            if (resendTimeLeft <= 0) {
                clearInterval(resendOtpTimer);
                resendBtn.disabled = false;
                resendTimer.textContent = '';
            }
            resendTimeLeft--;
        }, 1000);
    </script>
</body>
</html>