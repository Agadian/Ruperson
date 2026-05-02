<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>&#x1F3A8;</text></svg>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <div class="loading-screen">
    <div class="loader">
      <div class="loader-logo">Goodness.</div>
      <div class="loader-bar"><div class="loader-bar-fill"></div></div>
    </div>
  </div>

  <div class="scroll-progress"></div>
  <div class="mobile-overlay"></div>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="container">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-logo">Goodness<span>.</span> <span class="brand-tag">Graphics & Prints</span></a>
      <div class="nav-links">
        <a href="<?php echo esc_url(home_url('/')); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>>Home</a>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" <?php echo is_page('about') ? 'class="active"' : ''; ?>>About</a>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" <?php echo is_page('services') ? 'class="active"' : ''; ?>>Services</a>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('portfolio'))); ?>" <?php echo is_page('portfolio') ? 'class="active"' : ''; ?>>Portfolio</a>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" <?php echo is_page('contact') ? 'class="active"' : ''; ?>>Contact</a>
        <?php if (class_exists('WooCommerce')) : ?>
          <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" <?php echo is_shop() || is_product() ? 'class="active"' : ''; ?>>Shop</a>
          <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="nav-cart">
            <i class="fas fa-shopping-cart"></i>
            <?php $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
            <?php if ($count > 0) : ?>
              <span class="cart-count"><?php echo $count; ?></span>
            <?php endif; ?>
          </a>
        <?php endif; ?>
      </div>
      <div class="nav-right">
        <button class="theme-toggle" aria-label="Toggle theme">
          <i class="fas fa-moon icon-moon"></i>
          <i class="fas fa-sun icon-sun"></i>
        </button>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary btn-sm nav-cta">Get a Quote</a>
        <div class="menu-toggle" aria-label="Menu">
          <span></span><span></span><span></span>
        </div>
      </div>
    </div>
  </nav>
