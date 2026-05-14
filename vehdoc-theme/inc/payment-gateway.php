<?php
/**
 * Vehdoc Payment Gateway Integration (Paystack & Flutterwave)
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Verify Payment with Gateway
 */
function vehdoc_verify_payment_with_gateway($reference, $gateway = 'paystack') {
    if ($gateway === 'paystack') {
        return vehdoc_verify_paystack($reference);
    } elseif ($gateway === 'flutterwave') {
        return vehdoc_verify_flutterwave($reference);
    }
    return false;
}

/**
 * Verify Paystack Payment
 */
function vehdoc_verify_paystack($reference) {
    $secret_key = get_option('vehdoc_paystack_secret_key');
    if (empty($secret_key)) return false;

    $response = wp_remote_get('https://api.paystack.co/transaction/verify/' . rawurlencode($reference), array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'  => 'application/json',
        ),
        'timeout' => 30,
    ));

    if (is_wp_error($response)) return false;

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return isset($body['status']) && $body['status'] === true && isset($body['data']['status']) && $body['data']['status'] === 'success';
}

/**
 * Verify Flutterwave Payment
 */
function vehdoc_verify_flutterwave($transaction_id) {
    $secret_key = get_option('vehdoc_flutterwave_secret_key');
    if (empty($secret_key)) return false;

    $response = wp_remote_get('https://api.flutterwave.com/v3/transactions/' . rawurlencode($transaction_id) . '/verify', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $secret_key,
            'Content-Type'  => 'application/json',
        ),
        'timeout' => 30,
    ));

    if (is_wp_error($response)) return false;

    $body = json_decode(wp_remote_retrieve_body($response), true);
    return isset($body['status']) && $body['status'] === 'success' && isset($body['data']['status']) && $body['data']['status'] === 'successful';
}

/**
 * Paystack Webhook Handler
 */
function vehdoc_paystack_webhook() {
    $input = file_get_contents('php://input');
    $secret_key = get_option('vehdoc_paystack_secret_key');

    if (empty($secret_key)) {
        http_response_code(400);
        exit;
    }

    $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
    if ($signature !== hash_hmac('sha512', $input, $secret_key)) {
        http_response_code(401);
        exit;
    }

    $event = json_decode($input, true);
    if ($event['event'] === 'charge.success') {
        $reference = $event['data']['reference'];
        vehdoc_process_successful_payment($reference, 'paystack');
    }

    http_response_code(200);
    exit;
}

/**
 * Flutterwave Webhook Handler
 */
function vehdoc_flutterwave_webhook() {
    $input = file_get_contents('php://input');
    $secret_hash = get_option('vehdoc_flutterwave_secret_hash', '');

    if (!empty($secret_hash)) {
        $signature = $_SERVER['HTTP_VERIF_HASH'] ?? '';
        if ($signature !== $secret_hash) {
            http_response_code(401);
            exit;
        }
    }

    $event = json_decode($input, true);
    if (isset($event['data']['status']) && $event['data']['status'] === 'successful') {
        $tx_ref = $event['data']['tx_ref'] ?? '';
        vehdoc_process_successful_payment($tx_ref, 'flutterwave');
    }

    http_response_code(200);
    exit;
}

/**
 * Process Successful Payment
 */
function vehdoc_process_successful_payment($reference, $gateway) {
    global $wpdb;

    $order_id = $wpdb->get_var($wpdb->prepare("
        SELECT post_id FROM {$wpdb->postmeta}
        WHERE meta_key = '_vehdoc_payment_ref' AND meta_value = %s
    ", $reference));

    if (!$order_id) return;

    update_post_meta($order_id, '_vehdoc_payment_verified', true);
    update_post_meta($order_id, '_vehdoc_payment_date', current_time('mysql'));

    $current_status = get_post_meta($order_id, '_vehdoc_order_status', true);
    if ($current_status === 'pending') {
        update_post_meta($order_id, '_vehdoc_order_status', 'documents_received');
        vehdoc_log_status_change($order_id, 'pending', 'documents_received');
        vehdoc_notify_status_change($order_id, 'documents_received');
    }

    // Generate receipt
    vehdoc_generate_receipt($order_id);
}

/**
 * Generate Digital Receipt
 */
function vehdoc_generate_receipt($order_id) {
    $order   = get_post($order_id);
    $user_id = get_post_meta($order_id, '_vehdoc_order_user', true);
    $user    = get_user_by('ID', $user_id);
    $service_id = get_post_meta($order_id, '_vehdoc_order_service', true);
    $service    = get_post($service_id);
    $amount     = get_post_meta($order_id, '_vehdoc_order_amount', true);
    $ref        = get_post_meta($order_id, '_vehdoc_payment_ref', true);
    $gateway    = get_post_meta($order_id, '_vehdoc_payment_method', true);
    $date       = get_post_meta($order_id, '_vehdoc_payment_date', true);

    $receipt = array(
        'order_number' => 'VHD-' . str_pad($order_id, 6, '0', STR_PAD_LEFT),
        'customer'     => $user ? $user->display_name : '',
        'email'        => $user ? $user->user_email : '',
        'service'      => $service ? $service->post_title : '',
        'amount'       => $amount,
        'reference'    => $ref,
        'gateway'      => $gateway,
        'date'         => $date,
        'company'      => get_option('vehdoc_company_name', 'Vehdoc'),
    );

    update_post_meta($order_id, '_vehdoc_receipt', $receipt);

    // Email receipt to user
    if ($user) {
        $subject = sprintf('[Vehdoc] Payment Receipt — Order #%s', $receipt['order_number']);
        $message = sprintf(
            "Hi %s,\n\nPayment received successfully!\n\nOrder: %s\nService: %s\nAmount: ₦%s\nReference: %s\nDate: %s\n\nView your order: %s\n\n— Vehdoc Team",
            $user->display_name,
            $receipt['order_number'],
            $receipt['service'],
            number_format($amount),
            $ref,
            $date,
            home_url('/dashboard/')
        );
        wp_mail($user->user_email, $subject, $message);
    }

    return $receipt;
}

/**
 * Register Webhook Endpoints
 */
function vehdoc_register_webhook_endpoints() {
    add_rewrite_rule('vehdoc-webhook/paystack/?$', 'index.php?vehdoc_webhook=paystack', 'top');
    add_rewrite_rule('vehdoc-webhook/flutterwave/?$', 'index.php?vehdoc_webhook=flutterwave', 'top');
}
add_action('init', 'vehdoc_register_webhook_endpoints');

function vehdoc_webhook_query_vars($vars) {
    $vars[] = 'vehdoc_webhook';
    return $vars;
}
add_filter('query_vars', 'vehdoc_webhook_query_vars');

function vehdoc_handle_webhook() {
    $webhook = get_query_var('vehdoc_webhook');
    if ($webhook === 'paystack') {
        vehdoc_paystack_webhook();
    } elseif ($webhook === 'flutterwave') {
        vehdoc_flutterwave_webhook();
    }
}
add_action('template_redirect', 'vehdoc_handle_webhook');
