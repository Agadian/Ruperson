  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-about">
          <div class="footer-logo">Goodness.</div>
          <p>We are a creative digital agency specializing in branding, design, printing, and digital marketing. Transforming brands with innovative solutions.</p>
          <div class="footer-social">
            <a href="<?php echo esc_url(jackie_get('facebook', '#')); ?>" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="<?php echo esc_url(jackie_get('instagram', '#')); ?>" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="<?php echo esc_url(jackie_get('twitter', '#')); ?>" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="<?php echo esc_url(jackie_get('linkedin', '#')); ?>" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="<?php echo esc_url(jackie_get('behance', '#')); ?>" aria-label="Behance"><i class="fab fa-behance"></i></a>
          </div>
        </div>
        <div class="footer-col">
          <h4>Quick Links</h4>
          <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>">About Us</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">Services</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('portfolio'))); ?>">Portfolio</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>">Contact</a>
          <?php if (class_exists('WooCommerce')) : ?>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Shop</a>
          <?php endif; ?>
        </div>
        <div class="footer-col">
          <h4>Services</h4>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">Branding</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">Graphic Design</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('printing'))); ?>">Printing</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">Web Design</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">Digital Marketing</a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">Social Media</a>
        </div>
        <div class="footer-col footer-newsletter">
          <h4>Newsletter</h4>
          <p>Subscribe for creative tips, updates, and exclusive offers.</p>
          <form class="newsletter-form" id="newsletterForm">
            <input type="email" placeholder="Enter your email" required />
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Goodness Graphics & Prints. All rights reserved.</p>
        <div>
          <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">Privacy Policy</a> &nbsp;|&nbsp; <a href="#">Terms of Service</a>
        </div>
      </div>
    </div>
  </footer>

  <?php $whatsapp = jackie_get('whatsapp', '2348000000000'); ?>
  <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" class="whatsapp-float" aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
  </a>
  <button class="back-to-top" aria-label="Back to top"><i class="fas fa-chevron-up"></i></button>

  <?php wp_footer(); ?>
</body>
</html>
