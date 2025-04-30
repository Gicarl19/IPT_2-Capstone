    <?php
    session_start();
    require_once 'config.php';

    if (!isset($_SESSION['email'])) {
        header("Location: ../index.php");
        exit();
    }

    $conn = new mysqli('localhost', 'root', '1234', 'user_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_SESSION['email'];
    $success_message = "";
    $error_message = "";

    $stmt = $conn->prepare("SELECT * FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (!empty($current_password)) {
            if (password_verify($current_password, $user['password'])) {
                if (!empty($name) && $name !== $user['name']) {
                    $update_name = $conn->prepare("UPDATE registration SET name = ? WHERE email = ?");
                    $update_name->bind_param("ss", $name, $email);
                    $update_name->execute();
                    $_SESSION['name'] = $name;
                    $success_message = "Profile updated successfully!";
                }
                
                if (!empty($new_password)) {
                    if ($new_password === $confirm_password) {
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
                        
                        $strength = checkPasswordStrength($new_password);
                        
                        if ($strength === 'strong') {
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                            $update_password = $conn->prepare("UPDATE registration SET password = ? WHERE email = ?");
                            $update_password->bind_param("ss", $hashed_password, $email);
                            $update_password->execute();
                            $success_message = "Profile and password updated successfully!";
                        } else {
                            $error_message = "Password is too weak. Must contain at least 8 characters, including uppercase, lowercase, and number.";
                        }
                    } else {
                        $error_message = "New passwords do not match!";
                    }
                }
            } else {
                $error_message = "Current password is incorrect!";
            }
        } else if (!empty($name) && $name !== $user['name']) {
            $update_name = $conn->prepare("UPDATE registration SET name = ? WHERE email = ?");
            $update_name->bind_param("ss", $name, $email);
            $update_name->execute();
            $_SESSION['name'] = $name;
            $success_message = "Name updated successfully!";
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($_FILES['profile_picture']['type'], $allowed_types) && $_FILES['profile_picture']['size'] <= $max_size) {
            $upload_dir = 'uploads/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $filename = $email . '_' . time() . '_' . basename($_FILES['profile_picture']['name']);
            $target_file = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $update_pic = $conn->prepare("UPDATE registration SET profile_picture = ? WHERE email = ?");
                if ($update_pic === false) {
                    $error_message = "Prepare failed: " . $conn->error;
                } else {
                    $update_pic->bind_param("ss", $filename, $email);
                    if (!$update_pic->execute()) {
                        $error_message = "Execute failed: " . $update_pic->error;
                    } else {
                        $success_message = "Profile picture updated successfully!";
                        
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();
                    }
                }
            } else {
                $error_message = "Failed to upload profile picture. Check directory permissions.";
            }
        } else {
            $error_message = "Invalid file. Please upload a JPG, PNG, or GIF file under 2MB.";
        }
    }

    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Profile</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
        <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
        <style>
            .profile-container {
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                border-radius: 10px;
            }
            .profile-picture {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                object-fit: cover;
                margin-bottom: 20px;
            }
            .avatar-placeholder {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                background-color: #e9ecef;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 48px;
                color: #adb5bd;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
            <a class="navbar-brand" >Profile</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/user_folder/user_list.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Home
                        </a>
                    </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="profile-container">
                <h2 class="mb-4 text-center">My Profile</h2>
                
                <?php if(!empty($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if(!empty($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-4 text-center">
                        <?php if(isset($user['profile_picture']) && !empty($user['profile_picture'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-picture">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Update Profile Picture</label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-outline-primary">Upload Picture</button>
                        </form>
                    </div>
                    
                    <div class="col-md-8">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                <div class="form-text">Email address cannot be changed.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="role" class="form-label">Account Type</label>
                                <input type="text" class="form-control" id="role" value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>" readonly>
                            </div>
                            
                            <h4 class="mt-4">Change Password</h4>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                            
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>