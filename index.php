<?php

session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

session_unset();

function showError($error){
    return !empty($error) ? "<p class='error-message'> $error </p>" : '';
}
function isActiveForm($formName, $activeForm){
    return $formName === $activeForm ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City of Malabon University</title>
    <link rel="stylesheet" href="/style/index.css">
    <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
  
</head>
<body>
    <div class="container">
        <div class="brand-side">
            <div class="brand-content">
                <img src="/images/cmulogo.png" alt="Logo" class="brand-logo">
                <div class="university-name">City of Malabon University</div><br>
                <div class="university-name">(College of Business Accountacy)</div>

            </div>
        </div>
        
        <div class="form-side">
            <div class="form-container">
                <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
                    <h2>Sign in to your account</h2>
                    <?= showError($errors['login']); ?>
                    <form action="login_register.php" method="post">
                        <label for="email">Username</label>
                        <div class="input-wrapper">
                            <span class="input-icon">✉️</span>
                            <input type="email" id="email" name="email" r placeholder="Email">
                        </div>
                        
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon" >🔒</span>
                            <input type="password" id="password" name="password"  placeholder="Password" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility('password')" >👁️</span>
                        </div>
                        <div class="password-hint">At least 8 characters</div>
                        
                        <div class="forgot-password">
                            <a href="/otp/forgot.php">Forgot your password?</a>
                        </div>
                        
                        <button type="submit" name="login">SIGN IN</button>
                        
                        <div class="form-footer">
                            <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
                        </div>
                    </form>
                </div>
                
                <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
                    <h2>Register a new account</h2>
                    <?= showError($errors['register']); ?>
                    <form action="login_register.php" method="post">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                        
                        <label for="reg-email">Email</label>
                        <input type="email" id="reg-email" name="email" placeholder="Enter your email address" required>
                        
                        <label for="reg-password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="reg-password" name="password" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility('reg-password')">👁️</span>
                        </div>
                        <div class="password-hint">At least 8 characters</div>
                        
                        <label for="role">Select Role</label>
<select id="role" name="role" required>
    <option value="">--Select Role--</option>
    <option value="user">User </option>
</select>

                        
                        <button type="submit" name="register">Register</button>
                        
                        <div class="form-footer">
                            <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function showForm(formId) {
            document.querySelectorAll('.form-box').forEach(form => {
                form.classList.remove('active');
            }); 
            document.getElementById(formId).classList.add('active');
        }
        
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>