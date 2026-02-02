<?php
require_once '../config/database.php';
require_once '../config/session.php';

$database = new Database();
$db = $database->getConnection();

$success = "";
$error = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    

    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    if (empty($errors)) {
        try {
            $query = "INSERT INTO contacts (name, email, message, status) 
                      VALUES (:name, :email, :message, 'unread')";
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);
            
            if ($stmt->execute()) {
                $success = "Thank you! Your message has been sent successfully.";
                
                $name = $email = $message = "";
            } else {
                $error = "Sorry, there was an error sending your message.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
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
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Contact Us - UltraWave</title>
    <style>
    
        .contact-section {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .contact-header h1 {
            color: #EAF2EF;
            font-size: 48px;
            margin-bottom: 15px;
            background: linear-gradient(90deg, #5cb6f9, #087CA7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .contact-header p {
            color: #cae8ff;
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
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
        
        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 80px;
        }
        
        .contact-info {
            background: linear-gradient(135deg, rgba(92, 182, 249, 0.1) 0%, rgba(92, 182, 249, 0.05) 100%);
            padding: 40px;
            border-radius: 20px;
            border: 2px solid rgba(92, 182, 249, 0.2);
        }
        
        .contact-info h2 {
            color: #5cb6f9;
            font-size: 32px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(92, 182, 249, 0.3);
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 25px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(92, 182, 249, 0.3);
            transform: translateX(5px);
        }
        
        .info-icon {
            font-size: 24px;
            color: #5cb6f9;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(92, 182, 249, 0.1);
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .info-content h3 {
            color: #EAF2EF;
            font-size: 18px;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .info-content p {
            color: #cae8ff;
            font-size: 15px;
            line-height: 1.6;
            margin: 0;
            opacity: 0.9;
        }
        
        .contact-form-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(0, 173, 147, 0.05) 100%);
            padding: 40px;
            border-radius: 20px;
            border: 2px solid rgba(0, 173, 147, 0.2);
        }
        
        .contact-form-container h2 {
            color: #ffffff;
            font-size: 32px;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(0, 173, 147, 0.3);
            color: #ffff;
        }
        
        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            color: #EAF2EF;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .form-group label[for="subject"]::after {
            content: '';
        }
        
        .form-group input,
        .form-group textarea {
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 16px;
            color: #FFFF;
            font-family: 'HK Grotesk', sans-serif;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #5cb6f9;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(253, 253, 253, 0.1);
        }
        
        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);

        }

        .form-group input:not(:placeholder-shown),
        .form-group textarea:not(:placeholder-shown) {
             color: #ffffff;
             background: rgba(0, 0, 0, 0.4);
            }

        .submit-btn {
            padding: 18px 30px;
            background: linear-gradient(135deg, #00AD93 0%, #5cb6f9 100%);
            color: #050A30;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #5cb6f9 0%, #00AD93 100%);
            box-shadow: 0 10px 25px rgba(92, 182, 249, 0.3);
        }
        
        @media (max-width: 1024px) {
            .contact-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }
        
        @media (max-width: 768px) {
            .contact-header h1 {
                font-size: 36px;
            }
            
            .contact-info,
            .contact-form-container {
                padding: 30px;
            }
            
            .contact-info h2,
            .contact-form-container h2 {
                font-size: 28px;
            }
        }
        
        @media (max-width: 480px) {
            .contact-header h1 {
                font-size: 28px;
            }
            
            .contact-header p {
                font-size: 16px;
                color:#ffff;
            }
            
            .contact-info,
            .contact-form-container {
                padding: 20px;
            }
            
            .info-item {
                flex-direction: column;
                text-align: center;
                align-items: center;
            }
            
            .info-icon {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include '../partials/header.php'; ?>
    
    <main class="contact-section">
        <div class="contact-header">
            <h1>Contact UltraWave</h1>
            <p>We're here to help you with any questions or concerns</p>
        </div>
        
        <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="contact-container">
            <div class="contact-info">
                <h2>Get in Touch</h2>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Our Location</h3>
                        <p>123 Kalabri,Emshir<br>Prishtina<br>Kosovo</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <h3>Phone Number</h3>
                        <p>Customer Service: 0800 123 456<br>Technical Support: 0800 123 457<br>Business Inquiries: 0800 123 458</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email Address</h3>
                        <p>General: info@ultrawave.com<br>Support: support@ultrawave.com<br>Sales: sales@ultrawave.com</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h3>Business Hours</h3>
                        <p>Monday - Friday: 8:00 AM - 8:00 PM<br>Saturday: 9:00 AM - 5:00 PM<br>Sunday: 10:00 AM - 4:00 PM</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-container">
                <h2>Send Us a Message</h2>
                <form method="POST" action="" class="contact-form">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                               placeholder="Enter your full name"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                               placeholder="Enter your email address"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject (Optional)</label>
                        <input type="text" id="subject" name="subject" 
                               placeholder="What is this regarding?">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" 
                                  placeholder="Type your message here..."
                                  required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </main>
    
    <?php include '../partials/footer.php'; ?>
</body>
</html>