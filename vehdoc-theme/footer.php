</main>

<!-- Footer -->
<footer class="vehdoc-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col footer-about">
                <a href="<?php echo home_url('/'); ?>" class="footer-logo">
                    <span class="logo-text">Veh<span class="logo-accent">doc</span></span>
                </a>
                <p class="footer-desc">Fast, secure and reliable vehicle documentation services with doorstep delivery across Nigeria.</p>
                <div class="footer-socials">
                    <?php
                    $socials = array(
                        'vehdoc_facebook'  => 'fa-facebook-f',
                        'vehdoc_twitter'   => 'fa-x-twitter',
                        'vehdoc_instagram' => 'fa-instagram',
                        'vehdoc_linkedin'  => 'fa-linkedin-in',
                    );
                    foreach ($socials as $key => $icon) :
                        $url = get_option($key);
                        if ($url) :
                    ?>
                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener" class="social-link"><i class="fa-brands <?php echo $icon; ?>"></i></a>
                    <?php endif; endforeach; ?>
                </div>
            </div>

            <div class="footer-col">
                <h4>Services</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo home_url('/services/'); ?>">Vehicle Licence Renewal</a></li>
                    <li><a href="<?php echo home_url('/services/'); ?>">Proof of Ownership</a></li>
                    <li><a href="<?php echo home_url('/services/'); ?>">Change of Ownership</a></li>
                    <li><a href="<?php echo home_url('/services/'); ?>">Plate Number Processing</a></li>
                    <li><a href="<?php echo home_url('/services/'); ?>">Driver's Licence Renewal</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Company</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo home_url('/about-us/'); ?>">About Us</a></li>
                    <li><a href="<?php echo home_url('/#how-it-works'); ?>">How It Works</a></li>
                    <li><a href="<?php echo home_url('/#faq'); ?>">FAQ</a></li>
                    <li><a href="<?php echo home_url('/contact-us/'); ?>">Contact Us</a></li>
                    <li><a href="<?php echo home_url('/track-order/'); ?>">Track Order</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Legal</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo home_url('/privacy-policy/'); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo home_url('/terms-of-service/'); ?>">Terms of Service</a></li>
                    <li><a href="<?php echo home_url('/login/'); ?>">Login</a></li>
                    <li><a href="<?php echo home_url('/register/'); ?>">Register</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact</h4>
                <ul class="footer-contact">
                    <?php $phone = get_option('vehdoc_company_phone'); if ($phone) : ?>
                    <li><i class="fa-solid fa-phone"></i> <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></li>
                    <?php endif; ?>
                    <?php $email = get_option('vehdoc_company_email'); if ($email) : ?>
                    <li><i class="fa-solid fa-envelope"></i> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></li>
                    <?php endif; ?>
                    <?php $address = get_option('vehdoc_company_address'); if ($address) : ?>
                    <li><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($address); ?></li>
                    <?php endif; ?>
                </ul>

                <div class="footer-newsletter">
                    <h5>Stay Updated</h5>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo esc_html(get_option('vehdoc_company_name', 'Vehdoc')); ?>. All rights reserved.</p>
            <p>Vehicle Documentation Services — Nigeria</p>
        </div>
    </div>
</footer>

<!-- WhatsApp Float Button -->
<?php $whatsapp = get_option('vehdoc_whatsapp_number'); if ($whatsapp) : ?>
<a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $whatsapp); ?>?text=Hello%20Vehdoc%2C%20I%20need%20help%20with%20my%20vehicle%20documents" target="_blank" class="whatsapp-float" aria-label="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>
<?php endif; ?>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fa-solid fa-chevron-up"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
