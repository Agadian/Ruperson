<?php get_header(); ?>

  <section class="page-header">
    <div class="hero-bg">
      <div class="hero-shape hero-shape-1"></div>
      <div class="hero-shape hero-shape-2"></div>
      <div class="hero-shape hero-shape-3"></div>
    </div>
    <div class="page-header-content">
      <div class="breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> <span>/</span> <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>">Blog</a> <span>/</span> <span><?php the_title(); ?></span>
      </div>
      <h1><?php the_title(); ?></h1>
      <p style="margin-top:12px;opacity:0.7;">
        <i class="fas fa-calendar"></i> <?php echo get_the_date(); ?> &nbsp;&bull;&nbsp;
        <i class="fas fa-user"></i> <?php the_author(); ?> &nbsp;&bull;&nbsp;
        <i class="fas fa-folder"></i> <?php the_category(', '); ?>
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width:800px;">
      <?php while (have_posts()) : the_post(); ?>
        <?php if (has_post_thumbnail()) : ?>
          <div style="margin-bottom:30px;border-radius:var(--radius-lg);overflow:hidden;">
            <?php the_post_thumbnail('full', array('style' => 'width:100%;height:auto;')); ?>
          </div>
        <?php endif; ?>
        <div class="page-content reveal" style="font-size:1.05rem;line-height:1.8;">
          <?php the_content(); ?>
        </div>
        <div style="margin-top:40px;padding-top:20px;border-top:1px solid var(--border-color);">
          <?php the_tags('<div style="margin-bottom:16px;"><i class="fas fa-tags"></i> ', ', ', '</div>'); ?>
          <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div><?php previous_post_link('%link', '<i class="fas fa-arrow-left"></i> %title'); ?></div>
            <div><?php next_post_link('%link', '%title <i class="fas fa-arrow-right"></i>'); ?></div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

<?php get_footer(); ?>
