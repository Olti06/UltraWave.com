<?php
require_once '../config/database.php';
require_once '../config/session.php';

$database = new Database();
$db = $database->getConnection();

$products = [];

try {
    $query = "SELECT * FROM posts WHERE category = 'product' ORDER BY created_at DESC";
    $stmt = $db->query($query);
    $products = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (PDOException $e) {
    error_log("Internet services error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Internet Services - UltraWave</title>
    <style>
        .internet-hero {
            background: linear-gradient(135deg, 
                rgba(5, 10, 48, 0.95) 0%,
                rgba(92, 182, 249, 0.15) 100%);
            padding: 120px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .internet-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent 0%, 
                #00EEFF 25%, 
                #00AD93 50%, 
                #EAF2EF 75%, 
                transparent 100%);
            box-shadow: 0 0 20px rgba(0, 238, 255, 0.3);
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            color: #EAF2EF;
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background: linear-gradient(90deg, #00EEFF, #00AD93, #EAF2EF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            color: #EAF2EF;
            font-size: 22px;
            margin-bottom: 50px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            opacity: 0.9;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 60px;
            margin-top: 60px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            border: 2px solid rgba(0, 238, 255, 0.3);
            min-width: 180px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(0, 238, 255, 0.5);
            box-shadow: 0 10px 25px rgba(0, 238, 255, 0.2);
        }

        .stat-number {
            color: #00EEFF;
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(0, 238, 255, 0.3);
        }

        .stat-label {
            color: #EAF2EF;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .products-section {
            max-width: 1200px;
            margin: 80px auto;
            padding: 0 20px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title {
            color: #EAF2EF;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 3px;
            background: linear-gradient(90deg, #00EEFF, #00AD93);
            border-radius: 2px;
        }
        
        .section-intro {
            color: #EAF2EF;
            font-size: 20px;
            text-align: center;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
        }
        
        .product-card {
            background: linear-gradient(135deg, rgba(0, 238, 255, 0.1) 0%, rgba(0, 238, 255, 0.05) 100%);
            border-radius: 20px;
            padding: 40px 30px;
            border: 2px solid rgba(0, 238, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            border-color: rgba(0, 238, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 238, 255, 0.1);
        }
        
        .product-card h3 {
            color: #00EEFF;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .product-card p {
            color: #EAF2EF;
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .what-is-internet {
            padding: 100px 20px;
            background: linear-gradient(135deg, 
                rgba(5, 10, 48, 0.98) 0%, 
                rgba(18, 34, 157, 0.85) 50%,
                rgba(5, 10, 48, 0.98) 100%);
        }
        
        .internet-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 40px 0;
        }
        
        .feature-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 15px;
            border: 2px solid rgba(0, 238, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
            border-color: rgba(0, 238, 255, 0.4);
        }
    </style>
</head>
<body>
    <?php include '../partials/header.php'; ?>
    
    <section class="internet-hero">
        <div class="hero-content">
            <h1 class="hero-title">UltraWave Internet Services</h1>
            <p class="hero-subtitle">Experience lightning-fast fiber optic internet with speeds up to 1000 Mbps. Perfect for streaming, gaming, and working from home.</p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">1000 Mbps</div>
                    <div class="stat-label">Max Speed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="section-header">
            <h2 class="section-title">Our Internet Packages</h2>
            <div class="section-divider"></div>
            <p class="section-intro">Choose the perfect internet package for your needs</p>
        </div>
        
        <?php if(!empty($products)): ?>
            <div class="products-grid">
                <?php foreach($products as $product): ?>
                <div class="product-card">
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p><?php echo htmlspecialchars($product['content']); ?></p>
                    
                    <?php if(!empty($product['image_url'])): ?>
                    <div style="text-align: center; margin-top: 20px;">
                        <img src="../assets/images/<?php echo htmlspecialchars($product['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($product['title']); ?>"
                             style="max-width: 100%; border-radius: 10px; border: 2px solid rgba(0, 238, 255, 0.3);">
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px; background: rgba(255,255,255,0.05); border-radius: 20px;">
                <p style="color: #EAF2EF; font-size: 18px;">No internet packages available at the moment.</p>
            </div>
        <?php endif; ?>
    </section>
    
    <section class="what-is-internet">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div class="section-header">
                <h2 class="section-title">Why Choose UltraWave?</h2>
                <div class="section-divider"></div>
                <p class="section-intro">Discover the benefits of our premium internet services</p>
            </div>
            
            <div class="internet-features">
                <div class="feature-item">
                    <h3 style="color: #00EEFF; margin-bottom: 15px;">Lightning Fast Speeds</h3>
                    <p style="color: #EAF2EF; opacity: 0.9;">Enjoy download speeds up to 1000 Mbps with our fiber optic technology.</p>
                </div>
                
                <div class="feature-item">
                    <h3 style="color: #00EEFF; margin-bottom: 15px;">Secure Connection</h3>
                    <p style="color: #EAF2EF; opacity: 0.9;">Advanced security features to protect your data and privacy.</p>
                </div>
                
                <div class="feature-item">
                    <h3 style="color: #00EEFF; margin-bottom: 15px;">24/7 Support</h3>
                    <p style="color: #EAF2EF; opacity: 0.9;">Our technical team is available round the clock to assist you.</p>
                </div>
            </div>
        </div>
    </section>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>