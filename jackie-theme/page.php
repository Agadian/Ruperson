<?php get_header(); ?>

  <section class="page-header">
    <div class="hero-bg">
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
    </div>
    <div class="page-header-content">
      <div class="breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> <span>/</span> <span><?php the_title(); ?></span>
      </div>
      <h1><?php the_title(); ?></h1>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width:800px;">
      <?php while (have_posts()) : the_post(); ?>
        <div class="page-content reveal">
          <?php the_content(); ?>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

<?php get_footer(); ?>
