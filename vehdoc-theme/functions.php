<?php
/**
 * Vehdoc Theme Functions
 *
 * @package Vehdoc
 * @version 1.0.0
 */

if (!defined('ABSPATH')) exit;

define('VEHDOC_VERSION', '1.0.0');
define('VEHDOC_DIR', get_template_directory());
define('VEHDOC_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function vehdoc_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');

    register_nav_menus(array(
        'primary'   => __('Primary Menu', 'vehdoc'),
        'footer'    => __('Footer Menu', 'vehdoc'),
        'dashboard' => __('Dashboard Menu', 'vehdoc'),
    ));

    add_image_size('vehdoc-hero', 1920, 800, true);
    add_image_size('vehdoc-service', 600, 400, true);
    add_image_size('vehdoc-thumbnail', 300, 200, true);
}
add_action('after_setup_theme', 'vehdoc_setup');

/**
 * Enqueue Scripts and Styles
 */
function vehdoc_scripts() {
    // Google Fonts
    wp_enqueue_style('vehdoc-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', array(), null);

    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1');

    // Theme Styles
    wp_enqueue_style('vehdoc-style', VEHDOC_URI . '/css/theme-style.css', array(), VEHDOC_VERSION);

    // Dashboard Styles (only for logged-in users on dashboard pages)
    if (is_user_logged_in() && is_page_template(array(
        'templates/template-dashboard.php',
        'templates/template-vehicles.php',
        'templates/template-orders.php',
        'templates/template-tracking.php',
        'templates/template-delivery.php',
        'templates/template-profile.php',
    ))) {
        wp_enqueue_style('vehdoc-dashboard', VEHDOC_URI . '/css/dashboard.css', array('vehdoc-style'), VEHDOC_VERSION);
        wp_enqueue_script('vehdoc-dashboard-js', VEHDOC_URI . '/js/dashboard.js', array('jquery'), VEHDOC_VERSION, true);
        wp_localize_script('vehdoc-dashboard-js', 'vehdocDashboard', array(
            'ajaxUrl'  => admin_url('admin-ajax.php'),
            'restUrl'  => rest_url('vehdoc/v1/'),
            'nonce'    => wp_create_nonce('wp_rest'),
            'userId'   => get_current_user_id(),
        ));
    }

    // Main JS
    wp_enqueue_script('vehdoc-main', VEHDOC_URI . '/js/main.js', array('jquery'), VEHDOC_VERSION, true);
    wp_localize_script('vehdoc-main', 'vehdocMain', array(
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'restUrl'  => rest_url('vehdoc/v1/'),
        'nonce'    => wp_create_nonce('wp_rest'),
        'siteUrl'  => home_url(),
        'themeUrl' => VEHDOC_URI,
    ));

    // Payment JS (only on service/checkout pages)
    if (is_page_template('templates/template-checkout.php') || is_page_template('templates/template-services.php')) {
        wp_enqueue_script('paystack', 'https://js.paystack.co/v2/inline.js', array(), null, true);
        wp_enqueue_script('flutterwave', 'https://checkout.flutterwave.com/v3.js', array(), null, true);
        wp_enqueue_script('vehdoc-payment', VEHDOC_URI . '/js/payment.js', array('jquery', 'paystack', 'flutterwave'), VEHDOC_VERSION, true);

        $paystack_key = get_option('vehdoc_paystack_public_key', '');
        $flutterwave_key = get_option('vehdoc_flutterwave_public_key', '');

        wp_localize_script('vehdoc-payment', 'vehdocPayment', array(
            'ajaxUrl'        => admin_url('admin-ajax.php'),
            'restUrl'        => rest_url('vehdoc/v1/'),
            'nonce'          => wp_create_nonce('wp_rest'),
            'paystackPublicKey'    => $paystack_key,
            'flutterwavePublicKey' => $flutterwave_key,
            'dashboardUrl'         => home_url('/dashboard/'),
            'currency'       => 'NGN',
            'siteName'       => get_bloginfo('name'),
            'siteUrl'        => home_url(),
        ));
    }
}
add_action('wp_enqueue_scripts', 'vehdoc_scripts');

/**
 * Admin Scripts and Styles
 */
function vehdoc_admin_scripts($hook) {
    wp_enqueue_style('vehdoc-admin-style', VEHDOC_URI . '/css/admin.css', array(), VEHDOC_VERSION);
    wp_enqueue_script('vehdoc-admin-js', VEHDOC_URI . '/js/admin.js', array('jquery', 'wp-color-picker'), VEHDOC_VERSION, true);
    wp_localize_script('vehdoc-admin-js', 'vehdocAdmin', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url('vehdoc/v1/'),
        'nonce'   => wp_create_nonce('wp_rest'),
    ));
}
add_action('admin_enqueue_scripts', 'vehdoc_admin_scripts');

/**
 * Include Theme Modules
 */
require_once VEHDOC_DIR . '/inc/custom-post-types.php';
require_once VEHDOC_DIR . '/inc/theme-options.php';
require_once VEHDOC_DIR . '/inc/rest-api.php';
require_once VEHDOC_DIR . '/inc/payment-gateway.php';
require_once VEHDOC_DIR . '/inc/user-dashboard.php';
require_once VEHDOC_DIR . '/inc/document-upload.php';
require_once VEHDOC_DIR . '/inc/order-tracking.php';
require_once VEHDOC_DIR . '/inc/delivery-system.php';
require_once VEHDOC_DIR . '/inc/reminder-system.php';
require_once VEHDOC_DIR . '/inc/admin-dashboard.php';
require_once VEHDOC_DIR . '/inc/security.php';

/**
 * Register Widget Areas
 */
function vehdoc_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Column 1', 'vehdoc'),
        'id'            => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
    register_sidebar(array(
        'name'          => __('Footer Column 2', 'vehdoc'),
        'id'            => 'footer-2',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
    register_sidebar(array(
        'name'          => __('Footer Column 3', 'vehdoc'),
        'id'            => 'footer-3',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'vehdoc_widgets_init');

/**
 * Custom Rewrite Rules
 */
function vehdoc_rewrite_rules() {
    add_rewrite_rule('dashboard/?$', 'index.php?pagename=dashboard', 'top');
    add_rewrite_rule('dashboard/vehicles/?$', 'index.php?pagename=dashboard&vehdoc_tab=vehicles', 'top');
    add_rewrite_rule('dashboard/orders/?$', 'index.php?pagename=dashboard&vehdoc_tab=orders', 'top');
    add_rewrite_rule('dashboard/tracking/([0-9]+)/?$', 'index.php?pagename=dashboard&vehdoc_tab=tracking&order_id=$matches[1]', 'top');
    add_rewrite_rule('dashboard/profile/?$', 'index.php?pagename=dashboard&vehdoc_tab=profile', 'top');
}
add_action('init', 'vehdoc_rewrite_rules');

function vehdoc_query_vars($vars) {
    $vars[] = 'vehdoc_tab';
    $vars[] = 'order_id';
    return $vars;
}
add_filter('query_vars', 'vehdoc_query_vars');

/**
 * Redirect non-logged-in users from dashboard
 */
function vehdoc_dashboard_redirect() {
    if (is_page_template('templates/template-dashboard.php') && !is_user_logged_in()) {
        wp_redirect(home_url('/login/'));
        exit;
    }
}
add_action('template_redirect', 'vehdoc_dashboard_redirect');

/**
 * Custom Login/Registration
 */
function vehdoc_custom_login_redirect($redirect_to, $requested_redirect_to, $user) {
    if (!is_wp_error($user)) {
        if (in_array('administrator', $user->roles)) {
            return admin_url();
        }
        return home_url('/dashboard/');
    }
    return $redirect_to;
}
add_filter('login_redirect', 'vehdoc_custom_login_redirect', 10, 3);

/**
 * Allow SVG uploads for admins
 */
function vehdoc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'vehdoc_mime_types');

/**
 * Theme Activation: Create Default Pages & Services
 */
function vehdoc_activate() {
    $pages = array(
        'Dashboard'  => 'templates/template-dashboard.php',
        'Services'   => 'templates/template-services.php',
        'Login'      => 'templates/template-login.php',
        'Register'   => 'templates/template-register.php',
        'Checkout'   => 'templates/template-checkout.php',
        'Track Order'=> 'templates/template-tracking.php',
    );

    foreach ($pages as $title => $template) {
        $existing = get_page_by_title($title, OBJECT, 'page');
        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title'   => $title,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => '',
            ));
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $template);
            }
        }
    }

    // Create default services
    $default_services = array(
        array('title' => 'Vehicle Licence Renewal', 'price' => 25000, 'time' => '3-5 business days', 'desc' => 'Renew your vehicle licence quickly and easily. We handle the entire process from submission to delivery.'),
        array('title' => 'Proof of Ownership', 'price' => 18000, 'time' => '5-7 business days', 'desc' => 'Get your proof of ownership certificate processed without the hassle of visiting government offices.'),
        array('title' => 'Road Worthiness Certificate', 'price' => 20000, 'time' => '3-5 business days', 'desc' => 'Obtain your road worthiness certificate with our streamlined process and doorstep delivery.'),
        array('title' => 'Change of Ownership', 'price' => 45000, 'time' => '7-14 business days', 'desc' => 'Transfer vehicle ownership seamlessly with all documentation handled by our expert team.'),
        array('title' => 'Plate Number Processing', 'price' => 70000, 'time' => '14-21 business days', 'desc' => 'Get your vehicle plate number processed and delivered to your doorstep.'),
        array('title' => "Driver's Licence Renewal", 'price' => 35000, 'time' => '5-10 business days', 'desc' => "Renew your driver's licence without the long queues. We handle everything for you."),
        array('title' => 'Tinted Permit Processing', 'price' => 55000, 'time' => '5-7 business days', 'desc' => 'Get your vehicle tinted glass permit processed legally and efficiently.'),
        array('title' => 'Vehicle Insurance Processing', 'price' => 30000, 'time' => '1-3 business days', 'desc' => 'Get comprehensive vehicle insurance coverage with our trusted insurance partners.'),
        array('title' => 'Hackney Permit', 'price' => 40000, 'time' => '7-10 business days', 'desc' => 'Obtain your hackney permit for commercial vehicles with full documentation support.'),
        array('title' => 'Fleet Registration Services', 'price' => 150000, 'time' => '14-30 business days', 'desc' => 'Complete fleet registration and documentation services for businesses with multiple vehicles.'),
    );

    foreach ($default_services as $service) {
        $existing = get_page_by_title($service['title'], OBJECT, 'vehdoc_service');
        if (!$existing) {
            $post_id = wp_insert_post(array(
                'post_title'   => $service['title'],
                'post_status'  => 'publish',
                'post_type'    => 'vehdoc_service',
                'post_content' => $service['desc'],
            ));
            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, '_vehdoc_service_price', $service['price']);
                update_post_meta($post_id, '_vehdoc_service_time', $service['time']);
                update_post_meta($post_id, '_vehdoc_fast_track_price', round($service['price'] * 1.5));
                update_post_meta($post_id, '_vehdoc_delivery_fee', 3000);
            }
        }
    }

    flush_rewrite_rules();
}
add_action('after_switch_theme', 'vehdoc_activate');

/**
 * AJAX Handler: User Registration
 */
function vehdoc_ajax_register() {
    check_ajax_referer('wp_rest', 'nonce');

    $email     = sanitize_email($_POST['email'] ?? '');
    $password  = sanitize_text_field($_POST['password'] ?? '');
    $full_name = sanitize_text_field($_POST['full_name'] ?? '');
    $phone     = sanitize_text_field($_POST['phone'] ?? '');

    if (empty($email) || empty($password) || empty($full_name) || empty($phone)) {
        wp_send_json_error(array('message' => 'All fields are required.'));
    }

    if (email_exists($email)) {
        wp_send_json_error(array('message' => 'Email already registered.'));
    }

    if (strlen($password) < 8) {
        wp_send_json_error(array('message' => 'Password must be at least 8 characters.'));
    }

    $username = sanitize_user(strtolower(str_replace(' ', '', $full_name)) . rand(100, 999));
    $user_id  = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    }

    wp_update_user(array(
        'ID'           => $user_id,
        'display_name' => $full_name,
        'first_name'   => explode(' ', $full_name)[0],
        'last_name'    => implode(' ', array_slice(explode(' ', $full_name), 1)),
    ));

    update_user_meta($user_id, 'vehdoc_phone', $phone);
    update_user_meta($user_id, 'vehdoc_verified', false);
    update_user_meta($user_id, 'vehdoc_registered_at', current_time('mysql'));

    // Auto login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    wp_send_json_success(array(
        'message'  => 'Registration successful!',
        'redirect' => home_url('/dashboard/'),
    ));
}
add_action('wp_ajax_nopriv_vehdoc_register', 'vehdoc_ajax_register');

/**
 * AJAX Handler: User Login
 */
function vehdoc_ajax_login() {
    check_ajax_referer('wp_rest', 'nonce');

    $email    = sanitize_email($_POST['email'] ?? '');
    $password = sanitize_text_field($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        wp_send_json_error(array('message' => 'Email and password are required.'));
    }

    $user = wp_authenticate($email, $password);
    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => 'Invalid email or password.'));
    }

    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);

    $redirect = in_array('administrator', $user->roles) ? admin_url() : home_url('/dashboard/');

    wp_send_json_success(array(
        'message'  => 'Login successful!',
        'redirect' => $redirect,
    ));
}
add_action('wp_ajax_nopriv_vehdoc_login', 'vehdoc_ajax_login');

/**
 * AJAX Handler: Forgot Password
 */
function vehdoc_ajax_forgot_password() {
    check_ajax_referer('wp_rest', 'nonce');

    $email = sanitize_email($_POST['email'] ?? '');
    if (empty($email)) {
        wp_send_json_error(array('message' => 'Email is required.'));
    }

    $user = get_user_by('email', $email);
    if (!$user) {
        wp_send_json_error(array('message' => 'No account found with that email.'));
    }

    $reset_key = get_password_reset_key($user);
    if (is_wp_error($reset_key)) {
        wp_send_json_error(array('message' => 'Error generating reset link.'));
    }

    $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user->user_login), 'login');

    $message = sprintf(
        "Hi %s,\n\nYou requested a password reset for your Vehdoc account.\n\nClick here to reset your password:\n%s\n\nIf you didn't request this, please ignore this email.\n\n— Vehdoc Team",
        $user->display_name,
        $reset_url
    );

    wp_mail($email, 'Reset Your Vehdoc Password', $message);

    wp_send_json_success(array('message' => 'Password reset link sent to your email.'));
}
add_action('wp_ajax_nopriv_vehdoc_forgot_password', 'vehdoc_ajax_forgot_password');
