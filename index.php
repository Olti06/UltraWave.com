<?php

require_once 'config/database.php';
require_once 'config/session.php';


try {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 3";
    $stmt = $db->query($query);
    $latest_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exeptionon $e) {
    $latest_posts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>UltraWave</title>

    
    <style>
      
        .user-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .user-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #0a1142;
            min-width: 180px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(92, 182, 249, 0.3);
        }
        
        .user-dropdown:hover .user-dropdown-content {
            display: block;
        }
        
        .user-dropdown-content a {
            color: #e0e0ff;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-dropdown-content a:hover {
            background-color: rgba(92, 182, 249, 0.2);
            color: white;
        }
        
        .user-dropdown-content a:last-child {
            border-bottom: none;
        }
        
        .user-name {
            color: #5cb6f9;
            font-weight: 600;
        }
        
        .admin-badge {
            background: #ff9800;
            color: #050a30;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <header>
        <div class="top-bar">
            <div class="user-type">
                <a href="#">Private</a>
                <a href="#">Business</a>
            </div>
        
            <div class="hotline">
                <i i class="fas fa-phone-alt hotline-icon"></i>Service-Hotline: <strong>0800 123 456</strong>
            </div>
        </div>
        
        <div class="main-header">
            <div class="header-left">
                <div class="logo">
                    <a href="index.php">UltraWave</a>
                </div>
            
            <nav>
                <ul class="main-menu">
                    <li><a href="index.php">Home</a></li>
                    <li class="dropdown">
                        <a href="#">Services</a>
                        <ul class="dropdown-menu">
                            <li><a href="Services/internet-services.php">Internet</a></li>
                        </ul>
                    </li>
                    <li><a href="#service-packages">Offers</a></li>
                  <li><a href="About/about.php">About Us</a></li>
                  <li><a href="Contact/contact.php">Contact</a></li>
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
                            <i class="fas fa-search header-icon"></i>
                        </button>
                    </div>
                </div>
                 <?php if (isLoggedIn()): ?>
    <div class="user-dropdown">
        <a href="#" class="icon-link">
            <i class="fas fa-user-circle header-icon"></i>
            <span class="user-name">
                <?php 
                $name = $_SESSION['full_name'] ?? 'User';
                $shortName = explode(' ', $name)[0];
                echo htmlspecialchars($shortName);
                ?>
                <?php if (isAdmin()): ?>
                    <span class="admin-badge">ADMIN</span>
                <?php endif; ?>
            </span>
        </a>
        <div class="user-dropdown-content">
            
            <?php if (isAdmin()): ?>
                <a href="admin/dashboard.php">
                    <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i> Admin Panel
                </a>
            <?php endif; ?>
            <a href="account/logout.php">
                <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i> Logout
            </a>
        </div>
    </div>
<?php else: ?>
    <a href="account/login.php" class="icon-link">
                 <i class="fas fa-user-circle header-icon"></i>
                    <span>Account</span>
                    </a>
         <?php endif; ?>
            </div>
        </div>
    </header>
    

    <section class="home-main">
        <div class="home-content">
            <div class="home-left">
                <div class="moto">
                    <h1>Gate that connects you across the universe</h1>
                </div>
            </div>
            
            <div class="home-right">
                <div class="call-to-action">
                    <h2>Ultra-Fast Fiber — €14.99/mo</h2>
                    <p class="promo-note">Special offer: <strong>€14.99/month for the first year</strong> on all our packages! <strong>24-month minimum contract period</strong>.</p>
                    <p>Experience lightning-fast internet with our newest packages. Limited time offer with exclusive benefits for new customers.</p>
                    <a href="#checkModal" class="cta-button">Check Availability</a>
                </div>
            </div>
        </div>
    </section>

    <div id="checkModal" class="modal">
        <div class="modal-content">
            <a href="#" class="close">&times;</a>
            <h3>Check Your Address</h3>
            <form action="/Contract/contract-creation.html" method="get">
                <div class="form-group">
                    <label for="street">Street Address:</label>
                    <input type="text" id="street" name="street" placeholder="Enter your street" required>
                </div>
                
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required>
                </div>
                
                <div class="form-group">
                    <label for="postal">Postal Code:</label>
                    <input type="text" id="postal" name="postal" placeholder="Enter postal code" required>
                </div>
                <a href="/Contract/contract-creation.html"><button type="submit" class="submit-btn">Check Availability</button></a>
                
            </form>
        </div>
    </div>

    <section class="service-packages" id="service-packages">
        <div class="container">
            <h2 class="section-title">Our Service Packages</h2>
            <p class="section-subtitle">Find the perfect plan for your needs - All packages require a 24-month minimum contract</p>
            
            <div class="packages-grid">
                
                <div class="package-card">
                    <div class="inner-glow"></div>
                    <div class="package-header">
                        <div class="contract-badge">24-Month Contract</div>
                        <h3 class="package-name">Basic</h3>
                        <div class="package-price">
                            <div class="promo-price">€14.99<span>/month for the first year</span></div>
                            <div class="regular-price">Then €29.99<span>/month</span></div>
                        </div>
                    </div>
                    <div class="package-features">
                        <ul>
                            <li>
                                <strong>Internet Speed:</strong> 
                                <span class="feature-value">100 Mbps</span>
                            </li>
                            <li>
                                <strong>Call Minutes:</strong> 
                                <span class="feature-value">100 min/mo included + €0.07/min</span>
                            </li>
                            <li>
                                <strong>International Calls:</strong> 
                                <span class="feature-value">€0.15/min</span>
                            </li>
                            <li>
                                <strong>TV Option:</strong> 
                                <span class="feature-value">Optional, book extra</span>
                            </li>
                        </ul>
                    </div>
                    <a href="#checkModal" class="package-button">Select Package</a>
                </div>
                
                <div class="package-card">
                    <div class="inner-glow"></div>
                    <div class="package-header">
                        <div class="contract-badge">24-Month Contract</div>
                        <h3 class="package-name">Perfect</h3>
                        <div class="package-price">
                            <div class="promo-price">€14.99<span>/month for the first year</span></div>
                            <div class="regular-price">Then €42.99<span>/month</span></div>
                        </div>
                    </div>
                    <div class="package-features">
                        <ul>
                            <li>
                                <strong>Internet Speed:</strong> 
                                <span class="feature-value">300 Mbps</span>
                            </li>
                            <li>
                                <strong>Call Minutes:</strong> 
                                <span class="feature-value">250 min/mo included + €0.07/min</span>
                            </li>
                            <li>
                                <strong>International Calls:</strong> 
                                <span class="feature-value">€0.15/min</span>
                            </li>
                            <li>
                                <strong>TV Option:</strong> 
                                <span class="feature-value">Optional, book extra</span>
                            </li>
                        </ul>
                    </div>
                    <a href="#checkModal" class="package-button">Select Package</a>
                </div>
                
                <div class="package-card">
                    <div class="inner-glow"></div>
                    <div class="package-header">
                        <div class="contract-badge">24-Month Contract</div>
                        <h3 class="package-name">Premium</h3>
                        <div class="package-price">
                            <div class="promo-price">€14.99<span>/month for the first year</span></div>
                            <div class="regular-price">Then €54.99<span>/month</span></div>
                        </div>
                    </div>
                    <div class="package-features">
                        <ul>
                            <li>
                                <strong>Internet Speed:</strong> 
                                <span class="feature-value">600 Mbps</span>
                            </li>
                            <li>
                                <strong>Call Minutes:</strong> 
                                <span class="feature-value">Unlimited calls</span>
                            </li>
                            <li>
                                <strong>International Calls:</strong> 
                                <span class="feature-value">€0.15/min</span>
                            </li>
                            <li>
                                <strong>TV Option:</strong> 
                                <span class="feature-value">Optional, book extra</span>
                            </li>
                        </ul>
                    </div>
                    <a href="#checkModal" class="package-button">Select Package</a>
                </div>
                
                <div class="package-card">
                    <div class="inner-glow"></div>
                    <div class="package-header">
                        <div class="contract-badge">24-Month Contract</div>
                        <h3 class="package-name">Ultra</h3>
                        <div class="package-price">
                            <div class="promo-price">€14.99<span>/month for the first year</span></div>
                            <div class="regular-price">Then €69.99<span>/month</span></div>
                        </div>
                    </div>
                    <div class="package-features">
                        <ul>
                            <li>
                                <strong>Internet Speed:</strong> 
                                <span class="feature-value">1000 Mbps</span>
                            </li>
                            <li>
                                <strong>Call Minutes:</strong> 
                                <span class="feature-value">Unlimited calls</span>
                            </li>
                            <li>
                                <strong>International Calls:</strong> 
                                <span class="feature-value">€0.08/min</span>
                            </li>
                            <li>
                                <strong>TV Option:</strong> 
                                <span class="feature-value">Optional, book extra</span>
                            </li>
                        </ul>
                    </div>
                    <a href="#checkModal" class="package-button highlight">Select Package</a>
                </div>
                
            </div>
            
            <div class="contract-note">
                <div class="contract-note-content">
                    <div class="contract-text">
                        <h4>Important Contract Information</h4>
                        <p>All our packages require a <strong>24-month minimum contract period</strong>. The promotional price of €14.99/month applies only for the first 12 months. After the first year, the standard monthly rate applies for the remaining 12 months of your contract. Early termination fees apply if you cancel before the contract ends.</p>
                    </div>
                </div>
            </div>
            
            <div class="package-change-option">
                <div class="package-change-content">
                    <div class="change-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="change-text">
                        <h4>Package Change Option</h4>
                        <p>We understand your needs may change. That's why we offer a <strong>one-time package change option</strong> during your contract:</p>
                        <ul>
                            <li>Change your package <strong>once</strong> during the first 12 months of your contract</li>
                            <li>Available only for upgrades or downgrades within our service packages</li>
                            <li>New contract period starts from the change date (remaining months carry over)</li>
                            <li>Promotional pricing may not apply to the new package</li>
                            <li>This option is <strong>available only once</strong> and must be used within the first year</li>
                        </ul>
                        <p class="change-note"><strong>Note:</strong> Package change option is not available after the first 12 months of your contract.</p>
                    </div>
                </div>
            </div>

            
            <section class="router-rental">
                <div class="container">
                    <div class="router-header">
                        <h3 class="router-title">Router Options</h3>
                        <p class="router-subtitle">Choose the right router for your UltraWave Fiber connection. Use your own compatible router or rent one from us.</p>
                    </div>

                    <div class="router-options">
                        <div class="router-card">
                            <div class="router-badge">Most Popular</div>
                            <div class="router-icon"><i class="fas fa-wifi"></i></div>
                            <h4 class="router-name">UltraRouter Plus</h4>
                            <div class="router-features">
                                <ul>
                                    <li><i class="fas fa-check"></i> Dual-band WiFi (2.4GHz & 5GHz)</li>
                                    <li><i class="fas fa-check"></i> Up to 1200 Mbps speed</li>
                                    <li><i class="fas fa-check"></i> 4 Gigabit Ethernet ports</li>
                                    <li><i class="fas fa-check"></i> Advanced security features</li>
                                    <li><i class="fas fa-check"></i> Parental controls</li>
                                </ul>
                            </div>
                            <div class="router-price">
                                <div class="rental-price">€2.99<span>/month rental</span></div>
                                <div class="contract-note">24-month contract required</div>
                            </div>
                            <a href="#" class="router-button">Select UltraRouter Plus</a>
                        </div>

                        <div class="router-card premium">
                            <div class="router-badge premium">Premium Choice</div>
                            <div class="router-icon"><i class="fas fa-wifi"></i></div>
                            <h4 class="router-name">UltraRouter High</h4>
                            <div class="router-features">
                                <ul>
                                    <li><i class="fas fa-check"></i> Tri-band WiFi (2.4GHz, 5GHz & 6GHz)</li>
                                    <li><i class="fas fa-check"></i> Up to 3000 Mbps speed</li>
                                    <li><i class="fas fa-check"></i> 8 Gigabit Ethernet ports</li>
                                    <li><i class="fas fa-check"></i> Mesh network compatible</li>
                                    <li><i class="fas fa-check"></i> Advanced QoS & gaming optimization</li>
                                    <li><i class="fas fa-check"></i> Built-in security suite</li>
                                </ul>
                            </div>
                            <div class="router-price">
                                <div class="rental-price">€4.99<span>/month rental</span></div>
                                <div class="contract-note">24-month contract required</div>
                            </div>
                            <a href="#" class="router-button">Select UltraRouter High</a>
                        </div>
                    </div>

                    <div class="router-note">
                        <div class="note-icon"><i class="fas fa-info-circle"></i></div>
                        <div class="note-text">
                            <h5>Important Information</h5>
                            <p>You can use your own router if it's compatible with Fiber Optic connections (supports PPPoE, VLAN tagging). However, for optimal performance and support, we recommend using our certified routers. Router rental requires a 24-month contract. All rented routers include free replacement in case of technical issues.</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="tv-options">
                <h2 class="section-title">TV Options for Modern Viewers</h2>
                <p class="section-subtitle">Stream what you love, when you want. Choose the perfect TV package for today's viewing habits.</p>
                
                <div class="tv-options-grid">

                    <div class="tv-option-card">
                        <div class="tv-option-header">
                            <div class="popular-badge">Most Flexible</div>
                            <h3 class="tv-option-name">TV Universe Prime</h3>
                            <div class="tv-option-price">
                                <div class="monthly-price">€5.99<span>/month</span></div>
                            </div>
                        </div>
                        <div class="tv-option-features">
                            <ul>
                                <li>
                                    <strong>Streaming Focus:</strong>
                                    <span>Access to 50+ streaming apps (Netflix, Prime, Disney+ compatible)</span>
                                </li>
                                <li>
                                    <strong>Live Channels:</strong>
                                    <span>80+ basic channels + news & lifestyle</span>
                                </li>
                                <li>
                                    <strong>Simultaneous Streams:</strong>
                                    <span>2 devices at once</span>
                                </li>
                                <li>
                                    <strong>Resolution:</strong>
                                    <span>Full HD (1080p)</span>
                                </li>
                                <li>
                                    <strong>Cloud DVR:</strong>
                                    <span>20 hours storage</span>
                                </li>
                            </ul>
                        </div>
                        <div class="tv-ideal-for">
                            <strong>Ideal for:</strong> Cord-cutters who mainly use streaming services
                        </div>
                        <a href="#" class="tv-option-button">Select Prime</a>
                    </div>
                    
                    <div class="tv-option-card">
                        <div class="tv-option-header">
                            <div class="popular-badge">Balanced Choice</div>
                            <h3 class="tv-option-name">TV Universe Air</h3>
                            <div class="tv-option-price">
                                <div class="monthly-price">€8.99<span>/month</span></div>
                            </div>
                        </div>
                        <div class="tv-option-features">
                            <ul>
                                <li>
                                    <strong>Streaming Focus:</strong>
                                    <span>All Prime features + 5 premium streaming apps included</span>
                                </li>
                                <li>
                                    <strong>Live Channels:</strong>
                                    <span>120+ channels including sports & movies</span>
                                </li>
                                <li>
                                    <strong>Simultaneous Streams:</strong>
                                    <span>3 devices at once</span>
                                </li>
                                <li>
                                    <strong>Resolution:</strong>
                                    <span>4K UHD on select content</span>
                                </li>
                                <li>
                                    <strong>Cloud DVR:</strong>
                                    <span>50 hours storage</span>
                                </li>
                                <li>
                                    <strong>Sports Pack:</strong>
                                    <span>Basic sports channels included</span>
                                </li>
                            </ul>
                        </div>
                        <div class="tv-ideal-for">
                            <strong>Ideal for:</strong> Families who mix streaming with live TV
                        </div>
                        <a href="#" class="tv-option-button">Select Air</a>
                    </div>
                    
                    <div class="tv-option-card gold-card">
                        <div class="gold-overlay"></div>
                        <div class="tv-option-header">
                            <div class="popular-badge gold-badge">Ultimate Experience</div>
                            <h3 class="tv-option-name gold-text">TV Universe Gold</h3>
                            <div class="tv-option-price">
                                <div class="monthly-price gold-price">€12.99<span>/month</span></div>
                            </div>
                        </div>
                        <div class="tv-option-features">
                            <ul>
                                <li>
                                    <strong>Streaming Focus:</strong>
                                    <span>All apps + 8K streaming capability</span>
                                </li>
                                <li>
                                    <strong>Live Channels:</strong>
                                    <span>200+ channels including ALL premium networks</span>
                                </li>
                                <li>
                                    <strong>Simultaneous Streams:</strong>
                                    <span>5 devices at once</span>
                                </li>
                                <li>
                                    <strong>Resolution:</strong>
                                    <span>4K UHD & 8K where available</span>
                                </li>
                                <li>
                                    <strong>Cloud DVR:</strong>
                                    <span>Unlimited storage</span>
                                </li>
                                <li>
                                    <strong>Sports Pack:</strong>
                                    <span>ALL sports channels + PPV events</span>
                                </li>
                                <li>
                                    <strong>International:</strong>
                                    <span>30+ international channel packs</span>
                                </li>
                            </ul>
                        </div>
                        <div class="tv-ideal-for gold-ideal">
                            <strong>Ideal for:</strong> Entertainment enthusiasts who want everything
                        </div>
                        <a href="#" class="tv-option-button gold-button">Select Gold</a>
                    </div>
                </div>

                <section class="installation-info">
                    <div class="container">
                        <h4>How to Start Watching</h4>
                        <div class="installation-options">
                            <div class="installation-option">
                                <h5>Option 1: Install Universe App (Free)</h5>
                                <p>Download the "Universe App" on your compatible smart device:</p>
                                <ul>
                                    <li><i class="fas fa-check"></i> Smart TVs (Samsung, LG, Android TV, Apple TV)</li>
                                    <li><i class="fas fa-check"></i> Smartphones & Tablets (iOS & Android)</li>
                                    <li><i class="fas fa-check"></i> Gaming Consoles (PlayStation, Xbox)</li>
                                    <li><i class="fas fa-check"></i> Streaming Devices (Roku, Fire TV)</li>
                                </ul>
                                <p class="app-note">Simply search for "Universe App" in your device's app store, install, and sign in with your UltraWave account.</p>
                            </div>

                            <div class="installation-option">
                                <h5>Option 2: UltraWave Stick (Recommended)</h5>
                                <div class="stick-price">
                                    <div class="price-breakdown">
                                        <span class="item">UltraWave Stick:</span> <span class="amount">€54.99</span><br>
                                        <span class="item">Shipping:</span> <span class="amount">€5.99</span><br>
                                        <span class="item total">Total One-time Cost:</span> <span class="amount total">€60.98</span>
                                    </div>
                                </div>
                                <p>Transform ANY TV into a smart TV with our dedicated streaming stick:</p>
                                <ul>
                                    <li><i class="fas fa-check"></i> Plug & play - works with any HDMI port</li>
                                    <li><i class="fas fa-check"></i> 4K HDR streaming with Dolby Atmos</li>
                                    <li><i class="fas fa-check"></i> Voice remote with Google Assistant</li>
                                    <li><i class="fas fa-check"></i> Pre-loaded with Universe App</li>
                                    <li><i class="fas fa-check"></i> Free 3-5 day shipping</li>
                                    <li><i class="fas fa-check"></i> 1-year warranty included</li>
                                </ul>
                                <p class="stick-note"><strong>Best for:</strong> Non-smart TVs or for the best streaming experience</p>
                            </div>
                        </div>

                        <div class="compatibility-note">
                            <h5>Important Notes:</h5>
                            <ul>
                                <li><i class="fas fa-exclamation-circle"></i> All TV packages require an active UltraWave internet subscription</li>
                                <li><i class="fas fa-exclamation-circle"></i> Universe App is available on devices running Android 8.0+ or iOS 14+</li>
                                <li><i class="fas fa-exclamation-circle"></i> The UltraWave Stick ships within 24 hours of ordering</li>
                                <li><i class="fas fa-exclamation-circle"></i> No long-term contract required for TV packages - cancel anytime</li>
                                <li><i class="fas fa-exclamation-circle"></i> TV package prices are in addition to your internet plan</li>
                            </ul>
                        </div>
                    </div>
                </section>
                
                <div class="online-contract-section">
                    <div class="online-contract-content">
                        <div class="contract-text">
                            <h3>Start your subscription with us quickly and easily by creating your contract online</h3>
                            <p>Create your contract online today and receive exclusive bonuses:</p>
                            <div class="bonus-grid">
                                <div class="bonus-item">
                                    <div class="bonus-amount">€20</div>
                                    <div class="bonus-package">Basic Package</div>
                                </div>
                                <div class="bonus-item">
                                    <div class="bonus-amount">€30</div>
                                    <div class="bonus-package">Perfect Package</div>
                                </div>
                                <div class="bonus-item">
                                    <div class="bonus-amount">€40</div>
                                    <div class="bonus-package">Premium Package</div>
                                </div>
                                <div class="bonus-item">
                                    <div class="bonus-amount">€60</div>
                                    <div class="bonus-package">Ultra Package</div>
                                </div>
                            </div>
                            <p class="bonus-note">Online contract creation bonus is applied as a credit to your first bill.</p>
                            <a href="#checkModal" class="online-contract-button">Create Online Contract</a>
                        </div>
                        <div class="contract-image">
                            <i class="fas fa-file-contract contract-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="add-ons">
        <div class="container">
            <h2 class="section-title">Add-ons & Optional Services</h2>
            <p class="section-subtitle">Enhance your package with these optional services to customize your experience</p>
            
            <div class="addons-grid">
                <div class="addon-card">
                    <div class="addon-icon"><i class="fas fa-film"></i></div>
                    <h3 class="addon-title">Extra TV Pack</h3>
                    <p class="addon-description">Premium Movies, Sports, International Channels and specialized content packages.</p>
                    <div class="addon-price">From €4.99/month</div>
                </div>
                
                <div class="addon-card">
                    <div class="addon-icon"><i class="fas fa-phone-volume"></i></div>
                    <h3 class="addon-title">Extra Phone Minutes</h3>
                    <p class="addon-description">Additional national and international calling minutes beyond your package limits.</p>
                    <div class="addon-price">From €2.99/month</div>
                </div>
                
                <div class="addon-card">
                    <div class="addon-icon"><i class="fas fa-network-wired"></i></div>
                    <h3 class="addon-title">Static IP Address</h3>
                    <p class="addon-description">Essential for business applications, gaming servers, or remote access needs.</p>
                    <div class="addon-price">€9.99/month</div>
                </div>
                
                <div class="addon-card">
                    <div class="addon-icon"><i class="fas fa-cloud"></i></div>
                    <h3 class="addon-title">Business Tools</h3>
                    <p class="addon-description">Cloud Storage, VPN services, VoIP enhancements and business productivity tools.</p>
                    <div class="addon-price">From €14.99/month</div>
                </div>
                
                <div class="addon-card">
                    <div class="addon-icon"><i class="fas fa-wifi"></i></div>
                    <h3 class="addon-title">Extenders</h3>
                    <p class="addon-description">Latest technology extenders optimized for maximum WiFi coverage and performance.</p>
                    <div class="addon-price">€3.99/month</div>
                </div>
                
                <div class="addon-card">
                    <div class="addon-icon"><i class="fas fa-headset"></i></div>
                    <h3 class="addon-title">Priority Technical Support</h3>
                    <p class="addon-description">24/7 priority access to technical support with faster response times.</p>
                    <div class="addon-price">€7.99/month</div>
                </div>
            </div>
        </div>
    </section>

    <section class="why-choose-us">
        <div class="container">
            <h2 class="section-title">Why Choose UltraWave</h2>
            <p class="section-subtitle">Experience the difference with our premium services and exceptional customer care</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <h3 class="feature-title">High Speed</h3>
                    <p class="feature-description">Experience blazing-fast speeds for all your online needs, from streaming to gaming and remote work.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3 class="feature-title">Reliable Connection</h3>
                    <p class="feature-description">Enjoy a stable and secure network at all times with our advanced infrastructure and backup systems.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-headset"></i></div>
                    <h3 class="feature-title">24/7 Support</h3>
                    <p class="feature-description">Our dedicated support team is always here to help, day or night, with any questions or issues.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-home"></i><i class="fas fa-building"></i></div>
                    <h3 class="feature-title">For Home & Business</h3>
                    <p class="feature-description">We offer custom solutions for private customers and corporate clients, tailored to your specific needs.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="business-solutions">
        <div class="container">
            <div class="business-content">
                <div class="business-text">
                    <h2 class="business-title">Business Solutions</h2>
                    <p class="business-subtitle">We provide enterprise-grade connectivity for small businesses, corporations, and organizations.</p>
                    
                    <div class="solutions-list">
                        <div class="solution-item">
                            <div class="solution-icon"><i class="fas fa-globe"></i></div>
                            <div class="solution-details">
                                <h4>Dedicated Business Internet</h4>
                                <p>High-speed, reliable internet with guaranteed uptime and business-class support.</p>
                            </div>
                        </div>
                        
                        <div class="solution-item">
                            <div class="solution-icon"><i class="fas fa-phone-alt"></i></div>
                            <div class="solution-details">
                                <h4>VoIP Solutions</h4>
                                <p>Modern phone systems with advanced features for efficient business communication.</p>
                            </div>
                        </div>
                        
                        <div class="solution-item">
                            <div class="solution-icon"><i class="fas fa-tv"></i></div>
                            <div class="solution-details">
                                <h4>Corporate TV Services</h4>
                                <p>Custom TV packages for waiting areas, lobbies, and corporate entertainment.</p>
                            </div>
                        </div>
                        
                        <div class="solution-item">
                            <div class="solution-icon"><i class="fas fa-network-wired"></i></div>
                            <div class="solution-details">
                                <h4>Custom Network Infrastructure</h4>
                                <p>Tailored network solutions including Wi-Fi, security, and data management.</p>
                            </div>
                        </div>
                        
                        <div class="solution-item">
                            <div class="solution-icon"><i class="fas fa-user-tie"></i></div>
                            <div class="solution-details">
                                <h4>Professional Installation & Technical Support</h4>
                                <p>Expert installation and ongoing technical support for your business needs.</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#" class="business-button">Explore Business Solutions</a>
                </div>
                
                <div class="business-image">
                    <i class="fas fa-building building-icon"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="customer-portal">
        <div class="container">
            <div class="portal-content">
                <div class="portal-text">
                    <h2 class="portal-title">UltraWave Customer Portal</h2>
                    <p class="portal-subtitle">Manage your contract and technical matters 24/7 with our online customer portal</p>
                    
                    <div class="portal-features">
                        <div class="portal-feature">
                            <div class="portal-feature-icon"><i class="fas fa-user-circle"></i></div>
                            <div class="portal-feature-text">
                                <h4>Personal Account Management</h4>
                                <p>Access and manage your personal information, billing details, and subscription settings anytime.</p>
                            </div>
                        </div>
                        
                        <div class="portal-feature">
                            <div class="portal-feature-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div class="portal-feature-text">
                                <h4>Bill Payment & History</h4>
                                <p>View current bills, payment history, download invoices, and set up automatic payments.</p>
                            </div>
                        </div>
                        
                        <div class="portal-feature">
                            <div class="portal-feature-icon"><i class="fas fa-tools"></i></div>
                            <div class="portal-feature-text">
                                <h4>Technical Support & Troubleshooting</h4>
                                <p>Submit support tickets, track their status, and access troubleshooting guides for common issues.</p>
                            </div>
                        </div>
                        
                        <div class="portal-feature">
                            <div class="portal-feature-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="portal-feature-text">
                                <h4>Usage Monitoring</h4>
                                <p>Track your internet usage, data consumption, and network performance in real-time.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="portal-note">
                        <p><strong>Access your portal 24/7 from any device:</strong> Simply log in to manage all aspects of your UltraWave services.</p>
                    </div>
                    
                    <div class="portal-buttons">
                        <a href="/Account/login.html" class="portal-button primary" target="_blank">
                            <i class="fas fa-sign-in-alt"></i> Login to Customer Portal
                        </a>
                        <a href="#" class="portal-button secondary">
                            <i class="fas fa-question-circle"></i> Portal Guide & FAQ
                        </a>
                    </div>
                </div>
                
                <div class="portal-image">
                    <div class="portal-devices">
                        <div class="device laptop">
                            <i class="fas fa-laptop"></i>
                        </div>
                        <div class="device tablet">
                            <i class="fas fa-tablet-alt"></i>
                        </div>
                        <div class="device phone">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                    </div>