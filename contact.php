<?php
$pageTitle = 'Contact';
require_once 'includes/functions.php';

$success = false;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        if (submitContactMessage($name, $email, $subject, $message)) {
            $success = true;
        } else {
            $error = 'Something went wrong. Please try again later.';
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <span class="page-label">Get in Touch</span>
        <h1 class="page-title">Visit Us</h1>
        <p class="page-subtitle">We'd love to hear from you</p>
    </div>
</section>

<!-- Contact Content -->
<section class="contact-content">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2 class="form-title">Send a Message</h2>
                
                <?php if ($success): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✓</span>
                    <div>
                        <strong>Message Sent!</strong>
                        <p>Thank you for reaching out. We'll get back to you soon.</p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">!</span>
                    <div>
                        <strong>Oops!</strong>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <form class="contact-form" method="POST" action="contact.php">
                    <div class="form-group">
                        <label for="name">Your Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                               placeholder="Jane Doe">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                               placeholder="jane@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" 
                               value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                               placeholder="What's this about?">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message <span class="required">*</span></label>
                        <textarea id="message" name="message" rows="5" required 
                                  placeholder="Tell us what's on your mind..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">Send Message</button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info-section">
                <div class="info-card">
                    <h3>📍 Find Us</h3>
                    <p>123 Literary Lane<br>Booktown, BK 12345</p>
                    <a href="#" class="link-arrow">Get Directions →</a>
                </div>
                
                <div class="info-card">
                    <h3>🕐 Hours</h3>
                    <ul class="hours-list">
                        <li><span>Monday - Friday</span><span>7:00 AM - 9:00 PM</span></li>
                        <li><span>Saturday</span><span>8:00 AM - 10:00 PM</span></li>
                        <li><span>Sunday</span><span>9:00 AM - 6:00 PM</span></li>
                    </ul>
                </div>
                
                <div class="info-card">
                    <h3>📞 Contact</h3>
                    <p><strong>Phone:</strong> (555) 123-4567</p>
                    <p><strong>Email:</strong> hello@bookcafe.com</p>
                </div>
                
                <div class="info-card social-card">
                    <h3>Follow Us</h3>
                    <div class="social-buttons">
                        <a href="#" class="social-btn instagram">Instagram</a>
                        <a href="#" class="social-btn twitter">Twitter</a>
                        <a href="#" class="social-btn facebook">Facebook</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="map-placeholder">
        <div class="map-overlay">
            <span class="map-icon">🗺️</span>
            <p>Interactive Map</p>
            <small>Add your Google Maps embed here</small>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

