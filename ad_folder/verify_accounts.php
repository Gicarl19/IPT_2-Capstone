<?php 
session_start();  

if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

?>

<?php
$servername = "localhost"; 
$username = "root";
$password = "1234";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (file_exists('../config.php')) {
    require_once '../config.php';
}

$verification_message = '';

if (isset($_POST['verify_account'])) {
    $user_id = $_POST['user_id'];
    $entered_otp = $_POST['otp'];
    
    $stmt = $conn->prepare("SELECT * FROM registration WHERE id = ? AND otp = ? AND otp_expires > NOW()");
    $stmt->bind_param("is", $user_id, $entered_otp);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $update_stmt = $conn->prepare("UPDATE registration SET is_active = 1, otp = NULL, otp_expires = NULL WHERE id = ?");
        $update_stmt->bind_param("i", $user_id);
        
        if ($update_stmt->execute()) {
            $verification_message = '<div class="alert alert-success">Account verified successfully!</div>';
        } else {
            $verification_message = '<div class="alert alert-danger">Error activating account: ' . $conn->error . '</div>';
        }
    } else {
        $verification_message = '<div class="alert alert-danger">Invalid or expired OTP.</div>';
    }
}

if (isset($_POST['regenerate_otp'])) {
    $user_id = $_POST['user_id'];
    
    $stmt = $conn->prepare("SELECT name, email FROM registration WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        function generateOTP($length = 6) {
            $characters = '0123456789';
            $otp = '';
            
            for ($i = 0; $i < $length; $i++) {
                $otp .= $characters[rand(0, strlen($characters) - 1)];
            }
            
            return $otp;
        }
        
        $new_otp = generateOTP();
        $otp_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $update_stmt = $conn->prepare("UPDATE registration SET otp = ?, otp_expires = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $new_otp, $otp_expires, $user_id);
        
        if ($update_stmt->execute()) {
            $verification_message = '<div class="alert alert-success">New OTP generated: ' . $new_otp . '</div>';
            
            if (defined('USE_MAILER') && USE_MAILER) {
                $admin_email = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@example.com';
                $subject = "New OTP for User Verification";
                $mail_message = "A new OTP has been generated for user verification:\n\n";
                $mail_message .= "Name: " . $user['name'] . "\n";
                $mail_message .= "Email: " . $user['email'] . "\n\n";
                $mail_message .= "New OTP: " . $new_otp . "\n\n";
                $mail_message .= "This OTP will expire in 24 hours.\n\n";
                $mail_message .= "Regards,\nYour System";
                
                $headers = 'From: noreply@yoursystem.com' . "\r\n";
                
                mail($admin_email, $subject, $mail_message, $headers);
            }
        } else {
            $verification_message = '<div class="alert alert-danger">Error generating new OTP: ' . $conn->error . '</div>';
        }
    } else {
        $verification_message = '<div class="alert alert-danger">User not found.</div>';
    }
}

$stmt = $conn->prepare("SELECT id, name, email, role, otp, otp_expires FROM registration WHERE is_active = 0 ORDER BY otp_expires ASC");
$stmt->execute();
$pending_accounts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Verify User Accounts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .container { max-width: 1200px; margin-top: 30px; }
        .card { margin-bottom: 20px; }
        .expired { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Verify User Accounts</h2>
                <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Admin Dashboard</a>
                
                <?php echo $verification_message; ?>
                
                <?php if ($pending_accounts->num_rows > 0): ?>
                    <div class="row">
                        <?php while ($account = $pending_accounts->fetch_assoc()): ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Pending Account</h5>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Name:</strong> <?php echo htmlspecialchars($account['name']); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($account['email']); ?></p>
                                        <p><strong>Role:</strong> <?php echo htmlspecialchars($account['role']); ?></p>
                                        <p><strong>Current OTP:</strong> <?php echo htmlspecialchars($account['otp']); ?> (for demo only)</p>
                                        <p>
                                            <strong>OTP Expires:</strong> 
                                            <?php 
                                                $expires = strtotime($account['otp_expires']);
                                                $now = time();
                                                $expired = $expires < $now;
                                                $class = $expired ? 'expired' : '';
                                                echo '<span class="' . $class . '">' . date('Y-m-d H:i:s', $expires) . ($expired ? ' (EXPIRED)' : '') . '</span>';
                                            ?>
                                        </p>
                                        
                                        <form method="post" action="" class="mb-3">
                                            <input type="hidden" name="user_id" value="<?php echo $account['id']; ?>">
                                            <div class="mb-3">
                                                <label for="otp" class="form-label">Enter OTP to verify:</label>
                                                <input type="text" class="form-control" id="otp" name="otp" required>
                                            </div>
                                            <button type="submit" name="verify_account" class="btn btn-primary">Verify Account</button>
                                        </form>
                                        
                                        <form method="post" action="">
                                            <input type="hidden" name="user_id" value="<?php echo $account['id']; ?>">
                                            <button type="submit" name="regenerate_otp" class="btn btn-warning">Regenerate OTP</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No pending accounts to verify.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>