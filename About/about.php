<?php
require_once '../config/database.php';
require_once '../config/session.php';

$database = new Database();
$db = $database->getConnection();


$about_title = 'About UltraWave';
$about_content = '<p>UltraWave is a leading internet service provider dedicated to connecting people across the globe with high-speed, reliable internet services. Our mission is to bridge the digital divide and provide seamless connectivity solutions for homes and businesses.</p>';


try {
    $query = "SELECT * FROM page_content WHERE page_name = 'about'";
    $stmt = $db->query($query);
    
    if ($stmt && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $about_title = $row['title'] ?? $about_title;
        $about_content = $row['content'] ?? $about_content;
    }
} catch (PDOException $e) {

    error_log("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>About Us - UltraWave</title>
    <style>
        .about-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .about-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .about-header h1 {
            color: #EAF2EF;
            font-size: 48px;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #00EEFF, #00AD93);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .about-content {
            background: linear-gradient(135deg, rgba(0, 238, 255, 0.1) 0%, rgba(0, 238, 255, 0.05) 100%);
            padding: 60px;
            border-radius: 20px;
            border: 2px solid rgba(0, 238, 255, 0.2);
            margin-bottom: 60px;
        }
        
        .about-content h2 {
            color: #00EEFF;
            font-size: 32px;
            margin-bottom: 30px;
        }
        
        .about-content p {
            color: #EAF2EF;
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 25px;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(18, 34, 157, 0.3) 0%, rgba(5, 10, 48, 0.5) 100%);
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            border: 2px solid rgba(0, 238, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            border-color: rgba(0, 238, 255, 0.4);
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: 800;
            color: #00EEFF;
            margin-bottom: 15px;
        }
        
        .stat-label {
            color: #EAF2EF;
            font-size: 18px;
            font-weight: 600;
        }
        
        .edit-btn {
            display: inline-block;
            background: linear-gradient(135deg, #00AD93 0%, #00EEFF 100%);
            color: #050A30;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        
        .edit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 173, 147, 0.3);
        }
    </style>
</head>
<body>
    <?php include '../partials/header.php'; ?>
    
    <main class="about-section">
        <div class="about-header">
            <h1><?php echo htmlspecialchars($about_title); ?></h1>
            <div class="section-divider"></div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">50,000+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Customer Support</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">99.9%</div>
                <div class="stat-label">Uptime Guarantee</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-number">15+</div>
                <div class="stat-label">Years Experience</div>
            </div>
        </div>
        
        <div class="about-content">
            <?php echo $about_content; ?>
            
            <h2>Our Mission</h2>
            <p>To provide reliable, high-speed internet connectivity that empowers individuals, businesses, and communities to thrive in the digital age.</p>
            
            <h2>Our Values</h2>
            <p><strong>Innovation:</strong> Constantly evolving our technology to provide the best service.</p>
            <p><strong>Reliability:</strong> Ensuring 99.9% uptime for all our customers.</p>
            <p><strong>Customer Focus:</strong> Putting our customers at the center of everything we do.</p>
            
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="../admin/edit-page.php?page=about" class="edit-btn">
                    <i class="fas fa-edit"></i> Edit This Page
                </a>
            <?php endif; ?>
        </div>
    </main>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>