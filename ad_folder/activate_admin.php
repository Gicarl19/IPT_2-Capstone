
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

session_start();

$message = '';

$table_check = $conn->query("SHOW COLUMNS FROM registration LIKE 'is_active'");
if ($table_check->num_rows == 0) {
    $conn->query("ALTER TABLE registration ADD COLUMN is_active TINYINT(1) DEFAULT 1");
    $conn->query("ALTER TABLE registration ADD COLUMN otp VARCHAR(10) DEFAULT NULL");
    $conn->query("ALTER TABLE registration ADD COLUMN otp_expires DATETIME DEFAULT NULL");
    $message = "Database updated. Added verification columns.";
}

$admin_check = $conn->query("SELECT COUNT(*) as count FROM registration WHERE role = 'admin' AND is_active = 1");
$active_admin_count = $admin_check->fetch_assoc()['count'];

if ($active_admin_count == 0) {
    $first_admin = $conn->query("SELECT id FROM registration WHERE role = 'admin' ORDER BY id ASC LIMIT 1");
    
    if ($first_admin->num_rows > 0) {
        $admin_id = $first_admin->fetch_assoc()['id'];
        if ($conn->query("UPDATE registration SET is_active = 1 WHERE id = $admin_id")) {
            $message = "First admin account has been activated. You can now log in.";
        } else {
            $message = "Error activating admin account: " . $conn->error;
        }
    } else {
        $message = "No admin accounts found. Please register an admin account first.";
    }
} else {
    $message = "There is already an active admin account. No changes needed.";
}

$users_check = $conn->query("SELECT COUNT(*) as count FROM registration WHERE is_active = 1");
$active_users_count = $users_check->fetch_assoc()['count'];

if ($active_users_count == 0) {
    if ($conn->query("UPDATE registration SET is_active = 1 WHERE role = 'admin'")) {
        $message = "All admin accounts have been activated. You can now log in with an admin account.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Activation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3>Admin Account Activation</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-info">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <p>This script helps activate admin accounts when no admin is active.</p>
                        
                        <?php if ($active_admin_count > 0): ?>
                            <div class="alert alert-success">
                                You have <?php echo $active_admin_count; ?> active admin account(s).
                            </div>
                        <?php endif; ?>
                        
                        <a href="/index.php" class="btn btn-primary">Return to Login Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>