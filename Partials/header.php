<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_script = $_SERVER['SCRIPT_NAME'];
$current_page = basename($current_script, '.php');


$is_home_page = ($current_page == 'index' || $current_page == 'home' || 
                strpos($current_script, 'index.php') !== false || 
                strpos($current_script, 'home.php') !== false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>UltraWave</title>
    <style>
      
        .top-bar {
            background: linear-gradient(90deg, #0a1142 0%, #050a30 100%);
            padding: 8px 0;
            color: #cae8ff;
            font-size: 14px;
            border-bottom: 1px solid rgba(92, 182, 249, 0.2);
        }
        
        .top-bar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .top-bar .user-type {
            display: flex;
            gap: 20px;
        }
        
        .top-bar .user-type a {
            color: #cae8ff;
            text-decoration: none;
            padding: 4px 12px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .top-bar .user-type a:hover {
            background: rgba(92, 182, 249, 0.2);
            color: #ffffff;
        }
        
        .hotline {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .hotline-icon {
            color: #5cb6f9;
        }
        
        .top-bar strong {
            color: #ffffff;
        }
        
        
        .main-header {
            background: rgba(5, 10, 48, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid rgba(92, 182, 249, 0.3);
        }
        
        .main-header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 40px;
        }
        
        .logo a {
            color: #EAF2EF;
            font-size: 32px;
            font-weight: 800;
            text-decoration: none;
            background: linear-gradient(90deg, #5cb6f9 0%, #00AD93 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(92, 182, 249, 0.3);
        }
        
        /* Main Menu Fixes */
        .main-menu {
            display: flex;
            list-style: none;
            gap: 5px;
            margin: 0;
            padding: 0;
        }
        
        .main-menu > li > a {
            color: #cae8ff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .main-menu > li > a:hover,
        .main-menu > li > a.active {
            background: linear-gradient(135deg, rgba(92, 182, 249, 0.25) 0%, rgba(10, 17, 66, 0.4) 100%);
            color: #ffffff;
            box-shadow: 0 5px 15px rgba(92, 182, 249, 0.3);
        }
        
        
        .dropdown-menu {
            position: absolute;
            background: rgba(10, 17, 66, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(92, 182, 249, 0.3);
            border-radius: 10px;
            padding: 10px 0;
            min-width: 200px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 1001;
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .dropdown-menu li {
            margin: 0;
        }
        
        .dropdown-menu a {
            color: #cae8ff;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .dropdown-menu a:hover {
            background: rgba(92, 182, 249, 0.2);
            color: #ffffff;
            padding-left: 25px;
        }
        
        /* Header Icons Fixes */
        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .icon-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #cae8ff;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            min-width: 60px;
        }
        
        .icon-link:hover {
            background: rgba(92, 182, 249, 0.2);
            color: #ffffff;
            transform: translateY(-2px);
        }
        
        .header-icon {
            font-size: 20px;
            margin-bottom: 4px;
        }
        
        .icon-link span {
            font-size: 12px;
            font-weight: 600;
        }
        
     
        .search-container {
            position: relative;
        }
        
        .search-box {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(10, 17, 66, 0.95);
            border: 1px solid rgba(92, 182, 249, 0.3);
            border-radius: 10px;
            padding: 15px;
            width: 300px;
            display: none;
            z-index: 1001;
            backdrop-filter: blur(10px);
        }
        
        .search-container:hover .search-box {
            display: block;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(92, 182, 249, 0.3);
            border-radius: 8px;
            color: #ffffff;
            font-size: 14px;
            font-family: 'HK Grotesk', sans-serif;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #5cb6f9;
        }
        
        .search-btn {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #5cb6f9;
            cursor: pointer;
            font-size: 16px;
        }
        
       
        .user-menu-container {
            position: relative;
        }
        
        .user-info-dropdown {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            border-radius: 10px;
            background: rgba(92, 182, 249, 0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .user-info-dropdown:hover {
            background: rgba(92, 182, 249, 0.2);
            border-color: rgba(92, 182, 249, 0.3);
        }
        
        .user-avatar {
            font-size: 32px;
            color: #5cb6f9;
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        
        .user-name {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
        }
        
        .user-role {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        
        .admin-role-badge,
        .user-role-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        
        .admin-role-badge {
            background: linear-gradient(135deg, #FFD700 0%, #D4AF37 100%);
            color: #050A30;
        }
        
        .user-role-badge {
            background: linear-gradient(135deg, #5cb6f9 0%, #087CA7 100%);
            color: #ffffff;
        }
        
        .dropdown-arrow {
            color: #5cb6f9;
            font-size: 12px;
            transition: transform 0.3s ease;
        }
        
        .user-info-dropdown:hover .dropdown-arrow {
            transform: rotate(180deg);
        }
        
    
        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 320px;
            background: rgba(10, 17, 66, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(92, 182, 249, 0.3);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            display: none;
            z-index: 1001;
            overflow: hidden;
        }
        
        .user-menu-container:hover .user-dropdown-menu {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dropdown-header {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(5, 10, 48, 0.8);
            border-bottom: 1px solid rgba(92, 182, 249, 0.2);
        }
        
        .dropdown-avatar {
            font-size: 48px;
            color: #5cb6f9;
        }
        
        .dropdown-name {
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
        }
        
        .dropdown-email {
            color: #cae8ff;
            font-size: 13px;
            opacity: 0.8;
        }
        
        .dropdown-divider {
            height: 1px;
            background: rgba(92, 182, 249, 0.2);
            margin: 10px 0;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            color: #cae8ff;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .dropdown-item:hover {
            background: rgba(92, 182, 249, 0.1);
            color: #ffffff;
            border-left-color: #5cb6f9;
            padding-left: 25px;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
            color: #5cb6f9;
        }
        
        .dropdown-section {
            padding: 10px 20px;
        }
        
        .section-title {
            color: #5cb6f9;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .admin-item {
            padding: 12px 20px;
        }
        
        .admin-icon {
            width: 30px;
            height: 30px;
            background: rgba(92, 182, 249, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5cb6f9;
        }
        
        .item-title {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
        }
        
        .item-subtitle {
            color: #cae8ff;
            font-size: 11px;
            opacity: 0.7;
        }
        
        .logout-item {
            color: #ff6b6b !important;
        }
        
        .logout-item:hover {
            color: #ff5252 !important;
            background: rgba(255, 107, 107, 0.1) !important;
            border-left-color: #ff6b6b !important;
        }
        
        
        @media (max-width: 1024px) {
            .logo a {
                font-size: 28px;
            }
            
            .main-menu {
                gap: 2px;
            }
            
            .main-menu > li > a {
                padding: 10px 15px;
                font-size: 14px;
            }
            
            .user-dropdown-menu {
                width: 280px;
            }
        }
        
        @media (max-width: 768px) {
            .top-bar {
                padding: 5px 0;
                font-size: 12px;
            }
            
            .hotline {
                display: none;
            }
            
            .user-type {
                justify-content: center;
                width: 100%;
            }
            
            .main-menu {
                display: none; 
            }
            
            .header-left {
                gap: 20px;
            }
            
            .logo a {
                font-size: 24px;
            }
            
            .header-icons {
                gap: 15px;
            }
            
            .icon-link {
                padding: 6px 8px;
                min-width: 50px;
            }
            
            .icon-link span {
                font-size: 10px;
            }
            
            .header-icon {
                font-size: 18px;
            }
           
            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                color: #5cb6f9;
                font-size: 24px;
                cursor: pointer;
                padding: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }
            
            .logo a {
                font-size: 20px;
            }
            
            .user-info-dropdown {
                padding: 6px 10px;
            }
            
            .user-avatar {
                font-size: 24px;
            }
            
            .user-name {
                font-size: 12px;
            }
            
            .user-role-badge,
            .admin-role-badge {
                font-size: 9px;
                padding: 2px 6px;
            }
        }
    </style>
</head>
<body>
  
    <div class="top-bar">
        <div class="container">
            <div class="user-type">
                <a href="#">Private</a>
                <a href="#">Business</a>
            </div>
        
            <div class="hotline">
                <i class="fas fa-phone-alt hotline-icon"></i> Service-Hotline: <strong>0800 123 456</strong>
            </div>
        </div>
    </div>
    
    
    <header class="main-header">
        <div class="container">
            <div class="header-left">
                <div class="logo">
                    <a href="../index.php">Ultra<span>Wave</span></a>
                </div>
            
                <nav>
                    <ul class="main-menu">
                        <li><a href="../index.php" class="<?php echo $is_home_page ? 'active' : ''; ?>">Home</a></li>
                        <li class="dropdown">
                            <a href="#" class="<?php echo $current_page == 'internet-services' ? 'active' : ''; ?>">Services</a>
                            <ul class="dropdown-menu">
                                <li><a href="../Services/internet-services.php">Internet</a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo $is_home_page ? '#service-packages' : '../index.php#service-packages'; ?>">Offers</a></li>
                        <li><a href="../About/about.php" class="<?php echo $current_page == 'about' ? 'active' : ''; ?>">About Us</a></li>
                        <li><a href="../Contact/contact.php" class="<?php echo $current_page == 'contact' ? 'active' : ''; ?>">Contact</a></li>
                    </ul>
                </nav>
            </div>
        
            <div class="header-icons">
                
                <div class="search-container">
                    <a href="#" class="icon-link search-trigger">
                        <i class="fas fa-search header-icon"></i>
                        <span>Search</span>
                    </a>
                    
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Search for products, services, or help..." aria-label="Search">
                        <button class="search-btn">
                            <i class="fas fa-search search-icon"></i>
                        </button>
                    </div>
                </div>
                
              
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu-container">
                        <div class="user-info-dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="user-details">
                                <div class="user-name">
                                    <?php 
                                    $name = $_SESSION['full_name'] ?? 'User';
                                    $shortName = explode(' ', $name)[0];
                                    echo htmlspecialchars($shortName);
                                    ?>
                                </div>
                                <div class="user-role">
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <span class="admin-role-badge">
                                            <i class="fas fa-shield-alt"></i> ADMIN
                                        </span>
                                    <?php else: ?>
                                        <span class="user-role-badge">
                                            <i class="fas fa-user"></i> User
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                            
                       
                            <div class="user-dropdown-menu">
                                <div class="dropdown-header">
                                    <div class="dropdown-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="dropdown-user-info">
                                        <div class="dropdown-name"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></div>
                                        <div class="dropdown-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></div>
                                    </div>
                                </div>
                                
                                <div class="dropdown-divider"></div>
                                
                                <a href="../Account/profile.php" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    <span>My Profile</span>
                                </a>
                                
                                <a href="../Account/settings.php" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Account Settings</span>
                                </a>
                                
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                              
                                    <div class="dropdown-divider"></div>
                                    
                                    <div class="dropdown-section">
                                        <div class="section-title">Admin Panel</div>
                                        
                                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; padding: 0 20px 15px 20px;">
                                            <a href="../admin/dashboard.php" class="admin-panel-item">
                                                <div class="admin-icon-small">
                                                    <i class="fas fa-tachometer-alt"></i>
                                                </div>
                                                <div>
                                                    <div class="item-title-small">Dashboard</div>
                                                    <div class="item-subtitle-small">System overview</div>
                                                </div>
                                            </a>
                                            
                                            <a href="../admin/manage-posts.php" class="admin-panel-item">
                                                <div class="admin-icon-small">
                                                    <i class="fas fa-newspaper"></i>
                                                </div>
                                                <div>
                                                    <div class="item-title-small">Manage Content</div>
                                                    <div class="item-subtitle-small">Posts & pages</div>
                                                </div>
                                            </a>
                                            
                                            <a href="../admin/manage-contacts.php" class="admin-panel-item">
                                                <div class="admin-icon-small">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                                <div>
                                                    <div class="item-title-small">Messages</div>
                                                    <div class="item-subtitle-small">Contact messages</div>
                                                </div>
                                            </a>
                                            
                                            <a href="../admin/settings.php" class="admin-panel-item">
                                                <div class="admin-icon-small">
                                                    <i class="fas fa-sliders-h"></i>
                                                </div>
                                                <div>
                                                    <div class="item-title-small">Site Settings</div>
                                                    <div class="item-subtitle-small">Configure site</div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="dropdown-divider"></div>
                                
                                <a href="../Account/logout.php" class="dropdown-item logout-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="../Account/login.php" class="icon-link">
                        <i class="fas fa-user-circle header-icon"></i>
                        <span>Account</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <style>
        
        .admin-panel-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 12px 8px;
            background: rgba(92, 182, 249, 0.1);
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }
        
        .admin-panel-item:hover {
            background: rgba(92, 182, 249, 0.2);
            border-color: rgba(92, 182, 249, 0.3);
            transform: translateY(-2px);
        }
        
        .admin-icon-small {
            width: 35px;
            height: 35px;
            background: rgba(92, 182, 249, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5cb6f9;
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .item-title-small {
            color: #ffffff;
            font-weight: 600;
            font-size: 11px;
            line-height: 1.2;
            margin-bottom: 2px;
        }
        
        .item-subtitle-small {
            color: #cae8ff;
            font-size: 9px;
            opacity: 0.7;
        }
    </style>