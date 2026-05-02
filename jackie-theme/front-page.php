<?php get_header(); ?>
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-bg">
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
      <div class="hero-shape hero-shape-4"></div>
      <div class="hero-shape hero-shape-5"></div>
      <div class="hero-grid-overlay"></div>
    </div>
    <div class="container">
      <div class="hero-content">
        <div class="hero-label">
          <i class="fas fa-rocket"></i>
          <span>Award-Winning Creative Agency</span>
        </div>
        <h1 class="hero-title">
          We Craft <span class="highlight">Bold Brands</span> &amp; Digital Experiences
        </h1>
        <p class="hero-desc">
          From stunning brand identities to powerful digital marketing campaigns, we transform your vision into visual excellence that captivates and converts.
        </p>
        <div class="hero-btns">
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary btn-lg">
            <i class="fas fa-paper-plane"></i> Start Your Project
          </a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('portfolio'))); ?>" class="btn btn-outline-white btn-lg">
            <i class="fas fa-eye"></i> View Portfolio
          </a>
        </div>
        <div class="hero-stats">
          <div class="hero-stat">
            <h3><span data-count="<?php echo esc_attr(jackie_get('stat_projects', '500')); ?>" data-suffix="+">0</span></h3>
            <p>Projects Completed</p>
          </div>
          <div class="hero-stat">
            <h3><span data-count="<?php echo esc_attr(jackie_get('stat_clients', '350')); ?>" data-suffix="+">0</span></h3>
            <p>Happy Clients</p>
          </div>
          <div class="hero-stat">
            <h3><span data-count="<?php echo esc_attr(jackie_get('stat_years', '8')); ?>" data-suffix="+">0</span></h3>
            <p>Years Experience</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Cards -->
    <div class="hero-floating">
      <div class="floating-card floating-card-1">
        <div class="card-icon" style="background: var(--gradient-primary);">
          <i class="fas fa-palette"></i>
        </div>
        <div class="card-title">Branding Project</div>
        <div class="card-value">In Progress</div>
      </div>
      <div class="floating-card floating-card-2">
        <div class="card-icon" style="background: var(--gradient-warm);">
          <i class="fas fa-chart-line"></i>
        </div>
        <div class="card-title">Client Growth</div>
        <div class="card-value">+240% Revenue</div>
      </div>
      <div class="floating-card floating-card-3">
        <div class="card-icon" style="background: var(--gradient-cool);">
          <i class="fas fa-star"></i>
        </div>
        <div class="card-title">Client Rating</div>
        <div class="card-value">4.9 / 5.0</div>
      </div>
    </div>
  </section>

  <!-- Agency Introduction / Stats -->
  <section class="section section-gray">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Who We Are</span>
        <h2 class="section-title">Creative Solutions That <span class="gradient-text">Drive Results</span></h2>
        <p class="section-desc">Goodness Graphics &amp; Prints is a full-service design and digital marketing studio. We blend creativity with strategy to deliver memorable brand experiences.</p>
      </div>
      <div class="stats-row reveal">
        <div class="stat-card">
          <div class="stat-number" data-count="<?php echo esc_attr(jackie_get('stat_projects', '500')); ?>" data-suffix="+">0</div>
          <p>Projects Completed</p>
        </div>
        <div class="stat-card">
          <div class="stat-number" data-count="<?php echo esc_attr(jackie_get('stat_clients', '350')); ?>" data-suffix="+">0</div>
          <p>Happy Clients</p>
        </div>
        <div class="stat-card">
          <div class="stat-number" data-count="<?php echo esc_attr(jackie_get('stat_years', '8')); ?>" data-suffix="+">0</div>
          <p>Years of Experience</p>
        </div>
        <div class="stat-card">
          <div class="stat-number" data-count="25" data-suffix="+">0</div>
          <p>Team Members</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="section" id="services">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Our Services</span>
        <h2 class="section-title">What We <span class="gradient-text">Do Best</span></h2>
        <p class="section-desc">We offer a comprehensive range of creative services to elevate your brand and grow your business.</p>
      </div>
      <div class="services-grid">
        <div class="service-card reveal">
          <div class="service-icon" style="background: var(--gradient-primary);">
            <i class="fas fa-fingerprint"></i>
          </div>
          <h3>Branding &amp; Identity</h3>
          <p>Craft a powerful brand identity that sets you apart and resonates with your target audience.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="service-card reveal">
          <div class="service-icon" style="background: var(--gradient-warm);">
            <i class="fas fa-pen-nib"></i>
          </div>
          <h3>Graphic Design</h3>
          <p>Stunning visuals that communicate your message effectively and leave a lasting impression.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="service-card reveal">
          <div class="service-icon" style="background: var(--gradient-cool);">
            <i class="fas fa-print"></i>
          </div>
          <h3>Large Format Printing</h3>
          <p>High-quality banners, billboards, and signage that make a big impression.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('printing'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="service-card reveal">
          <div class="service-icon" style="background: var(--gradient-sunset);">
            <i class="fas fa-id-card"></i>
          </div>
          <h3>Business Cards &amp; Flyers</h3>
          <p>Premium quality business cards and flyers that represent your brand professionally.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('printing'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="service-card reveal">
          <div class="service-icon" style="background: linear-gradient(135deg, #3B82F6, #7C3AED);">
            <i class="fas fa-laptop-code"></i>
          </div>
          <h3>Website Design</h3>
          <p>Modern, responsive websites that look stunning and drive conversions.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="service-card reveal">
          <div class="service-icon" style="background: linear-gradient(135deg, #EC4899, #7C3AED);">
            <i class="fas fa-hashtag"></i>
          </div>
          <h3>Social Media Branding</h3>
          <p>Cohesive social media presence that builds engagement and grows your following.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="service-card reveal">
          <div class="service-icon" style="background: linear-gradient(135deg, #06B6D4, #7C3AED);">
            <i class="fas fa-box-open"></i>
          </div>
          <h3>Packaging Design</h3>
          <p>Eye-catching packaging that tells your brand story and stands out on shelves.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="service-card reveal">
          <div class="service-icon" style="background: linear-gradient(135deg, #F97316, #EC4899);">
            <i class="fas fa-bullhorn"></i>
          </div>
          <h3>Digital Marketing</h3>
          <p>Data-driven campaigns that increase visibility, traffic, and ROI.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="learn-more">Learn More <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </section>

  <!-- Portfolio Section -->
  <section class="section section-dark" id="portfolio">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label" style="-webkit-text-fill-color: var(--purple-light);">Our Work</span>
        <h2 class="section-title" style="color: var(--white);">Featured <span class="gradient-text">Projects</span></h2>
        <p class="section-desc" style="color: rgba(255,255,255,0.6);">A showcase of our finest creative work across branding, design, and digital marketing.</p>
      </div>
      <div class="portfolio-filters reveal">
        <button class="filter-btn active" data-filter="all">All</button>
        <button class="filter-btn" data-filter="branding">Branding</button>
        <button class="filter-btn" data-filter="design">Design</button>
        <button class="filter-btn" data-filter="printing">Printing</button>
        <button class="filter-btn" data-filter="web">Web</button>
        <button class="filter-btn" data-filter="social">Social</button>
      </div>
      <div class="portfolio-grid">
        <div class="portfolio-item reveal-scale" data-category="branding">
          <div class="portfolio-placeholder bg-1">
            <i class="fas fa-gem"></i>
          </div>
          <div class="portfolio-overlay">
            <div class="view-btn"><i class="fas fa-expand"></i></div>
            <h3>Luxe Brand Identity</h3>
            <p>Branding</p>
          </div>
        </div>
        <div class="portfolio-item reveal-scale" data-category="design">
          <div class="portfolio-placeholder bg-2">
            <i class="fas fa-palette"></i>
          </div>
          <div class="portfolio-overlay">
            <div class="view-btn"><i class="fas fa-expand"></i></div>
            <h3>Creative Campaign</h3>
            <p>Graphic Design</p>
          </div>
        </div>
        <div class="portfolio-item reveal-scale" data-category="web">
          <div class="portfolio-placeholder bg-3">
            <i class="fas fa-globe"></i>
          </div>
          <div class="portfolio-overlay">
            <div class="view-btn"><i class="fas fa-expand"></i></div>
            <h3>Tech Startup Website</h3>
            <p>Web Design</p>
          </div>
        </div>
        <div class="portfolio-item reveal-scale" data-category="printing">
          <div class="portfolio-placeholder bg-4">
            <i class="fas fa-layer-group"></i>
          </div>
          <div class="portfolio-overlay">
            <div class="view-btn"><i class="fas fa-expand"></i></div>
            <h3>Premium Packaging</h3>
            <p>Printing &amp; Packaging</p>
          </div>
        </div>
        <div class="portfolio-item reveal-scale" data-category="social">
          <div class="portfolio-placeholder bg-5">
            <i class="fas fa-share-alt"></i>
          </div>
          <div class="portfolio-overlay">
            <div class="view-btn"><i class="fas fa-expand"></i></div>
            <h3>Social Media Kit</h3>
            <p>Social Media</p>
          </div>
        </div>
        <div class="portfolio-item reveal-scale" data-category="branding">
          <div class="portfolio-placeholder bg-6">
            <i class="fas fa-crown"></i>
          </div>
          <div class="portfolio-overlay">
            <div class="view-btn"><i class="fas fa-expand"></i></div>
            <h3>Corporate Rebrand</h3>
            <p>Branding</p>
          </div>
        </div>
      </div>
      <div class="text-center" style="margin-top: 48px;">
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('portfolio'))); ?>" class="btn btn-outline-white">View All Projects <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="section">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Why Goodness</span>
        <h2 class="section-title">Why Choose <span class="gradient-text">Us</span></h2>
        <p class="section-desc">We combine creativity, technology, and strategic thinking to deliver outstanding results for every client.</p>
      </div>
      <div class="features-grid reveal">
        <div class="feature-card">
          <div class="feature-icon" style="background: var(--gradient-primary);">
            <i class="fas fa-users"></i>
          </div>
          <h4>Creative Team</h4>
          <p>Talented designers and strategists who bring fresh perspectives.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background: var(--gradient-warm);">
            <i class="fas fa-bolt"></i>
          </div>
          <h4>Fast Delivery</h4>
          <p>Quick turnaround without compromising on quality.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background: var(--gradient-cool);">
            <i class="fas fa-award"></i>
          </div>
          <h4>Premium Quality</h4>
          <p>Award-winning designs crafted to the highest standards.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background: var(--gradient-sunset);">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <h4>Affordable Pricing</h4>
          <p>Competitive rates that deliver exceptional value.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background: var(--gradient-aurora);">
            <i class="fas fa-cogs"></i>
          </div>
          <h4>Modern Equipment</h4>
          <p>State-of-the-art tools and technology for perfect results.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="section section-gray">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Testimonials</span>
        <h2 class="section-title">What Our <span class="gradient-text">Clients Say</span></h2>
        <p class="section-desc">Don't just take our word for it. Here's what our amazing clients have to say about working with us.</p>
      </div>
      <div class="testimonials-slider reveal">
        <div class="testimonial-card">
          <div class="testimonial-quote">&ldquo;</div>
          <div class="testimonial-stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-text">Goodness Graphics transformed our brand completely. The team's creativity and attention to detail exceeded all our expectations. Highly recommended!</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar" style="background: var(--gradient-primary);">A</div>
            <div class="testimonial-info">
              <h4>Adebayo Johnson</h4>
              <p>CEO, TechVentures Ltd</p>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-quote">&ldquo;</div>
          <div class="testimonial-stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-text">The printing quality is outstanding. From business cards to large banners, everything was delivered on time and looked absolutely perfect.</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar" style="background: var(--gradient-warm);">S</div>
            <div class="testimonial-info">
              <h4>Sarah Okonkwo</h4>
              <p>Marketing Director, GreenPath</p>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-quote">&ldquo;</div>
          <div class="testimonial-stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-text">Our social media presence went from invisible to viral thanks to Goodness's creative team. They truly understand digital branding and engagement.</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar" style="background: var(--gradient-cool);">M</div>
            <div class="testimonial-info">
              <h4>Michael Eze</h4>
              <p>Founder, FoodieHub</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-inner reveal-scale">
        <h2 class="cta-title">Ready to Transform Your Brand?</h2>
        <p class="cta-desc">Let's create something extraordinary together. Start your project today and watch your vision come to life.</p>
        <div class="cta-btns">
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-white btn-lg">
            <i class="fas fa-paper-plane"></i> Start Your Project
          </a>
          <a href="tel:<?php echo esc_attr(jackie_get('phone', '+234 800 000 0000')); ?>" class="btn btn-outline-white btn-lg">
            <i class="fas fa-phone"></i> Call Us Now
          </a>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
