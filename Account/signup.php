<?php
require_once '../config/database.php';
require_once '../config/session.php';


if (isLoggedIn()) {
    header("Location: ../index.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  
    $firstname       = trim($_POST['firstname'] ?? '');
    $lastname        = trim($_POST['lastname'] ?? '');
    $birthdate       = trim($_POST['birthdate'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $phone           = trim($_POST['phone'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';
    $clientnumber    = trim($_POST['clientnumber'] ?? '');

    $full_name = trim($firstname . ' ' . $lastname);

    $errors = [];

    
    if ($firstname === '') {
        $errors[] = "First name is required";
    }

    if ($lastname === '') {
        $errors[] = "Last name is required";
    }

    if ($email === '') {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if ($birthdate === '') {
        $errors[] = "Date of birth is required";
    } else {
        $dob = new DateTime($birthdate);
        $today = new DateTime();

        if ($dob > $today) {
            $errors[] = "Invalid birth date";
        } else {
            $age = $today->diff($dob)->y;
            if ($age < 18) {
                $errors[] = "You must be at least 18 years old to register";
            }
        }
    }

    if ($phone === '') {
        $errors[] = "Phone number is required";
    }

    if ($password === '') {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        $errors[] = "Password must contain both letters and numbers";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    if ($clientnumber === '') {
        $errors[] = "Customer number is required";
    }

    if (empty($errors)) {
        try {
           
            $check = $db->prepare("
                SELECT id 
                FROM users 
                WHERE email = :email OR clientnumber = :clientnumber
            ");
            $check->execute([
                ':email' => $email,
                ':clientnumber' => $clientnumber
            ]);

            if ($check->rowCount() > 0) {
                $error = "Email or customer number already exists.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $db->prepare("
                    INSERT INTO users 
                    (full_name, email, password, phone, clientnumber, role, created_at)
                    VALUES 
                    (:full_name, :email, :password, :phone, :clientnumber, 'user', NOW())
                ");

                $stmt->execute([
                    ':full_name'    => $full_name,
                    ':email'        => $email,
                    ':password'     => $hashedPassword,
                    ':phone'        => $phone,
                    ':clientnumber' => $clientnumber
                ]);

            
                session_regenerate_id(true);

                $_SESSION['user_id']   = $db->lastInsertId();
                $_SESSION['full_name'] = $full_name;
                $_SESSION['email']     = $email;
                $_SESSION['role']      = 'user';

                $success = "Registration successful! Redirecting...";
                header("refresh:2;url=../index.php");
                exit();
            }

        } catch (PDOException $e) {
          
            error_log($e->getMessage());
            $error = "Something went wrong. Please try again later.";
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>UltraWave - Registration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @font-face {
            font-family: 'HK Grotesk';
            src: url('fonts/HKGrotesk-Regular.woff2') format('woff2'),
                 url('fonts/HKGrotesk-Regular.woff') format('woff');
            font-weight: 400;
            font-style: normal;
        }

        body {
            background-color: #050a30;
            font-family: 'HK Grotesk', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .registration-container {
            width: 100%;
            max-width: 500px;
            background: #ffffff;
            padding: 40px;
            border-radius: 20px;
            border: 2px solid rgba(92, 182, 249, 0.3);
            box-shadow: 
                0 10px 30px rgba(5, 10, 48, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

       
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
            display: none;
        }
        
        .alert.visible {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        .alert-success {
            background: rgba(0, 173, 147, 0.1);
            color: #00AD93;
            border: 1px solid rgba(0, 173, 147, 0.3);
        }
        
        .alert-error {
            background: rgba(229, 57, 53, 0.1);
            color: #e53935;
            border: 1px solid rgba(229, 57, 53, 0.3);
        }

        .progress-indicator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            position: relative;
        }

        .progress-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 10%;
            right: 10%;
            height: 3px;
            background: #e0e0e0;
            z-index: 1;
        }

        .progress-step {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 30%;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin: 0 auto 10px;
            transition: all 0.3s ease;
            border: 3px solid #e0e0e0;
        }

        .progress-step.active .step-circle {
            background: #12229d;
            color: white;
            border-color: #12229d;
        }

        .progress-step.completed .step-circle {
            background: #5cb6f9;
            color: white;
            border-color: #5cb6f9;
        }

        .progress-step.locked .step-circle {
            background: #f5f5f5;
            color: #999;
            border-color: #e0e0e0;
            cursor: not-allowed;
        }

        .step-label {
            font-size: 12px;
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .progress-step.active .step-label {
            color: #12229d;
            font-weight: 600;
        }

        .progress-step.locked .step-label {
            color: #999;
        }

        .step-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .step-content.active {
            display: block;
        }

        .step-header {
            margin-bottom: 30px;
        }

        .step-header h3 {
            color: #050a30;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .step-header p {
            color: rgba(5, 10, 48, 0.6);
            font-size: 15px;
            line-height: 1.5;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            position: relative;
        }

        .full-width {
            width: 100%;
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #050a30;
            font-weight: 600;
            font-size: 14px;
        }

        .required::after {
            content: ' *';
            color: #e53935;
        }

        input, select {
            width: 100%;
            padding: 14px 18px;
            background: #f9fafd;
            border: 1px solid rgba(5, 10, 48, 0.1);
            border-radius: 10px;
            font-size: 15px;
            color: #050a30;
            font-family: 'HK Grotesk', sans-serif;
            transition: all 0.3s ease;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23050a30' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            padding-right: 45px;
            cursor: pointer;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #5cb6f9;
            box-shadow: 0 0 0 3px rgba(92, 182, 249, 0.1);
            background: #ffffff;
        }

        .checkbox-group {
            margin: 25px 0;
        }

        .checkbox-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 10px;
            background: #f9fafd;
            border-radius: 8px;
            border: 1px solid rgba(5, 10, 48, 0.05);
        }

        .checkbox-item input {
            width: 18px;
            height: 18px;
            margin-top: 3px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .checkbox-label {
            font-size: 13px;
            line-height: 1.5;
            color: rgba(5, 10, 48, 0.8);
        }

        .checkbox-label a {
            color: #12229d;
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-label a:hover {
            color: #5cb6f9;
            text-decoration: underline;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            gap: 15px;
        }

        .navigation-buttons.step1-only {
            justify-content: flex-end;
        }

        .btn {
            padding: 16px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: 'HK Grotesk', sans-serif;
        }

        .btn-primary {
            background: #12229d;
            color: white;
            box-shadow: 0 5px 15px rgba(18, 34, 157, 0.2);
        }

        .btn-primary:hover:not(:disabled) {
            background: #1a237e;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(18, 34, 157, 0.3);
        }

        .btn-primary:disabled {
            background: #b0b0b0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #050a30;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
            transform: translateY(-2px);
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #12229d;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .login-link:hover {
            color: #5cb6f9;
        }

        .form-note {
            font-size: 12px;
            color: rgba(5, 10, 48, 0.6);
            margin-top: 5px;
            display: block;
        }

        .password-strength {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 10px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            background: #e53935;
            transition: all 0.3s ease;
        }

        .strength-bar.medium {
            background: #ff9800;
        }

        .strength-bar.strong {
            background: #4caf50;
        }

        .summary-box {
            margin-top: 30px;
            padding: 20px;
            background: #f9fafd;
            border-radius: 10px;
            border: 1px solid rgba(5, 10, 48, 0.1);
        }

        .summary-box h4 {
            color: #050a30;
            margin-bottom: 15px;
            font-size: 18px;
        }

        #registration-summary {
            font-size: 14px;
            line-height: 1.8;
            color: rgba(5, 10, 48, 0.8);
        }

        #registration-summary div {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed rgba(5, 10, 48, 0.1);
        }

        #registration-summary div:last-child {
            border-bottom: none;
        }

        #registration-summary strong {
            color: #050a30;
            min-width: 150px;
            display: inline-block;
        }

        .error-message {
            color: #e53935;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .input-error {
            border-color: #e53935 !important;
        }

        .step-validation-message {
            background: #fff8e1;
            border-left: 4px solid #ff9800;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .registration-container {
                padding: 30px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .progress-indicator::before {
                left: 5%;
                right: 5%;
            }

            .step-label {
                font-size: 11px;
            }

            .navigation-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .registration-container {
                padding: 25px 20px;
            }
            
            .step-header h3 {
                font-size: 24px;
            }
            
            .btn {
                padding: 14px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <php if($success): ?>
            <div class="alert alert-success visible">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <php endif; ?>
        
        <php if($error): ?>
            <div class="alert alert-error visible">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <php endif; ?>
        
        
        <php if(!$success): ?>
        <form method="POST" action="" id="registration-form">
            <div class="progress-indicator">
                <div class="progress-step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Personal Data</div>
                </div>
                <div class="progress-step locked" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Account Details</div>
                </div>
                <div class="progress-step locked" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Confirmation</div>
                </div>
            </div>

            <div class="step-content active" id="step-1">
                <div class="step-header">
                    <h3>Personal Information</h3>
                    <p>Please provide your personal details to begin the registration</p>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="salutation" class="required">Salutation</label>
                        <select id="salutation" name="salutation" required>
                            <option value="">Please select</option>
                            <option value="mr">Mr.</option>
                            <option value="ms">Ms.</option>
                        </select>
                        <div class="error-message" id="salutation-error">Please select a salutation</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Title (Optional)</label>
                        <input type="text" id="title" name="title" placeholder="e.g. Prof., Dr.">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname" class="required">First Name</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Enter your first name" required>
                        <div class="error-message" id="firstname-error">Please enter your first name</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastname" class="required">Last Name</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Enter your last name" required>
                        <div class="error-message" id="lastname-error">Please enter your last name</div>
                    </div>
                </div>

                <div class="full-width">
                    <label for="birthdate" class="required">Date of Birth</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                    <span class="form-note">You must be at least 18 years old to register</span>
                    <div class="error-message" id="birthdate-error">Please enter a valid date of birth (must be at least 18 years old)</div>
                </div>

                <div class="full-width">
                    <label for="email" class="required">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="your.email@example.com" required>
                    <div class="error-message" id="email-error">Please enter a valid email address</div>
                </div>

                <div class="full-width">
                    <label for="phone" class="required">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your mobile number" required>
                    <div class="error-message" id="phone-error">Please enter a valid phone number</div>
                </div>

                <div class="navigation-buttons step1-only">
                    <button type="button" class="btn btn-primary" id="continue-step1" onclick="validateStep1()">
                        Continue to Account Details <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="step-content" id="step-2">
                <div class="step-header">
                    <h3>Account Setup</h3>
                    <p>Create your login credentials and security details</p>
                </div>

                <div class="full-width">
                    <label for="clientnumber" class="required">Customer Number</label>
                    <input type="text" id="clientnumber" name="clientnumber" placeholder="Enter your customer number" required>
                    <span class="form-note">You can find this on your contract or invoice</span>
                    <div class="error-message" id="clientnumber-error">Please enter your customer number</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="required">Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                        <div class="password-strength">
                            <div class="strength-bar" id="strength-bar"></div>
                        </div>
                        <span class="form-note">Minimum 8 characters with letters and numbers</span>
                        <div class="error-message" id="password-error">Password must be at least 8 characters with letters and numbers</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm-password" class="required">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Repeat your password" required>
                        <div class="error-message" id="confirm-password-error">Passwords do not match</div>
                    </div>
                </div>

                <div class="navigation-buttons">
                    <button type="button" class="btn btn-secondary" onclick="goBackToStep1()">
                        <i class="fas fa-arrow-left"></i> Back to Personal Data
                    </button>
                    <button type="button" class="btn btn-primary" id="continue-step2" onclick="validateStep2()">
                        Continue to Confirmation <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="step-content" id="step-3">
                <div class="step-header">
                    <h3>Terms & Confirmation</h3>
                    <p>Review and accept the terms to complete your registration</p>
                </div>

                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms" class="checkbox-label">
                            I agree to the <a href="#">Terms & Conditions</a> and confirm that I have read the <a href="#">Privacy Policy</a>.
                            <span class="required"></span>
                        </label>
                    </div>
                </div>

                <div class="navigation-buttons">
                    <button type="button" class="btn btn-secondary" onclick="goBackToStep2()">
                        <i class="fas fa-arrow-left"></i> Back to Account Details
                    </button>
                    <button type="submit" class="btn btn-primary" id="complete-registration">
                        <i class="fas fa-check"></i> Complete Registration
                    </button>
                </div>
            </div>
        </form>
        <php endif; ?>

        <a href="login.php" class="login-link">Already have an account? Log in here</a>
    </div>

    <script>
       
        let currentStep = 1;
        
        
        const registrationData = {
            step1: {},
            step2: {}
        };

       
        function updateProgressIndicator(step) {
            document.querySelectorAll('.progress-step').forEach((el, index) => {
                el.classList.remove('active', 'completed', 'locked');
                
                const stepNumber = index + 1;
                if (stepNumber < step) {
                    el.classList.add('completed');
                } else if (stepNumber === step) {
                    el.classList.add('active');
                } else {
                    el.classList.add('locked');
                }
            });
        }

        
        function goToStep(step) {
            
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            
          
            document.getElementById(`step-${step}`).classList.add('active');
            
         
            updateProgressIndicator(step);
            currentStep = step;
        }

      
        function validateStep1() {
            let isValid = true;
            
            
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelectorAll('.input-error').forEach(el => {
                el.classList.remove('input-error');
            });
            
           
            const requiredFields = ['salutation', 'firstname', 'lastname', 'birthdate', 'email', 'phone'];
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                const error = document.getElementById(`${field}-error`);
                
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('input-error');
                    error.style.display = 'block';
                }
            });
            
        
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (emailInput.value && !emailRegex.test(emailInput.value)) {
                isValid = false;
                emailInput.classList.add('input-error');
                emailError.style.display = 'block';
            }
            
            
            const birthdateInput = document.getElementById('birthdate');
            const birthdateError = document.getElementById('birthdate-error');
            
            if (birthdateInput.value) {
                const birthdate = new Date(birthdateInput.value);
                const today = new Date();
                const age = today.getFullYear() - birthdate.getFullYear();
                const monthDiff = today.getMonth() - birthdate.getMonth();
                
                if (age < 18 || (age === 18 && monthDiff < 0)) {
                    isValid = false;
                    birthdateInput.classList.add('input-error');
                    birthdateError.style.display = 'block';
                }
            }
            
            if (isValid) {
              
                registrationData.step1 = {
                    name: document.getElementById('firstname').value + ' ' + document.getElementById('lastname').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    birthdate: document.getElementById('birthdate').value
                };
                
                
                goToStep(2);
            }
            
            return false;
        }

       
        function validateStep2() {
            let isValid = true;
            
            
            document.querySelectorAll('.error-message').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelectorAll('.input-error').forEach(el => {
                el.classList.remove('input-error');
            });
            
            
            const requiredFields = ['clientnumber', 'password', 'confirm-password'];
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                const error = document.getElementById(`${field}-error`);
                
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('input-error');
                    error.style.display = 'block';
                }
            });
            
          
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('password-error');
            
            if (passwordInput.value) {
                if (passwordInput.value.length < 8) {
                    isValid = false;
                    passwordInput.classList.add('input-error');
                    passwordError.style.display = 'block';
                }
            }
            
           
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            const confirmError = document.getElementById('confirm-password-error');
            
            if (password && confirmPassword && password !== confirmPassword) {
                isValid = false;
                document.getElementById('confirm-password').classList.add('input-error');
                confirmError.style.display = 'block';
            }
            
            if (isValid) {
                
                registrationData.step2 = {
                    clientnumber: document.getElementById('clientnumber').value
                };
                
                
                goToStep(3);
            }
            
            return false;
        }

        document.getElementById('password')?.addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthBar = document.getElementById('strength-bar');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            let width = 0;
            let colorClass = '';
            
            switch(strength) {
                case 0:
                case 1:
                    width = 25;
                    colorClass = '';
                    break;
                case 2:
                    width = 50;
                    colorClass = 'medium';
                    break;
                case 3:
                    width = 75;
                    colorClass = 'strong';
                    break;
                case 4:
                    width = 100;
                    colorClass = 'strong';
                    break;
            }
            
            strengthBar.style.width = width + '%';
            strengthBar.className = 'strength-bar ' + colorClass;
        });

      
        function goBackToStep1() {
            goToStep(1);
        }

        function goBackToStep2() {
            goToStep(2);
        }

    
        document.addEventListener('DOMContentLoaded', function() {
            
            const today = new Date();
            const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            const birthdateInput = document.getElementById('birthdate');
            if (birthdateInput) {
                birthdateInput.max = maxDate.toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>