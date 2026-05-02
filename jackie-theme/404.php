<?php get_header(); ?>

  <section class="page-header">
    <div class="hero-bg">
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
    </div>
    <div class="page-header-content">
      <h1>Page Not <span class="gradient-text">Found</span></h1>
      <p>The page you're looking for doesn't exist or has been moved.</p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="text-align:center;padding:60px 0;">
      <div style="font-size:8rem;font-weight:900;background:var(--gradient-primary);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:20px;">404</div>
      <p style="font-size:1.2rem;margin-bottom:30px;color:var(--text-secondary);">Oops! This page seems to have wandered off.</p>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-lg">
        <i class="fas fa-home"></i> Back to Home
      </a>
    </div>
  </section>

<?php get_footer(); ?>
