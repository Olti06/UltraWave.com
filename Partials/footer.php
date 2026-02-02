
<footer style="background: #0a1142; color: white; padding: 60px 20px; margin-top: 80px;">
    <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
        <div>
            <h3 style="color: #00EEFF; margin-bottom: 20px;">UltraWave</h3>
            <p style="opacity: 0.8;">Connecting you across the universe with lightning-fast internet services.</p>
        </div>
        
        <div>
            <h4 style="color: #EAF2EF; margin-bottom: 15px;">Quick Links</h4>
            <ul style="list-style: none; padding: 0;">
                <li><a href="../index.php" style="color: rgba(234, 242, 239, 0.8); text-decoration: none; display: block; margin-bottom: 8px;">Home</a></li>
                <li><a href="../Services/internet-services.php" style="color: rgba(234, 242, 239, 0.8); text-decoration: none; display: block; margin-bottom: 8px;">Services</a></li>
                <li><a href="../About/about.php" style="color: rgba(234, 242, 239, 0.8); text-decoration: none; display: block; margin-bottom: 8px;">About Us</a></li>
                <li><a href="../Contact/contact.php" style="color: rgba(234, 242, 239, 0.8); text-decoration: none; display: block; margin-bottom: 8px;">Contact</a></li>
            </ul>
        </div>
        
        <div>
            <h4 style="color: #EAF2EF; margin-bottom: 15px;">Contact Info</h4>
            <p style="opacity: 0.8; margin-bottom: 10px;">
                <i class="fas fa-map-marker-alt" style="color: #00EEFF; margin-right: 10px;"></i>
                123 Internet Avenue, Tech City
            </p>
            <p style="opacity: 0.8; margin-bottom: 10px;">
                <i class="fas fa-phone" style="color: #00EEFF; margin-right: 10px;"></i>
                0800 123 456
            </p>
            <p style="opacity: 0.8;">
                <i class="fas fa-envelope" style="color: #00EEFF; margin-right: 10px;"></i>
                info@ultrawave.com
            </p>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
        <p style="opacity: 0.7;">&copy; <?php echo date('Y'); ?> UltraWave. All rights reserved.</p>
    </div>
</footer>

<script>

document.addEventListener('DOMContentLoaded', function() {
    const searchTrigger = document.querySelector('.search-trigger');
    const searchContainer = document.querySelector('.search-container');
    
    if (searchTrigger) {
        searchTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            searchContainer.classList.toggle('active');
        });
    }
    

    const backToTop = document.createElement('a');
    backToTop.href = '#';
    backToTop.className = 'back-to-top';
    backToTop.innerHTML = '<i class="fas fa-chevron-up"></i>';
    document.body.appendChild(backToTop);
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTop.classList.add('visible');
        } else {
            backToTop.classList.remove('visible');
        }
    });
    
    backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({top: 0, behavior: 'smooth'});
    });
});
</script>