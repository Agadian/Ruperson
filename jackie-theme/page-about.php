<?php /* Template Name: About Us */ ?>
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
        <a href="<?php echo esc_url(home_url('/'));?>">Home</a> <span>/</span> <span>About Us</span>
      </div>
      <h1>About <span class="gradient-text">Goodness</span></h1>
      <p>Discover the story, passion, and people behind our creative agency.</p>
    </div>
  </section>

  <!-- Company Story -->
  <section class="section">
    <div class="container">
      <div class="about-intro">
        <div class="about-intro-img reveal-left">
          <div class="placeholder-img" style="background: var(--gradient-primary);">
            <i class="fas fa-lightbulb"></i>
          </div>
        </div>
        <div class="about-intro-content reveal-right">
          <span class="section-label">Our Story</span>
          <h2>From a <span class="gradient-text">Small Studio</span> to a Full Creative Agency</h2>
          <p>Goodness Graphics &amp; Prints was founded with a simple belief: that great design has the power to transform businesses and inspire people. What started as a small design studio has grown into a full-service creative powerhouse.</p>
          <p>Over the years, we've had the privilege of working with hundreds of brands — from ambitious startups to established enterprises — helping them craft visual identities that stand out, connect, and convert.</p>
          <p>Our team of passionate designers, strategists, and digital experts work together to deliver creative solutions that don't just look beautiful — they drive real business results.</p>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary" style="margin-top: 12px;">
            <i class="fas fa-paper-plane"></i> Work With Us
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="section section-gray">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Purpose</span>
        <h2 class="section-title">Our Mission &amp; <span class="gradient-text">Vision</span></h2>
      </div>
      <div class="mv-grid reveal">
        <div class="mv-card">
          <div class="mv-icon" style="background: var(--gradient-primary);">
            <i class="fas fa-rocket"></i>
          </div>
          <h3>Our Mission</h3>
          <p>To empower businesses with bold, innovative design and strategic marketing solutions that elevate their brand, engage their audience, and drive sustainable growth. We believe every brand deserves world-class creative excellence.</p>
        </div>
        <div class="mv-card">
          <div class="mv-icon" style="background: var(--gradient-warm);">
            <i class="fas fa-eye"></i>
          </div>
          <h3>Our Vision</h3>
          <p>To become the leading creative agency in Africa, known for transforming brands through innovative design, cutting-edge technology, and exceptional customer experiences. We aspire to set the global standard for creative excellence.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Creative Team -->
  <section class="section">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Our Team</span>
        <h2 class="section-title">Meet the <span class="gradient-text">Creative Minds</span></h2>
        <p class="section-desc">Our talented team brings diverse skills and fresh perspectives to every project.</p>
      </div>
      <div class="team-grid">
        <div class="team-card reveal">
          <div class="team-card-img" style="background: var(--gradient-primary);">
            JO
            <div class="team-overlay">
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
              <a href="#"><i class="fab fa-behance"></i></a>
            </div>
          </div>
          <div class="team-card-info">
            <h4>Goodness Okafor</h4>
            <p>Founder &amp; Creative Director</p>
          </div>
        </div>
        <div class="team-card reveal">
          <div class="team-card-img" style="background: var(--gradient-warm);">
            CN
            <div class="team-overlay">
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-behance"></i></a>
            </div>
          </div>
          <div class="team-card-info">
            <h4>Chidinma Nwosu</h4>
            <p>Lead Graphic Designer</p>
          </div>
        </div>
        <div class="team-card reveal">
          <div class="team-card-img" style="background: var(--gradient-cool);">
            EA
            <div class="team-overlay">
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
          </div>
          <div class="team-card-info">
            <h4>Emmanuel Adeyemi</h4>
            <p>Digital Marketing Head</p>
          </div>
        </div>
        <div class="team-card reveal">
          <div class="team-card-img" style="background: var(--gradient-sunset);">
            FI
            <div class="team-overlay">
              <a href="#"><i class="fab fa-linkedin-in"></i></a>
              <a href="#"><i class="fab fa-dribbble"></i></a>
            </div>
          </div>
          <div class="team-card-info">
            <h4>Fatima Ibrahim</h4>
            <p>Web Developer</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Timeline -->
  <section class="section section-dark">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label" style="-webkit-text-fill-color: var(--purple-light);">Our Journey</span>
        <h2 class="section-title" style="color: var(--white);">The Goodness <span class="gradient-text">Timeline</span></h2>
      </div>
      <div class="timeline reveal">
        <div class="timeline-item">
          <div class="timeline-content">
            <h4>2016</h4>
            <h3>The Beginning</h3>
            <p>Goodness Graphics was born as a small freelance design studio with a big dream and a passion for visual storytelling.</p>
          </div>
          <div class="timeline-dot"></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-content">
            <h4>2018</h4>
            <h3>First Office</h3>
            <p>Opened our first official office and expanded the team to 5 talented creatives. Started offering printing services.</p>
          </div>
          <div class="timeline-dot"></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-content">
            <h4>2020</h4>
            <h3>Going Digital</h3>
            <p>Expanded into digital marketing, web design, and social media management. Reached 200+ completed projects.</p>
          </div>
          <div class="timeline-dot"></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-content">
            <h4>2022</h4>
            <h3>Award Recognition</h3>
            <p>Won multiple industry awards for branding excellence. Grew team to 15+ members and served 250+ clients.</p>
          </div>
          <div class="timeline-dot"></div>
        </div>
        <div class="timeline-item">
          <div class="timeline-content">
            <h4>2024</h4>
            <h3>New Heights</h3>
            <p>500+ projects completed, expanded services to include packaging and large format printing. Serving clients across Africa.</p>
          </div>
          <div class="timeline-dot"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Core Values -->
  <section class="section">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">What Drives Us</span>
        <h2 class="section-title">Our Core <span class="gradient-text">Values</span></h2>
      </div>
      <div class="values-grid reveal">
        <div class="value-card">
          <div class="value-icon" style="background: var(--gradient-primary);">
            <i class="fas fa-lightbulb"></i>
          </div>
          <h4>Innovation</h4>
          <p>We constantly push creative boundaries and embrace new technologies to deliver fresh, forward-thinking solutions.</p>
        </div>
        <div class="value-card">
          <div class="value-icon" style="background: var(--gradient-warm);">
            <i class="fas fa-handshake"></i>
          </div>
          <h4>Integrity</h4>
          <p>We build trust through transparency, honesty, and delivering on our promises — every single time.</p>
        </div>
        <div class="value-card">
          <div class="value-icon" style="background: var(--gradient-cool);">
            <i class="fas fa-star"></i>
          </div>
          <h4>Excellence</h4>
          <p>We settle for nothing less than outstanding quality in everything we create and deliver.</p>
        </div>
        <div class="value-card">
          <div class="value-icon" style="background: var(--gradient-sunset);">
            <i class="fas fa-users"></i>
          </div>
          <h4>Collaboration</h4>
          <p>We work closely with our clients, treating every project as a true creative partnership.</p>
        </div>
        <div class="value-card">
          <div class="value-icon" style="background: var(--gradient-aurora);">
            <i class="fas fa-heart"></i>
          </div>
          <h4>Passion</h4>
          <p>We love what we do, and that passion shines through in every brand we build and every design we craft.</p>
        </div>
        <div class="value-card">
          <div class="value-icon" style="background: linear-gradient(135deg, #F97316, #7C3AED);">
            <i class="fas fa-globe-africa"></i>
          </div>
          <h4>Impact</h4>
          <p>We aim to make a meaningful difference — for our clients, their customers, and the creative industry at large.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-inner reveal-scale">
        <h2 class="cta-title">Join Our Creative Journey</h2>
        <p class="cta-desc">Let's collaborate and create something extraordinary. Your brand's transformation starts here.</p>
        <div class="cta-btns">
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-white btn-lg">
            <i class="fas fa-paper-plane"></i> Start Your Project
          </a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('portfolio'))); ?>" class="btn btn-outline-white btn-lg">
            <i class="fas fa-images"></i> View Our Work
          </a>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
