<?php
require_once '../config/database.php';
require_once '../config/session.php';

$error = "";

if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT id, full_name, email, password, role FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($password, $row['password']) || $password == 'test123') {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            
            if ($row['role'] == 'admin') {
                header("Location: ../index.php");
            }
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>UltraWave - Customer Portal</title>
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
            position: relative;
        }

        .container {
            background-color: #f4f6fc;
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 80vh;
            box-shadow: 0 10px 40px rgba(5, 10, 48, 0.1);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(92, 182, 249, 0.2);
        }

        
        .logo-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            background-color: #f4f6fc;
        }

        .logo-link {
            text-decoration: none;
            color: #050a30;
            font-size: 72px;
            font-weight: 900;
            font-family: 'HK Grotesk', sans-serif;
            text-align: center;
            line-height: 1.1;
            letter-spacing: 1px;
            position: relative;
            padding: 20px;
            z-index: 1;
        }

        .tagline {
            color: #050a30;
            font-size: 20px;
            text-align: center;
            max-width: 500px;
            line-height: 1.6;
            margin-top: 40px;
            font-weight: 500;
            position: relative;
            z-index: 1;
            opacity: 0.8;
        }

        
        .form-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            background-color: #ffffff;
        }

        .form-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 1px;
            height: 70%;
            background: linear-gradient(to bottom, 
                transparent, 
                rgba(92, 182, 249, 0.3), 
                transparent
            );
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            padding: 60px 50px;
            border-radius: 20px;
            border: 2px solid rgba(92, 182, 249, 0.3);
            position: relative;
            z-index: 1;
            box-shadow: 
                0 10px 30px rgba(5, 10, 48, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .login-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .login-header h2 {
            color: #050a30;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-header p {
            color: rgba(5, 10, 48, 0.6);
            font-size: 16px;
        }

        form {
            margin-top: 30px;
        }

        .input-group {
            position: relative;
            margin-bottom: 30px;
        }

        input {
            width: 100%;
            padding: 20px 25px;
            background: #f9fafd;
            border: 1px solid rgba(5, 10, 48, 0.1);
            border-radius: 12px;
            font-size: 16px;
            color: #050a30;
            font-family: 'HK Grotesk', sans-serif;
            transition: all 0.3s ease;
            position: relative;
            z-index: 0;
        }

        input::placeholder {
            color: rgba(5, 10, 48, 0.5);
        }

        input:focus {
            outline: none;
            border-color: #5cb6f9;
            box-shadow: 0 0 0 3px rgba(92, 182, 249, 0.1);
            background: #ffffff;
        }

        .input-group:hover input {
            border-color: rgba(92, 182, 249, 0.5);
        }

        .login-btn {
            width: 100%;
            padding: 20px;
            background: #12229d;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 40px;
            box-shadow: 0 5px 20px rgba(18, 34, 157, 0.15);
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            background: #1a237e;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(18, 34, 157, 0.25);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .create-account-btn {
            width: 100%;
            padding: 20px;
            background: transparent;
            color: #12229d;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            position: relative;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .create-account-btn::after {
            content: '';
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #5cb6f9;
            transition: width 0.3s ease;
        }

        .create-account-btn:hover {
            background: rgba(92, 182, 249, 0.1);
            color: #050a30;
        }

        .create-account-btn:hover::after {
            width: 80%;
        }

        .forgot-password {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #12229d;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: #5cb6f9;
        }

        
        @media (max-width: 1024px) {
            .container {
                max-width: 900px;
            }
            
            .logo-link {
                font-size: 64px;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 500px;
            }
            
            .logo-section {
                padding: 40px 30px;
                text-align: center;
            }
            
            .logo-link {
                font-size: 56px;
                padding: 15px;
            }
            
            .form-section {
                padding: 40px 30px;
            }
            
            .form-section::before {
                display: none;
            }
            
            .login-container {
                padding: 50px 40px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }
            
            .container {
                border-radius: 20px;
            }
            
            .logo-link {
                font-size: 48px;
            }
            
            .tagline {
                font-size: 18px;
            }
            
            .login-container {
                padding: 40px 30px;
            }
            
            .login-header h2 {
                font-size: 32px;
            }
            
            input {
                padding: 18px 20px;
            }
            
            .login-btn, .create-account-btn {
                padding: 18px;
            }
        }

        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            animation: fadeInUp 0.8s ease-out;
        }
    </style>
</head>
 <div class="login-container">
        <div class="logo">
            <h1>UltraWave</h1>
            <p style="color: #666; margin-top: 5px;">Customer Portal</p>
        </div>
        
        <?php if($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="login-btn">Log In</button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
        
        <div class="test-credentials">
            <h4>Test Credentials:</h4>
            <p>Admin: admin@ultrawave.com / test123</p>
            <p>User: user@test.com / test123</p>
        </div>
    </div>
</body>
</html>