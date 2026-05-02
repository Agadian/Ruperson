<?php /* Template Name: Contact */ ?>
<?php get_header(); ?>
  <!-- Page Header -->
  <section class="page-header">
    <div class="hero-bg">
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
    </div>
    <div class="page-header-content">
      <div class="breadcrumb">
        <a href="<?php echo esc_url(home_url('/'));?>">Home</a> <span>/</span> <span>Contact</span>
      </div>
      <h1>Get In <span class="gradient-text">Touch</span></h1>
      <p>We'd love to hear from you. Let's start a conversation about your next project.</p>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="section">
    <div class="container">
      <div class="contact-grid">

        <!-- Contact Info -->
        <div class="reveal-left">
          <div class="contact-info-cards">
            <div class="contact-info-card">
              <div class="info-icon" style="background: var(--gradient-primary);">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div>
                <h4>Visit Our Office</h4>
                <p>123 Creative Avenue, Victoria Island<br>Lagos, Nigeria</p>
              </div>
            </div>
            <div class="contact-info-card">
              <div class="info-icon" style="background: var(--gradient-warm);">
                <i class="fas fa-phone-alt"></i>
              </div>
              <div>
                <h4>Call Us</h4>
                <p>+234 800 000 0000<br>+234 901 234 5678</p>
              </div>
            </div>
            <div class="contact-info-card">
              <div class="info-icon" style="background: var(--gradient-cool);">
                <i class="fas fa-envelope"></i>
              </div>
              <div>
                <h4>Email Us</h4>
                <p>hello@jackiecreative.com<br>info@jackiecreative.com</p>
              </div>
            </div>
            <div class="contact-info-card">
              <div class="info-icon" style="background: #25D366;">
                <i class="fab fa-whatsapp"></i>
              </div>
              <div>
                <h4>WhatsApp</h4>
                <p>Chat with us instantly<br><a href="https://wa.me/2348000000000" target="_blank" style="color: var(--purple); font-weight: 600;">Open WhatsApp Chat</a></p>
              </div>
            </div>
          </div>

          <!-- Business Hours -->
          <div class="business-hours">
            <h4><i class="fas fa-clock"></i> &nbsp;Business Hours</h4>
            <div class="hours-row">
              <span>Monday - Friday</span>
              <span>9:00 AM - 6:00 PM</span>
            </div>
            <div class="hours-row">
              <span>Saturday</span>
              <span>10:00 AM - 4:00 PM</span>
            </div>
            <div class="hours-row">
              <span>Sunday</span>
              <span>Closed</span>
            </div>
          </div>

          <!-- Social Media -->
          <div style="margin-top: 24px;">
            <h4 style="font-family: var(--font-heading); font-weight: 700; margin-bottom: 16px;">Follow Us</h4>
            <div class="footer-social">
              <a href="#" aria-label="Facebook" style="border-color: var(--border-color); color: var(--text-secondary);"><i class="fab fa-facebook-f"></i></a>
              <a href="#" aria-label="Instagram" style="border-color: var(--border-color); color: var(--text-secondary);"><i class="fab fa-instagram"></i></a>
              <a href="#" aria-label="Twitter" style="border-color: var(--border-color); color: var(--text-secondary);"><i class="fab fa-twitter"></i></a>
              <a href="#" aria-label="LinkedIn" style="border-color: var(--border-color); color: var(--text-secondary);"><i class="fab fa-linkedin-in"></i></a>
              <a href="#" aria-label="Behance" style="border-color: var(--border-color); color: var(--text-secondary);"><i class="fab fa-behance"></i></a>
              <a href="#" aria-label="TikTok" style="border-color: var(--border-color); color: var(--text-secondary);"><i class="fab fa-tiktok"></i></a>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-wrap reveal-right">
          <h3>Send Us a Message</h3>
          <p>Fill out the form below and we'll get back to you within 24 hours.</p>
          <form id="contactForm">
            <div class="form-row">
              <div class="form-group">
                <label>Full Name *</label>
                <input type="text" placeholder="Your full name" required />
              </div>
              <div class="form-group">
                <label>Email Address *</label>
                <input type="email" placeholder="your@email.com" required />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" placeholder="+234 XXX XXX XXXX" />
              </div>
              <div class="form-group">
                <label>Service Interested In</label>
                <select>
                  <option value="">Select a service</option>
                  <option value="branding">Branding &amp; Identity</option>
                  <option value="graphic-design">Graphic Design</option>
                  <option value="printing">Printing Services</option>
                  <option value="web-design">Website Design</option>
                  <option value="digital-marketing">Digital Marketing</option>
                  <option value="social-media">Social Media Management</option>
                  <option value="packaging">Packaging Design</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label>Budget Range</label>
              <select>
                <option value="">Select your budget</option>
                <option value="100-300">$100 - $300</option>
                <option value="300-700">$300 - $700</option>
                <option value="700-1500">$700 - $1,500</option>
                <option value="1500+">$1,500+</option>
              </select>
            </div>
            <div class="form-group">
              <label>Project Details *</label>
              <textarea placeholder="Tell us about your project — goals, timeline, any specific requirements..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
              <i class="fas fa-paper-plane"></i> Send Message
            </button>
          </form>
        </div>
      </div>

      <!-- Map -->
      <div class="contact-map reveal">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.7293958152024!2d3.4226085!3d6.4280556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103bf53aec4dd92d%3A0x5e34fe6b8d86e3e5!2sVictoria%20Island%2C%20Lagos!5e0!3m2!1sen!2sng!4v1700000000000!5m2!1sen!2sng"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Jackie Creative Agency Location">
        </iframe>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-inner reveal-scale">
        <h2 class="cta-title">Prefer a Quick Chat?</h2>
        <p class="cta-desc">Reach us instantly on WhatsApp or give us a call. We're always happy to help!</p>
        <div class="cta-btns">
          <a href="https://wa.me/2348000000000" target="_blank" class="btn btn-white btn-lg">
            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
          </a>
          <a href="tel:+2348000000000" class="btn btn-outline-white btn-lg">
            <i class="fas fa-phone"></i> Call Now
          </a>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
