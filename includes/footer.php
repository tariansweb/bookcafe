    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-grid">
                <!-- Brand Section -->
                <div class="footer-brand">
                    <a href="index.php" class="footer-logo">
                        <span class="logo-icon">📚</span>
                        <span><?php echo SITE_NAME; ?></span>
                    </a>
                    <p class="footer-description">
                        A sanctuary where the aroma of freshly brewed coffee mingles with the timeless scent of well-loved books.
                    </p>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg>
                        </a>
                        <a href="#" aria-label="Twitter" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                            </svg>
                        </a>
                        <a href="#" aria-label="Facebook" class="social-link">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-links">
                    <h4>Explore</h4>
                    <ul>
                        <li><a href="menu.php">Our Menu</a></li>
                        <li><a href="books.php">Book Collection</a></li>
                        <li><a href="events.php">Upcoming Events</a></li>
                        <li><a href="contact.php">Find Us</a></li>
                    </ul>
                </div>

                <!-- Hours -->
                <div class="footer-hours">
                    <h4>Hours</h4>
                    <ul>
                        <li><span>Mon - Fri</span><span>7:00 AM - 9:00 PM</span></li>
                        <li><span>Saturday</span><span>8:00 AM - 10:00 PM</span></li>
                        <li><span>Sunday</span><span>9:00 AM - 6:00 PM</span></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="footer-newsletter">
                    <h4>Stay in the Loop</h4>
                    <p>Get updates on new arrivals, events, and special offers.</p>
                    <form class="newsletter-form" action="subscribe.php" method="POST">
                        <input type="email" name="email" placeholder="your@email.com" required>
                        <button type="submit" class="btn btn-accent">Subscribe</button>
                    </form>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                <p class="footer-quote">"A room without books is like a body without a soul." — Cicero</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
</body>
</html>

