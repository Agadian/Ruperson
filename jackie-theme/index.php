<?php get_header(); ?>

  <section class="page-header">
    <div class="hero-bg">
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
    </div>
    <div class="page-header-content">
      <div class="breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> <span>/</span> <span>Blog</span>
      </div>
      <h1>Our <span class="gradient-text">Blog</span></h1>
      <p>Latest news, tips, and insights from our creative team.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <?php if (have_posts()) : ?>
        <div class="services-grid">
          <?php while (have_posts()) : the_post(); ?>
            <article class="service-card reveal">
              <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large', array('style' => 'width:100%;height:200px;object-fit:cover;border-radius:var(--radius-md);margin-bottom:16px;')); ?></a>
              <?php endif; ?>
              <h3><a href="<?php the_permalink(); ?>" style="color:var(--text-primary);"><?php the_title(); ?></a></h3>
              <p style="font-size:0.8rem;color:var(--text-secondary);margin-bottom:8px;">
                <i class="fas fa-calendar"></i> <?php echo get_the_date(); ?>
              </p>
              <p><?php the_excerpt(); ?></p>
              <a href="<?php the_permalink(); ?>" class="learn-more">Read More <i class="fas fa-arrow-right"></i></a>
            </article>
          <?php endwhile; ?>
        </div>
        <div style="text-align:center;margin-top:40px;">
          <?php the_posts_pagination(array('mid_size' => 2)); ?>
        </div>
      <?php else : ?>
        <div style="text-align:center;padding:60px 0;">
          <h2>No posts found</h2>
          <p>Check back soon for updates!</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

<?php get_footer(); ?>
