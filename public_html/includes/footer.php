    </main>
    
    <!-- Donation CTA Banner (appears on every page) -->
    <section class="donation-cta-banner" style="background: linear-gradient(135deg, var(--primary-color), #c0392b); padding: 2.5rem 0; text-align: center; color: white;">
        <div class="container">
            <h2 style="color: white; margin-bottom: 0.5rem; font-size: 1.75rem;">Help Us Save More Exotic Felines</h2>
            <p style="margin-bottom: 1.5rem; font-size: 1.1rem; opacity: 0.95;">Every dollar you donate goes directly to the rescue, rehabilitation, and rehoming of small exotic cats in need.</p>
            <a href="/donate.php" class="btn" style="background: white; color: var(--primary-color); font-weight: 700; padding: 14px 40px; font-size: 1.1rem; border-radius: 30px; display: inline-block; transition: all 0.3s ease;">Donate Now &rarr;</a>
        </div>
    </section>
    
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p><?php echo e(SITE_NAME); ?> is dedicated to rescuing, rehabilitating, and rehoming small exotic felines in need. We provide a safe haven for these exotic cats while they await their forever homes.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><svg width="24" height="24" fill="currentColor"><use href="#icon-facebook"/></svg></a>
                        <a href="#" aria-label="Instagram"><svg width="24" height="24" fill="currentColor"><use href="#icon-instagram"/></svg></a>
                        <a href="#" aria-label="Twitter"><svg width="24" height="24" fill="currentColor"><use href="#icon-twitter"/></svg></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <?php if ($pageVisibility['adoptions']): ?>
                        <li><a href="/adoptions.php">Adopt an Exotic Cat</a></li>
                        <?php endif; ?>
                        <?php if ($pageVisibility['volunteer']): ?>
                        <li><a href="/volunteer.php">Volunteer</a></li>
                        <?php endif; ?>
                        <li><a href="/donate.php">Donate</a></li>
                        <?php if ($pageVisibility['residents']): ?>
                        <li><a href="/residents.php">Sanctuary Residents</a></li>
                        <?php endif; ?>
                        <?php if ($pageVisibility['blog']): ?>
                        <li><a href="/blog.php">Blog</a></li>
                        <?php endif; ?>
                        <li><a href="/contact.php">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Resources</h3>
                    <ul class="footer-links">
                        <li><a href="/about.php">About Us</a></li>
                        <li><a href="/sponsors.php">Our Sponsors</a></li>
                        <li><a href="/faq.php">FAQ</a></li>
                        <li><a href="/privacy.php">Privacy Policy</a></li>
                        <li><a href="/terms.php">Terms of Service</a></li>
                        <li><a href="/sitemap.php">Site Map</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li>
                            <svg width="20" height="20" fill="currentColor"><use href="#icon-location"/></svg>
                            <span><?php echo e(getSetting('site_address', 'Murfreesboro, TN')); ?></span>
                        </li>
                        <li>
                            <svg width="20" height="20" fill="currentColor"><use href="#icon-email"/></svg>
                            <a href="mailto:<?php echo e(SITE_EMAIL); ?>"><?php echo e(SITE_EMAIL); ?></a>
                        </li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Newsletter</h3>
                    <p>Stay updated with our latest news and adoptable small exotic cats.</p>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" name="email" placeholder="Your email" required>
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo e(SITE_NAME); ?>. All rights reserved.</p>
                    <p>Made with ❤️ for small exotic cats in need | 501(c)(3) Non-Profit Organization</p>
                </div>
                <div class="footer-bottom-right">
                    <a href="/privacy.php">Privacy Policy</a>
                    <span>|</span>
                    <a href="/terms.php">Terms</a>
                    <span>|</span>
                    <a href="/sitemap.php">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- SVG Icons Sprite -->
    <svg style="display: none;">
        <symbol id="icon-facebook" viewBox="0 0 24 24">
            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
        </symbol>
        <symbol id="icon-instagram" viewBox="0 0 24 24">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
        </symbol>
        <symbol id="icon-twitter" viewBox="0 0 24 24">
            <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
        </symbol>
        <symbol id="icon-location" viewBox="0 0 24 24">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
            <circle cx="12" cy="10" r="3"/>
        </symbol>
        <symbol id="icon-email" viewBox="0 0 24 24">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </symbol>
        <symbol id="icon-phone" viewBox="0 0 24 24">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </symbol>
    </svg>
    
    <script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>
</body>
</html>
