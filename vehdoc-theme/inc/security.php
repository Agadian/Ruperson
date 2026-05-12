<?php
/**
 * Vehdoc Security Module
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Rate Limiting for Login Attempts
 */
function vehdoc_check_login_attempts() {
    if (!isset($_POST['log'])) return;

    $ip = vehdoc_get_client_ip();
    $transient_key = 'vehdoc_login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key) ?: 0;

    if ($attempts >= 5) {
        wp_die(
            __('Too many failed login attempts. Please try again in 15 minutes.', 'vehdoc'),
            __('Login Blocked', 'vehdoc'),
            array('response' => 429)
        );
    }
}
add_action('wp_login_failed', function($username) {
    $ip = vehdoc_get_client_ip();
    $transient_key = 'vehdoc_login_attempts_' . md5($ip);
    $attempts = get_transient($transient_key) ?: 0;
    set_transient($transient_key, $attempts + 1, 15 * MINUTE_IN_SECONDS);
});

function vehdoc_clear_login_attempts($user_login, $user) {
    $ip = vehdoc_get_client_ip();
    delete_transient('vehdoc_login_attempts_' . md5($ip));
}
add_action('wp_login', 'vehdoc_clear_login_attempts', 10, 2);

/**
 * Get Client IP
 */
function vehdoc_get_client_ip() {
    $headers = array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR');
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            return trim($ips[0]);
        }
    }
    return '0.0.0.0';
}

/**
 * Secure File Upload Validation
 */
function vehdoc_validate_upload($file) {
    $allowed = array('pdf', 'jpg', 'jpeg', 'png', 'gif');
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        $file['error'] = __('File type not allowed. Please upload PDF, JPG, or PNG files.', 'vehdoc');
    }

    if ($file['size'] > 10 * 1024 * 1024) {
        $file['error'] = __('File size exceeds 10MB limit.', 'vehdoc');
    }

    return $file;
}
add_filter('wp_handle_upload_prefilter', 'vehdoc_validate_upload');

/**
 * Hide WordPress Version
 */
remove_action('wp_head', 'wp_generator');

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Security Headers
 */
function vehdoc_security_headers() {
    if (is_admin()) return;

    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
add_action('send_headers', 'vehdoc_security_headers');

/**
 * Prevent User Enumeration
 */
function vehdoc_prevent_user_enumeration($redirect, $request) {
    if (preg_match('/\?author=([0-9]*)/', $request)) {
        return home_url('/');
    }
    return $redirect;
}
add_filter('redirect_canonical', 'vehdoc_prevent_user_enumeration', 10, 2);

/**
 * CSRF Protection for AJAX
 */
function vehdoc_verify_ajax_nonce() {
    if (defined('DOING_AJAX') && DOING_AJAX) {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
        if (strpos($action, 'vehdoc_') === 0) {
            if (!check_ajax_referer('wp_rest', 'nonce', false)) {
                wp_send_json_error(array('message' => 'Security check failed'), 403);
            }
        }
    }
}

/**
 * Sanitize All Vehdoc Inputs
 */
function vehdoc_sanitize_input($input) {
    if (is_array($input)) {
        return array_map('vehdoc_sanitize_input', $input);
    }
    return sanitize_text_field($input);
}

/**
 * Admin Role Capabilities
 */
function vehdoc_add_caps() {
    $admin = get_role('administrator');
    if ($admin) {
        $admin->add_cap('manage_vehdoc');
        $admin->add_cap('manage_vehdoc_orders');
        $admin->add_cap('manage_vehdoc_services');
        $admin->add_cap('manage_vehdoc_deliveries');
        $admin->add_cap('view_vehdoc_analytics');
        $admin->add_cap('export_vehdoc_data');
    }
}
add_action('admin_init', 'vehdoc_add_caps');
