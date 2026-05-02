<?php /* Template Name: Printing Services */ ?>
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
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> <span>/</span> <span>Printing Services</span>
      </div>
      <h1>Printing <span class="gradient-text">Services</span></h1>
      <p>Premium quality printing solutions for all your business needs. From business cards to large format banners.</p>
    </div>
  </section>

  <!-- Print Products Grid -->
  <section class="section">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Our Products</span>
        <h2 class="section-title">What We <span class="gradient-text">Print</span></h2>
        <p class="section-desc">High-quality printing services with fast turnaround and competitive pricing.</p>
      </div>

      <div class="print-products-grid">
        <?php
        $print_products = new WP_Query(array(
            'post_type'      => 'print_pricing',
            'posts_per_page' => -1,
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_print_order',
            'order'          => 'ASC',
        ));

        if ($print_products->have_posts()) :
            while ($print_products->have_posts()) : $print_products->the_post();
                $icon  = get_post_meta(get_the_ID(), '_print_icon', true) ?: 'fas fa-file-alt';
                $desc  = get_post_meta(get_the_ID(), '_print_description', true);
                $price = get_post_meta(get_the_ID(), '_print_price', true);
        ?>
          <div class="print-product-card reveal">
            <div class="print-product-icon">
              <i class="<?php echo esc_attr($icon); ?>"></i>
            </div>
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html($desc); ?></p>
            <div class="print-product-price"><?php echo esc_html($price); ?></div>
          </div>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            // Fallback static content
        ?>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-file-alt"></i></div>
            <h3>Flyers</h3>
            <p>Eye-catching flyers for events, promotions, and marketing campaigns.</p>
            <div class="print-product-price">From $25</div>
          </div>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-book-open"></i></div>
            <h3>Brochures</h3>
            <p>Professional brochures that showcase your products and services.</p>
            <div class="print-product-price">From $45</div>
          </div>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-flag"></i></div>
            <h3>Banners</h3>
            <p>Large format vinyl banners for indoor and outdoor advertising.</p>
            <div class="print-product-price">From $35/sqm</div>
          </div>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-scroll"></i></div>
            <h3>Roll-Up Stands</h3>
            <p>Portable roll-up banner stands perfect for exhibitions and events.</p>
            <div class="print-product-price">From $65</div>
          </div>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-address-card"></i></div>
            <h3>Business Cards</h3>
            <p>Premium business cards with various finishes and paper stocks.</p>
            <div class="print-product-price">From $15</div>
          </div>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-tshirt"></i></div>
            <h3>T-Shirts</h3>
            <p>Custom printed t-shirts for teams, events, and brand merchandise.</p>
            <div class="print-product-price">From $8/piece</div>
          </div>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-sticky-note"></i></div>
            <h3>Stickers</h3>
            <p>Custom die-cut and vinyl stickers in any shape and size.</p>
            <div class="print-product-price">From $20</div>
          </div>
          <div class="print-product-card reveal">
            <div class="print-product-icon"><i class="fas fa-boxes"></i></div>
            <h3>Packaging Print</h3>
            <p>Custom packaging boxes and labels for products and retail.</p>
            <div class="print-product-price">From $50</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Pricing Tables -->
  <section class="section section-gray">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">Pricing</span>
        <h2 class="section-title">Print <span class="gradient-text">Pricing</span></h2>
        <p class="section-desc">Transparent pricing for all our printing products. Volume discounts available.</p>
      </div>

      <?php
      // Dynamic pricing table from print_pricing posts with quantity prices
      $pricing_items = new WP_Query(array(
          'post_type'      => 'print_pricing',
          'posts_per_page' => -1,
          'orderby'        => 'meta_value_num',
          'meta_key'       => '_print_order',
          'order'          => 'ASC',
          'meta_query'     => array(
              array(
                  'key'     => '_print_quantity_prices',
                  'value'   => '',
                  'compare' => '!=',
              ),
          ),
      ));

      if ($pricing_items->have_posts()) :
      ?>
      <div class="reveal" style="overflow-x: auto;">
        <table class="print-pricing-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>100</th>
              <th>250</th>
              <th>500</th>
              <th>1000</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($pricing_items->have_posts()) : $pricing_items->the_post();
                $prices_json = get_post_meta(get_the_ID(), '_print_quantity_prices', true);
                $prices = json_decode($prices_json, true);
                if (!is_array($prices)) continue;
            ?>
            <tr>
              <td><?php the_title(); ?></td>
              <td><?php echo esc_html($prices['100'] ?? '—'); ?></td>
              <td><?php echo esc_html($prices['250'] ?? '—'); ?></td>
              <td><?php echo esc_html($prices['500'] ?? '—'); ?></td>
              <td><?php echo esc_html($prices['1000'] ?? '—'); ?></td>
            </tr>
            <?php endwhile; wp_reset_postdata(); ?>
          </tbody>
        </table>
      </div>
      <?php else : ?>
      <!-- Fallback static pricing tables -->
      <div class="reveal" style="overflow-x: auto;">
        <table class="print-pricing-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>100 pcs</th>
              <th>250 pcs</th>
              <th>500 pcs</th>
              <th>1000 pcs</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Business Cards</td><td>$15</td><td>$30</td><td>$50</td><td>$85</td></tr>
            <tr><td>Flyers (A5)</td><td>$25</td><td>$45</td><td>$75</td><td>$120</td></tr>
            <tr><td>Brochures</td><td>$45</td><td>$90</td><td>$150</td><td>$250</td></tr>
            <tr><td>Stickers</td><td>$20</td><td>$40</td><td>$65</td><td>$100</td></tr>
            <tr><td>Posters (A3)</td><td>$35</td><td>$70</td><td>$120</td><td>$200</td></tr>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Quote Request Form -->
  <section class="section">
    <div class="container" style="max-width: 800px;">
      <div class="section-header reveal">
        <span class="section-label">Get a Quote</span>
        <h2 class="section-title">Request a <span class="gradient-text">Print Quote</span></h2>
        <p class="section-desc">Need a custom quote? Fill out the form below and we'll get back to you within 24 hours.</p>
      </div>
      <div class="contact-form-wrap reveal">
        <form id="printQuoteForm">
          <div class="form-row">
            <div class="form-group">
              <label>Full Name *</label>
              <input type="text" name="name" placeholder="Your full name" required />
            </div>
            <div class="form-group">
              <label>Email Address *</label>
              <input type="email" name="email" placeholder="your@email.com" required />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Phone Number</label>
              <input type="tel" name="phone" placeholder="+234 XXX XXX XXXX" />
            </div>
            <div class="form-group">
              <label>Product Type *</label>
              <select name="product" required>
                <option value="">Select a product</option>
                <option value="business-cards">Business Cards</option>
                <option value="flyers">Flyers</option>
                <option value="brochures">Brochures</option>
                <option value="banners">Banners</option>
                <option value="rollup-stands">Roll-Up Stands</option>
                <option value="tshirts">T-Shirts</option>
                <option value="stickers">Stickers</option>
                <option value="packaging">Packaging</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Quantity</label>
              <input type="number" name="quantity" placeholder="e.g. 500" />
            </div>
            <div class="form-group">
              <label>Delivery Date</label>
              <input type="date" name="date" />
            </div>
          </div>
          <div class="form-group">
            <label>Additional Details</label>
            <textarea name="details" placeholder="Tell us more about your print requirements..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
            <i class="fas fa-paper-plane"></i> Request Quote
          </button>
        </form>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-inner reveal-scale">
        <h2 class="cta-title">Need It Printed Fast?</h2>
        <p class="cta-desc">Contact us directly on WhatsApp for urgent print jobs and quick quotes.</p>
        <div class="cta-btns">
          <a href="https://wa.me/<?php echo esc_attr(jackie_get('whatsapp', '2348000000000')); ?>" target="_blank" class="btn btn-white btn-lg">
            <i class="fab fa-whatsapp"></i> Chat on WhatsApp
          </a>
          <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-outline-white btn-lg">
            <i class="fas fa-phone"></i> Contact Us
          </a>
        </div>
      </div>
    </div>
  </section>

<?php get_footer(); ?>
