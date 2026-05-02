<?php
/**
 * Custom Post Types: Portfolio & Pricing
 */

if (!defined('ABSPATH')) exit;

/* ---------- Portfolio CPT ---------- */
function jackie_register_portfolio_cpt() {
    $labels = array(
        'name'               => 'Portfolio',
        'singular_name'      => 'Portfolio Item',
        'add_new'            => 'Add New Project',
        'add_new_item'       => 'Add New Portfolio Item',
        'edit_item'          => 'Edit Portfolio Item',
        'new_item'           => 'New Portfolio Item',
        'all_items'          => 'All Portfolio Items',
        'view_item'          => 'View Portfolio Item',
        'search_items'       => 'Search Portfolio',
        'not_found'          => 'No portfolio items found',
        'menu_name'          => 'Portfolio',
    );

    register_post_type('portfolio', array(
        'labels'        => $labels,
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'       => array('slug' => 'portfolio-item'),
        'show_in_rest'  => true,
    ));

    // Portfolio Categories taxonomy
    register_taxonomy('portfolio_category', 'portfolio', array(
        'labels' => array(
            'name'          => 'Portfolio Categories',
            'singular_name' => 'Portfolio Category',
            'add_new_item'  => 'Add New Category',
            'menu_name'     => 'Categories',
        ),
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => array('slug' => 'portfolio-category'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'jackie_register_portfolio_cpt');

/* ---------- Portfolio Meta Boxes ---------- */
function jackie_portfolio_meta_boxes() {
    add_meta_box('portfolio_details', 'Project Details', 'jackie_portfolio_details_callback', 'portfolio', 'normal', 'high');
}
add_action('add_meta_boxes', 'jackie_portfolio_meta_boxes');

function jackie_portfolio_details_callback($post) {
    wp_nonce_field('jackie_portfolio_nonce', 'jackie_portfolio_nonce_field');
    $client = get_post_meta($post->ID, '_portfolio_client', true);
    $link   = get_post_meta($post->ID, '_portfolio_link', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="portfolio_client">Client Name</label></th>
            <td><input type="text" id="portfolio_client" name="portfolio_client" value="<?php echo esc_attr($client); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="portfolio_link">Project URL</label></th>
            <td><input type="url" id="portfolio_link" name="portfolio_link" value="<?php echo esc_url($link); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

function jackie_save_portfolio_meta($post_id) {
    if (!isset($_POST['jackie_portfolio_nonce_field']) || !wp_verify_nonce($_POST['jackie_portfolio_nonce_field'], 'jackie_portfolio_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['portfolio_client'])) {
        update_post_meta($post_id, '_portfolio_client', sanitize_text_field($_POST['portfolio_client']));
    }
    if (isset($_POST['portfolio_link'])) {
        update_post_meta($post_id, '_portfolio_link', esc_url_raw($_POST['portfolio_link']));
    }
}
add_action('save_post_portfolio', 'jackie_save_portfolio_meta');

/* ---------- Service Pricing CPT ---------- */
function jackie_register_pricing_cpt() {
    register_post_type('service_pricing', array(
        'labels' => array(
            'name'               => 'Service Pricing',
            'singular_name'      => 'Pricing Package',
            'add_new'            => 'Add Pricing Package',
            'add_new_item'       => 'Add New Pricing Package',
            'edit_item'          => 'Edit Pricing Package',
            'all_items'          => 'All Pricing Packages',
            'menu_name'          => 'Service Pricing',
        ),
        'public'       => false,
        'show_ui'      => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-money-alt',
        'supports'     => array('title'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'jackie_register_pricing_cpt');

/* ---------- Service Pricing Meta Boxes ---------- */
function jackie_pricing_meta_boxes() {
    add_meta_box('pricing_details', 'Package Details', 'jackie_pricing_details_callback', 'service_pricing', 'normal', 'high');
}
add_action('add_meta_boxes', 'jackie_pricing_meta_boxes');

function jackie_pricing_details_callback($post) {
    wp_nonce_field('jackie_pricing_nonce', 'jackie_pricing_nonce_field');
    $price       = get_post_meta($post->ID, '_pricing_price', true);
    $period      = get_post_meta($post->ID, '_pricing_period', true);
    $popular     = get_post_meta($post->ID, '_pricing_popular', true);
    $type        = get_post_meta($post->ID, '_pricing_type', true);
    $features    = get_post_meta($post->ID, '_pricing_features', true);
    $order       = get_post_meta($post->ID, '_pricing_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="pricing_type">Pricing Type</label></th>
            <td>
                <select id="pricing_type" name="pricing_type">
                    <option value="branding" <?php selected($type, 'branding'); ?>>Branding / Services</option>
                    <option value="printing" <?php selected($type, 'printing'); ?>>Printing</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="pricing_price">Price ($)</label></th>
            <td><input type="text" id="pricing_price" name="pricing_price" value="<?php echo esc_attr($price); ?>" class="regular-text" placeholder="e.g. 299" /></td>
        </tr>
        <tr>
            <th><label for="pricing_period">Period/Label</label></th>
            <td><input type="text" id="pricing_period" name="pricing_period" value="<?php echo esc_attr($period); ?>" class="regular-text" placeholder="e.g. per project, per month" /></td>
        </tr>
        <tr>
            <th><label for="pricing_popular">Popular/Featured?</label></th>
            <td><input type="checkbox" id="pricing_popular" name="pricing_popular" value="1" <?php checked($popular, '1'); ?> /> Mark as popular/recommended</td>
        </tr>
        <tr>
            <th><label for="pricing_order">Display Order</label></th>
            <td><input type="number" id="pricing_order" name="pricing_order" value="<?php echo esc_attr($order ?: 0); ?>" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="pricing_features">Features (one per line)</label></th>
            <td><textarea id="pricing_features" name="pricing_features" rows="10" class="large-text"><?php echo esc_textarea($features); ?></textarea>
            <p class="description">Enter one feature per line. Prefix with <code>-</code> to mark as not included.</p></td>
        </tr>
    </table>
    <?php
}

function jackie_save_pricing_meta($post_id) {
    if (!isset($_POST['jackie_pricing_nonce_field']) || !wp_verify_nonce($_POST['jackie_pricing_nonce_field'], 'jackie_pricing_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('pricing_price', 'pricing_period', 'pricing_type', 'pricing_features', 'pricing_order');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
    update_post_meta($post_id, '_pricing_popular', isset($_POST['pricing_popular']) ? '1' : '0');
}
add_action('save_post_service_pricing', 'jackie_save_pricing_meta');

/* ---------- Print Pricing CPT ---------- */
function jackie_register_print_pricing_cpt() {
    register_post_type('print_pricing', array(
        'labels' => array(
            'name'               => 'Print Pricing',
            'singular_name'      => 'Print Price',
            'add_new'            => 'Add Print Price',
            'add_new_item'       => 'Add New Print Price',
            'edit_item'          => 'Edit Print Price',
            'all_items'          => 'All Print Prices',
            'menu_name'          => 'Print Pricing',
        ),
        'public'       => false,
        'show_ui'      => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-printer',
        'supports'     => array('title'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'jackie_register_print_pricing_cpt');

/* ---------- Print Pricing Meta Boxes ---------- */
function jackie_print_pricing_meta_boxes() {
    add_meta_box('print_pricing_details', 'Print Pricing Details', 'jackie_print_pricing_callback', 'print_pricing', 'normal', 'high');
}
add_action('add_meta_boxes', 'jackie_print_pricing_meta_boxes');

function jackie_print_pricing_callback($post) {
    wp_nonce_field('jackie_print_pricing_nonce', 'jackie_print_pricing_nonce_field');
    $icon        = get_post_meta($post->ID, '_print_icon', true);
    $description = get_post_meta($post->ID, '_print_description', true);
    $price       = get_post_meta($post->ID, '_print_price', true);
    $prices_json = get_post_meta($post->ID, '_print_quantity_prices', true);
    $order       = get_post_meta($post->ID, '_print_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="print_icon">Icon Class (Font Awesome)</label></th>
            <td><input type="text" id="print_icon" name="print_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text" placeholder="e.g. fas fa-file-alt" /></td>
        </tr>
        <tr>
            <th><label for="print_description">Description</label></th>
            <td><textarea id="print_description" name="print_description" rows="3" class="large-text"><?php echo esc_textarea($description); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="print_price">Starting Price</label></th>
            <td><input type="text" id="print_price" name="print_price" value="<?php echo esc_attr($price); ?>" class="regular-text" placeholder="e.g. From $25" /></td>
        </tr>
        <tr>
            <th><label for="print_quantity_prices">Quantity Pricing (JSON)</label></th>
            <td><textarea id="print_quantity_prices" name="print_quantity_prices" rows="5" class="large-text"><?php echo esc_textarea($prices_json); ?></textarea>
            <p class="description">JSON format: <code>{"100":"$25","250":"$45","500":"$75","1000":"$120"}</code></p></td>
        </tr>
        <tr>
            <th><label for="print_order">Display Order</label></th>
            <td><input type="number" id="print_order" name="print_order" value="<?php echo esc_attr($order ?: 0); ?>" class="small-text" /></td>
        </tr>
    </table>
    <?php
}

function jackie_save_print_pricing_meta($post_id) {
    if (!isset($_POST['jackie_print_pricing_nonce_field']) || !wp_verify_nonce($_POST['jackie_print_pricing_nonce_field'], 'jackie_print_pricing_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array('print_icon', 'print_description', 'print_price', 'print_quantity_prices', 'print_order');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_print_pricing', 'jackie_save_print_pricing_meta');

/* ---------- Flush Rewrite on Activation ---------- */
function jackie_rewrite_flush() {
    jackie_register_portfolio_cpt();
    jackie_register_pricing_cpt();
    jackie_register_print_pricing_cpt();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'jackie_rewrite_flush');
