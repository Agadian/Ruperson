<?php
/**
 * Goodness Graphics & Prints Theme Functions
 */

if (!defined('ABSPATH')) exit;

define('JACKIE_VERSION', '1.0.0');
define('JACKIE_DIR', get_template_directory());
define('JACKIE_URI', get_template_directory_uri());

/* ---------- Theme Setup ---------- */
function jackie_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'jackie-theme'),
        'footer'  => __('Footer Menu', 'jackie-theme'),
    ));

    add_image_size('portfolio-thumb', 600, 450, true);
    add_image_size('team-photo', 400, 500, true);
    add_image_size('service-icon', 120, 120, true);
}
add_action('after_setup_theme', 'jackie_setup');

/* ---------- Enqueue Scripts & Styles ---------- */
function jackie_scripts() {
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap', array(), null);

    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');

    // Theme styles
    wp_enqueue_style('jackie-theme-style', JACKIE_URI . '/css/theme-style.css', array(), JACKIE_VERSION);
    wp_enqueue_style('jackie-style', get_stylesheet_uri(), array(), JACKIE_VERSION);

    // Theme JS
    wp_enqueue_script('jackie-main', JACKIE_URI . '/js/main.js', array(), JACKIE_VERSION, true);

    // Localize script
    wp_localize_script('jackie-main', 'jackieAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('jackie_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'jackie_scripts');

/* ---------- Include Custom Post Types & Admin ---------- */
require_once JACKIE_DIR . '/inc/custom-post-types.php';
require_once JACKIE_DIR . '/inc/theme-options.php';
require_once JACKIE_DIR . '/inc/woocommerce-support.php';
require_once JACKIE_DIR . '/inc/flutterwave-gateway.php';

/* ---------- Widgets ---------- */
function jackie_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'jackie-theme'),
        'id'            => 'footer-widgets',
        'description'   => __('Footer widget area', 'jackie-theme'),
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('Shop Sidebar', 'jackie-theme'),
        'id'            => 'shop-sidebar',
        'description'   => __('Sidebar for WooCommerce shop pages', 'jackie-theme'),
        'before_widget' => '<div class="shop-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'jackie_widgets_init');

/* ---------- AJAX Contact Form Handler ---------- */
function jackie_handle_contact_form() {
    check_ajax_referer('jackie_nonce', 'nonce');

    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $phone   = sanitize_text_field($_POST['phone'] ?? '');
    $service = sanitize_text_field($_POST['service'] ?? '');
    $budget  = sanitize_text_field($_POST['budget'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    $to      = get_option('admin_email');
    $subject = 'New Contact Form Submission from ' . $name;
    $body    = "Name: $name\nEmail: $email\nPhone: $phone\nService: $service\nBudget: $budget\nMessage:\n$message";
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email);

    $sent = wp_mail($to, $subject, $body, $headers);

    if ($sent) {
        wp_send_json_success('Message sent successfully!');
    } else {
        wp_send_json_error('Failed to send message. Please try again.');
    }
}
add_action('wp_ajax_jackie_contact', 'jackie_handle_contact_form');
add_action('wp_ajax_nopriv_jackie_contact', 'jackie_handle_contact_form');

/* ---------- AJAX Newsletter Handler ---------- */
function jackie_handle_newsletter() {
    check_ajax_referer('jackie_nonce', 'nonce');

    $email = sanitize_email($_POST['email'] ?? '');
    if (!is_email($email)) {
        wp_send_json_error('Please enter a valid email address.');
    }

    $subscribers = get_option('jackie_newsletter_subscribers', array());
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('jackie_newsletter_subscribers', $subscribers);
    }

    wp_send_json_success('Thanks for subscribing!');
}
add_action('wp_ajax_jackie_newsletter', 'jackie_handle_newsletter');
add_action('wp_ajax_nopriv_jackie_newsletter', 'jackie_handle_newsletter');

/* ---------- AJAX Print Quote Handler ---------- */
function jackie_handle_print_quote() {
    check_ajax_referer('jackie_nonce', 'nonce');

    $name     = sanitize_text_field($_POST['name'] ?? '');
    $email    = sanitize_email($_POST['email'] ?? '');
    $phone    = sanitize_text_field($_POST['phone'] ?? '');
    $product  = sanitize_text_field($_POST['product'] ?? '');
    $quantity = sanitize_text_field($_POST['quantity'] ?? '');
    $date     = sanitize_text_field($_POST['date'] ?? '');
    $details  = sanitize_textarea_field($_POST['details'] ?? '');

    $to      = get_option('admin_email');
    $subject = 'Print Quote Request from ' . $name;
    $body    = "Name: $name\nEmail: $email\nPhone: $phone\nProduct: $product\nQuantity: $quantity\nDelivery Date: $date\nDetails:\n$details";
    $headers = array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email);

    wp_mail($to, $subject, $body, $headers);

    wp_send_json_success('Quote request sent! We will get back to you shortly.');
}
add_action('wp_ajax_jackie_print_quote', 'jackie_handle_print_quote');
add_action('wp_ajax_nopriv_jackie_print_quote', 'jackie_handle_print_quote');

/* ---------- Custom Excerpt Length ---------- */
function jackie_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'jackie_excerpt_length');

/* ---------- Body Classes ---------- */
function jackie_body_classes($classes) {
    if (is_front_page()) $classes[] = 'home-page';
    if (class_exists('WooCommerce') && is_woocommerce()) $classes[] = 'woo-page';
    return $classes;
}
add_filter('body_class', 'jackie_body_classes');
