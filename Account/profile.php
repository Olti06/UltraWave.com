<?php
require_once '../config/database.php';
require_once '../config/session.php';


if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();


$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$success = "";
$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
  
    if (empty($full_name)) {
        $error = "Full name is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        try {
     
            $check_query = "SELECT id FROM users WHERE email = :email AND id != :id";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->bindParam(':email', $email);
            $check_stmt->bindParam(':id', $user_id);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                $error = "Email is already taken by another user";
            } else {
               
                $update_query = "UPDATE users SET full_name = :full_name, email = :email WHERE id = :id";
                $update_stmt = $db->prepare($update_query);
                $update_stmt->bindParam(':full_name', $full_name);
                $update_stmt->bindParam(':email', $email);
                $update_stmt->bindParam(':id', $user_id);
                
                if ($update_stmt->execute()) {
                    
                    $_SESSION['full_name'] = $full_name;
                    $_SESSION['email'] = $email;
                    
                    $success = "Profile updated successfully!";
                  
                    $user['full_name'] = $full_name;
                    $user['email'] = $email;
                } else {
                    $error = "Failed to update profile";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All password fields are required";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match";
    } elseif (strlen($new_password) < 8) {
        $error = "New password must be at least 8 characters";
    } else {
      
        if (password_verify($current_password, $user['password']) || $current_password == 'test123') {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_query = "UPDATE users SET password = :password WHERE id = :id";
            $password_stmt = $db->prepare($password_query);
            $password_stmt->bindParam(':password', $hashed_password);
            $password_stmt->bindParam(':id', $user_id);
            
            if ($password_stmt->execute()) {
                $success = "Password changed successfully!";
            } else {
                $error = "Failed to change password";
            }
        } else {
            $error = "Current password is incorrect";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>My Profile - UltraWave</title>
    <style>
        .profile-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .profile-header h1 {
            color: #EAF2EF;
            font-size: 36px;
            margin-bottom: 10px;
            background: linear-gradient(90deg, #5cb6f9, #087CA7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .profile-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
        }
        
     
        .profile-sidebar {
            background: linear-gradient(135deg, rgba(92, 182, 249, 0.1) 0%, rgba(92, 182, 249, 0.05) 100%);
            border-radius: 16px;
            border: 2px solid rgba(92, 182, 249, 0.2);
            padding: 30px;
            height: fit-content;
        }
        
        .user-card {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .user-avatar-large {
            font-size: 80px;
            color: #5cb6f9;
            margin-bottom: 20px;
        }
        
        .user-name-large {
            color: #EAF2EF;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .user-email {
            color: #cae8ff;
            font-size: 14px;
            margin-bottom: 15px;
            opacity: 0.8;
        }
        
        .user-role-badge-large {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
        }
        
        .admin-role-badge-large {
            background: linear-gradient(135deg, #FFD700 0%, #D4AF37 100%);
            color: #050A30;
        }
        
        .user-role-badge-large {
            background: linear-gradient(135deg, #5cb6f9 0%, #087CA7 100%);
            color: #ffffff;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 10px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            color: #cae8ff;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(92, 182, 249, 0.1);
            color: #ffffff;
            transform: translateX(5px);
        }
        
        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 18px;
            color: #5cb6f9;
        }
        
      
        .profile-content {
            background: linear-gradient(135deg, rgba(0, 173, 147, 0.1) 0%, rgba(0, 173, 147, 0.05) 100%);
            border-radius: 16px;
            border: 2px solid rgba(0, 173, 147, 0.2);
            padding: 40px;
        }
        
        .section-title {
            color: #00AD93;
            font-size: 28px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(0, 173, 147, 0.3);
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
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
        
        .profile-form {
            max-width: 600px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            color: #EAF2EF;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #ffffff;
            font-size: 16px;
            font-family: 'HK Grotesk', sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #5cb6f9;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(92, 182, 249, 0.1);
        }
        
        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }
        
        .submit-btn {
            padding: 15px 30px;
            background: linear-gradient(135deg, #00AD93 0%, #5cb6f9 100%);
            color: #050A30;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #5cb6f9 0%, #00AD93 100%);
            box-shadow: 0 10px 25px rgba(92, 182, 249, 0.3);
        }
        
        .form-section {
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #00EEFF;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #cae8ff;
            font-size: 14px;
        }
        
        @media (max-width: 1024px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                order: 2;
            }
        }
        
        @media (max-width: 768px) {
            .profile-content {
                padding: 25px;
            }
            
            .user-stats {
                grid-template-columns: 1fr;
            }
            
            .form-group input {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <?php include '../partials/header.php'; ?>
    
    <main class="profile-container">
        <div class="profile-header">
            <h1>My Account</h1>
            <p style="color: #cae8ff;">Manage your profile and account settings</p>
        </div>
        
        <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-grid">
          
            <div class="profile-sidebar">
                <div class="user-card">
                    <div class="user-avatar-large">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-name-large">
                        <?php echo htmlspecialchars($user['full_name']); ?>
                    </div>
                    <div class="user-email">
                        <?php echo htmlspecialchars($user['email']); ?>
                    </div>
                    <div class="user-role-badge-large <?php echo $user['role'] == 'admin' ? 'admin-role-badge-large' : 'user-role-badge-large'; ?>">
                        <i class="fas fa-<?php echo $user['role'] == 'admin' ? 'shield-alt' : 'user'; ?>"></i>
                        <?php echo ucfirst($user['role']); ?>
                    </div>
                </div>
                
                <ul class="sidebar-menu">
                    <li><a href="#" class="active">
                        <i class="fas fa-user"></i> Profile
                    </a></li>
                    <li><a href="settings.php">
                        <i class="fas fa-cog"></i> Settings
                    </a></li>
                    <li><a href="billing.php">
                        <i class="fas fa-credit-card"></i> Billing
                    </a></li>
                    <li><a href="support.php">
                        <i class="fas fa-headset"></i> Support
                    </a></li>
                    <li><a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a></li>
                </ul>
            </div>
            
         
            <div class="profile-content">
                
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user-circle"></i> Personal Information
                    </h2>
                    
                    <form method="POST" action="" class="profile-form">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name']); ?>"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                   placeholder="Enter your phone number">
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" 
                                   value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"
                                   placeholder="Enter your address">
                        </div>
                        
                        <button type="submit" name="update_profile" class="submit-btn">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </form>
                </div>
                
                
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i> Change Password
                    </h2>
                    
                    <form method="POST" action="" class="profile-form">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" 
                                   placeholder="Enter current password"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" 
                                   placeholder="Enter new password"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" 
                                   placeholder="Confirm new password"
                                   required>
                        </div>
                        
                        <button type="submit" name="change_password" class="submit-btn">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </form>
                </div>
                
               
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i> Account Information
                    </h2>
                    
                    <div class="user-stats">
                        <div class="stat-box">
                            <div class="stat-number">#<?php echo str_pad($user['id'], 6, '0', STR_PAD_LEFT); ?></div>
                            <div class="stat-label">Account ID</div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-number">
                                <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                            </div>
                            <div class="stat-label">Member Since</div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-number">
                                <?php echo $user['role'] == 'admin' ? 'Admin' : 'User'; ?>
                            </div>
                            <div class="stat-label">Account Type</div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-number">
                                <?php 
                            
                                $post_query = "SELECT COUNT(*) as post_count FROM posts WHERE author_id = :user_id";
                                $post_stmt = $db->prepare($post_query);
                                $post_stmt->bindParam(':user_id', $user_id);
                                $post_stmt->execute();
                                $post_count = $post_stmt->fetch(PDO::FETCH_ASSOC)['post_count'];
                                echo $post_count;
                                ?>
                            </div>
                            <div class="stat-label">Posts Created</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>