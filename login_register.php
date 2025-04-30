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

if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    define('ADMIN_EMAIL', 'admin@example.com'); 
    define('USE_MAILER', false); 
}

function generateOTP($length = 6) {
    $characters = '0123456789';
    $otp = '';
    
    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $otp;
}

function sendOTPToAdmin($conn, $user_email, $user_name, $otp) {
    if (!defined('USE_MAILER') || !USE_MAILER) {

        $_SESSION['demo_otp'] = $otp;
        return true;
    }
    
    $admin_email = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : 'admin@example.com';
    $subject = "New User Account Verification";
    $message = "A new user has registered:\n\n";
    $message .= "Name: $user_name\n";
    $message .= "Email: $user_email\n\n";
    $message .= "To verify this account, use the following OTP: $otp\n\n";
    $message .= "This OTP will expire in 24 hours.\n\n";
    $message .= "Regards,\nYour System";
    
    $headers = 'From: noreply@yoursystem.com' . "\r\n";
    
    return mail($admin_email, $subject, $message, $headers);
}

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password_plain = $_POST['password']; 
    $role = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = 'Invalid Email Format';
        header("Location: index.php");
        exit();
    }

    function checkPasswordStrength($password) {
        $length = strlen($password) >= 8;
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
       
        if ($length && $uppercase && $lowercase && $number) {
            return 'strong';
        }
        return 'weak';
    }

    $strength = checkPasswordStrength($password_plain);

    if ($strength !== 'strong') {
        $_SESSION['register_error'] = 'Password is too weak. Must contain at least 8 characters, including uppercase, lowercase, number.';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    }

    $password = password_hash($password_plain, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT email FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is Already Registered';
        $_SESSION['active_form'] = 'register';
        header("Location: index.php");
        exit();
    } 
    
    $table_check = $conn->query("SHOW COLUMNS FROM registration LIKE 'is_active'");
    if ($table_check->num_rows == 0) {
        $conn->query("ALTER TABLE registration ADD COLUMN is_active TINYINT(1) DEFAULT 0");
        $conn->query("ALTER TABLE registration ADD COLUMN otp VARCHAR(10) DEFAULT NULL");
        $conn->query("ALTER TABLE registration ADD COLUMN otp_expires DATETIME DEFAULT NULL");
    }
    
    // Generate OTP and set expiry time (24 hours from now)
    $otp = generateOTP();
    $otp_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    $is_first_admin = false;
    if ($role === 'admin') {
        $admin_check = $conn->query("SELECT COUNT(*) as count FROM registration WHERE role = 'admin'");
        $admin_count = $admin_check->fetch_assoc()['count'];
        $is_first_admin = ($admin_count == 0);
    }
    
    $is_active = ($is_first_admin) ? 1 : 0;
    
    if ($is_first_admin) {
        $stmt = $conn->prepare("INSERT INTO registration (name, email, password, role, is_active) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);
    } else {
        $stmt = $conn->prepare("INSERT INTO registration (name, email, password, role, is_active, otp, otp_expires) VALUES (?, ?, ?, ?, 0, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $password, $role, $otp, $otp_expires);
    }
    
    if ($stmt->execute()) {
        if ($is_first_admin) {
            $_SESSION['register_success'] = 'Admin account created successfully! You can now log in.';
        } else {
            sendOTPToAdmin($conn, $email, $name, $otp);
            
            $_SESSION['register_success'] = 'Registration successful! Your account will be activated after admin verification.';
            if (isset($_SESSION['demo_otp'])) {
                $_SESSION['register_success'] .= ' Demo OTP: ' . $_SESSION['demo_otp'];
            }
        }
    } else {
        $_SESSION['register_error'] = 'Registration failed. Please try again. Error: ' . $conn->error;
        $_SESSION['active_form'] = 'register';
    }

    header("Location: index.php");
    exit();
}

// Login Logic
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $table_check = $conn->query("SHOW COLUMNS FROM registration LIKE 'is_active'");
            if ($table_check->num_rows > 0 && isset($user['is_active']) && $user['is_active'] != 1) {
                
                if ($user['role'] === 'admin') {
                    $admin_check = $conn->query("SELECT COUNT(*) as count FROM registration WHERE role = 'admin' AND is_active = 1");
                    $admin_active_count = $admin_check->fetch_assoc()['count'];
                    
                    if ($admin_active_count == 0) {
                        $conn->query("UPDATE registration SET is_active = 1 WHERE email = '{$user['email']}'");
                        
                        $_SESSION['name'] = $user['name'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['user_id'] = $user['id']; 
                        
                        header("Location: ad_folder/admin_process.php");
                        exit();
                    }
                }
                
                $_SESSION['login_error'] = 'Your account is pending admin verification. Please try again later.';
                $_SESSION['active_form'] = 'login';
                header("Location: index.php");
                exit();
            }
            
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id']; 

            if ($user['role'] === 'admin') {
                header("Location: ad_folder/admin_process.php");
            } else {
                header("Location: user_folder/reservation.php");
            }
            exit();
        } else {
            $_SESSION['login_error'] = 'Incorrect Email or Password';
        }
    } else {
        $_SESSION['login_error'] = 'Incorrect Email or Password';
    }

    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}
?>