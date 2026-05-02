<?php /* Template Name: Portfolio */ ?>
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
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> <span>/</span> <span>Portfolio</span>
      </div>
      <h1>Our <span class="gradient-text">Portfolio</span></h1>
      <p>Explore our creative work across branding, design, printing, web, social media, and packaging.</p>
    </div>
  </section>

  <!-- Portfolio Gallery -->
  <section class="section">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Our Work</span>
        <h2 class="section-title">Creative <span class="gradient-text">Projects</span></h2>
        <p class="section-desc">Every project we take on is an opportunity to push boundaries and deliver exceptional results.</p>
      </div>

      <?php
      // Get all portfolio categories
      $categories = get_terms(array(
          'taxonomy'   => 'portfolio_category',
          'hide_empty' => true,
      ));
      ?>

      <div class="portfolio-filters reveal">
        <button class="filter-btn active" data-filter="all">All</button>
        <?php if (!is_wp_error($categories) && !empty($categories)) : ?>
          <?php foreach ($categories as $cat) : ?>
            <button class="filter-btn" data-filter="<?php echo esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></button>
          <?php endforeach; ?>
        <?php else : ?>
          <button class="filter-btn" data-filter="branding">Branding</button>
          <button class="filter-btn" data-filter="design">Design</button>
          <button class="filter-btn" data-filter="printing">Printing</button>
          <button class="filter-btn" data-filter="web">Web</button>
          <button class="filter-btn" data-filter="social">Social</button>
          <button class="filter-btn" data-filter="packaging">Packaging</button>
        <?php endif; ?>
      </div>

      <div class="portfolio-grid">
        <?php
        $portfolio = new WP_Query(array(
            'post_type'      => 'portfolio',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ));

        if ($portfolio->have_posts()) :
            $bg_classes = array('bg-1','bg-2','bg-3','bg-4','bg-5','bg-6');
            $icons = array('fas fa-gem','fas fa-palette','fas fa-print','fas fa-laptop-code','fas fa-share-alt','fas fa-box-open');
            $i = 0;
            while ($portfolio->have_posts()) : $portfolio->the_post();
                $terms = get_the_terms(get_the_ID(), 'portfolio_category');
                $cat_slug = '';
                $cat_name = '';
                if ($terms && !is_wp_error($terms)) {
                    $cat_slug = $terms[0]->slug;
                    $cat_name = $terms[0]->name;
                }
                $bg = $bg_classes[$i % count($bg_classes)];
                $icon = $icons[$i % count($icons)];
                $link = get_post_meta(get_the_ID(), '_portfolio_link', true);
        ?>
          <div class="portfolio-item reveal-scale" data-category="<?php echo esc_attr($cat_slug); ?>">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('portfolio-thumb', array('class' => 'portfolio-img')); ?>
            <?php else : ?>
              <div class="portfolio-placeholder <?php echo esc_attr($bg); ?>">
                <i class="<?php echo esc_attr($icon); ?>"></i>
              </div>
            <?php endif; ?>
            <div class="portfolio-overlay">
              <?php if ($link) : ?>
                <a href="<?php echo esc_url($link); ?>" class="view-btn" target="_blank"><i class="fas fa-expand"></i></a>
              <?php else : ?>
                <div class="view-btn"><i class="fas fa-expand"></i></div>
              <?php endif; ?>
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html($cat_name); ?></p>
            </div>
          </div>
        <?php
                $i++;
            endwhile;
            wp_reset_postdata();
        else :
            // Fallback: show static portfolio items if no portfolio posts exist
        ?>
          <div class="portfolio-item reveal-scale" data-category="branding">
            <div class="portfolio-placeholder bg-1"><i class="fas fa-gem"></i></div>
            <div class="portfolio-overlay">
              <div class="view-btn"><i class="fas fa-expand"></i></div>
              <h3>Luxe Brand Identity</h3>
              <p>Branding</p>
            </div>
          </div>
          <div class="portfolio-item reveal-scale" data-category="design">
            <div class="portfolio-placeholder bg-2"><i class="fas fa-palette"></i></div>
            <div class="portfolio-overlay">
              <div class="view-btn"><i class="fas fa-expand"></i></div>
              <h3>Creative Campaign</h3>
              <p>Design</p>
            </div>
          </div>
          <div class="portfolio-item reveal-scale" data-category="web">
            <div class="portfolio-placeholder bg-3"><i class="fas fa-laptop-code"></i></div>
            <div class="portfolio-overlay">
              <div class="view-btn"><i class="fas fa-expand"></i></div>
              <h3>Tech Startup Website</h3>
              <p>Web Design</p>
            </div>
          </div>
          <div class="portfolio-item reveal-scale" data-category="printing">
            <div class="portfolio-placeholder bg-4"><i class="fas fa-print"></i></div>
            <div class="portfolio-overlay">
              <div class="view-btn"><i class="fas fa-expand"></i></div>
              <h3>Premium Print Set</h3>
              <p>Printing</p>
            </div>
          </div>
          <div class="portfolio-item reveal-scale" data-category="social">
            <div class="portfolio-placeholder bg-5"><i class="fas fa-share-alt"></i></div>
            <div class="portfolio-overlay">
              <div class="view-btn"><i class="fas fa-expand"></i></div>
              <h3>Social Media Campaign</h3>
              <p>Social Media</p>
            </div>
          </div>
          <div class="portfolio-item reveal-scale" data-category="packaging">
            <div class="portfolio-placeholder bg-6"><i class="fas fa-box-open"></i></div>
            <div class="portfolio-overlay">
              <div class="view-btn"><i class="fas fa-expand"></i></div>
              <h3>Product Packaging</h3>
              <p>Packaging</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-inner reveal-scale">
        <h2 class="cta-title">Have a Project in Mind?</h2>
        <p class="cta-desc">Let's collaborate and create something extraordinary together.</p>
        <div class="cta-btns">
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-white btn-lg">
            <i class="fas fa-paper-plane"></i> Start Your Project
          </a>
          <a href="https://wa.me/<?php echo esc_attr(jackie_get('whatsapp', '2348000000000')); ?>" target="_blank" class="btn btn-outline-white btn-lg">
            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
          </a>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
