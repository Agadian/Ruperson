<?php
/**
 * WooCommerce Shop Template
 */
get_header(); ?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="hero-bg">
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
    </div>
    <div class="page-header-content">
      <div class="breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> <span>/</span>
        <?php if (is_product()) : ?>
          <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Shop</a> <span>/</span>
          <span><?php the_title(); ?></span>
        <?php else : ?>
          <span>Shop</span>
        <?php endif; ?>
      </div>
      <?php if (is_product()) : ?>
        <h1><?php the_title(); ?></h1>
      <?php else : ?>
        <h1>Our <span class="gradient-text">Shop</span></h1>
        <p>Order professional design and printing products delivered straight to your doorstep.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Shop Content -->
  <section class="section">
    <div class="container">
      <?php woocommerce_content(); ?>
    </div>
  </section>

<?php get_footer(); ?>
