<?php
/**
 * Vehdoc Order Tracking System
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Get Order Tracking Data
 */
function vehdoc_get_tracking_data($order_id) {
    $order = get_post($order_id);
    if (!$order) return null;

    $status  = get_post_meta($order_id, '_vehdoc_order_status', true) ?: 'pending';
    $history = get_post_meta($order_id, '_vehdoc_status_history', true) ?: array();

    $steps = array(
        'pending'            => array('label' => 'Order Placed', 'icon' => 'fa-clipboard-check', 'description' => 'Your order has been placed and is awaiting processing.'),
        'documents_received' => array('label' => 'Documents Received', 'icon' => 'fa-file-alt', 'description' => 'We have received your documents and are reviewing them.'),
        'processing'         => array('label' => 'Processing', 'icon' => 'fa-cog', 'description' => 'Your documents are being processed with the relevant authority.'),
        'approved'           => array('label' => 'Approved', 'icon' => 'fa-check-circle', 'description' => 'Your documents have been approved and are being prepared.'),
        'ready_for_delivery' => array('label' => 'Ready for Delivery', 'icon' => 'fa-box', 'description' => 'Your documents are ready and awaiting delivery pickup.'),
        'delivered'          => array('label' => 'Delivered', 'icon' => 'fa-home', 'description' => 'Your documents have been delivered successfully!'),
    );

    $status_keys   = array_keys($steps);
    $current_index = array_search($status, $status_keys);

    $tracking = array();
    foreach ($steps as $key => $step) {
        $step_index = array_search($key, $status_keys);
        $timestamp  = null;

        foreach ($history as $h) {
            if ($h['to'] === $key) {
                $timestamp = $h['timestamp'];
                break;
            }
        }

        $tracking[] = array(
            'key'         => $key,
            'label'       => $step['label'],
            'icon'        => $step['icon'],
            'description' => $step['description'],
            'completed'   => $step_index <= $current_index,
            'current'     => $step_index === $current_index,
            'timestamp'   => $timestamp,
        );
    }

    return array(
        'current_status' => $status,
        'steps'          => $tracking,
        'order_number'   => 'VHD-' . str_pad($order_id, 6, '0', STR_PAD_LEFT),
    );
}

/**
 * AJAX: Get Order Tracking
 */
function vehdoc_ajax_get_tracking() {
    check_ajax_referer('wp_rest', 'nonce');

    $order_id = intval($_GET['order_id'] ?? $_POST['order_id'] ?? 0);
    if (!$order_id) {
        wp_send_json_error(array('message' => 'Order ID required'));
    }

    $order_user = get_post_meta($order_id, '_vehdoc_order_user', true);
    if ($order_user != get_current_user_id() && !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Access denied'));
    }

    $tracking = vehdoc_get_tracking_data($order_id);
    if (!$tracking) {
        wp_send_json_error(array('message' => 'Order not found'));
    }

    wp_send_json_success($tracking);
}
add_action('wp_ajax_vehdoc_get_tracking', 'vehdoc_ajax_get_tracking');

/**
 * Shortcode: Order Tracking
 */
function vehdoc_tracking_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<p class="vehdoc-notice">Please <a href="' . home_url('/login/') . '">log in</a> to track your order.</p>';
    }

    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

    if (!$order_id) {
        return '<div class="vehdoc-tracking-search">
            <h3>Track Your Order</h3>
            <form method="get" class="vehdoc-search-form">
                <input type="text" name="order_id" placeholder="Enter Order Number (e.g., VHD-000123)" class="vehdoc-input" required>
                <button type="submit" class="vehdoc-btn vehdoc-btn-primary">Track Order</button>
            </form>
        </div>';
    }

    $tracking = vehdoc_get_tracking_data($order_id);
    if (!$tracking) {
        return '<p class="vehdoc-notice vehdoc-notice-error">Order not found.</p>';
    }

    ob_start();
    ?>
    <div class="vehdoc-tracking-container">
        <div class="vehdoc-tracking-header">
            <h3>Order <?php echo esc_html($tracking['order_number']); ?></h3>
            <span class="vehdoc-status-badge status-<?php echo esc_attr($tracking['current_status']); ?>">
                <?php echo esc_html(ucwords(str_replace('_', ' ', $tracking['current_status']))); ?>
            </span>
        </div>

        <div class="vehdoc-progress-tracker">
            <?php foreach ($tracking['steps'] as $index => $step) : ?>
                <div class="vehdoc-progress-step <?php echo $step['completed'] ? 'completed' : ''; ?> <?php echo $step['current'] ? 'current' : ''; ?>">
                    <div class="step-indicator">
                        <div class="step-circle">
                            <i class="fa-solid <?php echo esc_attr($step['icon']); ?>"></i>
                        </div>
                        <?php if ($index < count($tracking['steps']) - 1) : ?>
                            <div class="step-line"></div>
                        <?php endif; ?>
                    </div>
                    <div class="step-content">
                        <h4><?php echo esc_html($step['label']); ?></h4>
                        <p><?php echo esc_html($step['description']); ?></p>
                        <?php if ($step['timestamp']) : ?>
                            <span class="step-time"><?php echo esc_html(date('M j, Y g:i A', strtotime($step['timestamp']))); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('vehdoc_tracking', 'vehdoc_tracking_shortcode');

/**
 * Get Tracking by Order Number (VHD-XXXXXX format)
 */
function vehdoc_get_tracking_by_number($order_number) {
    $order_number = strtoupper(trim($order_number));
    $order_id     = 0;

    if (preg_match('/^VHD-?(\d+)$/i', $order_number, $m)) {
        $order_id = intval($m[1]);
    } elseif (is_numeric($order_number)) {
        $order_id = intval($order_number);
    }

    if (!$order_id) return null;

    $order = get_post($order_id);
    if (!$order || $order->post_type !== 'vehdoc_order') return null;

    $order_user = get_post_meta($order_id, '_vehdoc_order_user', true);
    if ($order_user != get_current_user_id() && !current_user_can('manage_options')) {
        return null;
    }

    $tracking = vehdoc_get_tracking_data($order_id);
    if (!$tracking) return null;

    $service_id = get_post_meta($order_id, '_vehdoc_order_service', true);
    $service    = $service_id ? get_post($service_id) : null;

    $tracking['service'] = $service ? $service->post_title : 'Vehicle Documentation Service';
    $tracking['status']  = get_post_meta($order_id, '_vehdoc_order_status', true) ?: 'pending';

    return $tracking;
}
