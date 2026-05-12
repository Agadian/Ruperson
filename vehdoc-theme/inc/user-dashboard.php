<?php
/**
 * Vehdoc User Dashboard Functions
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Get Dashboard Stats for Current User
 */
function vehdoc_get_user_stats() {
    $user_id = get_current_user_id();

    $vehicles = wp_count_posts('vehdoc_vehicle');
    $user_vehicles = count(get_posts(array(
        'post_type'      => 'vehdoc_vehicle',
        'author'         => $user_id,
        'posts_per_page' => -1,
        'fields'         => 'ids',
    )));

    $orders = get_posts(array(
        'post_type'      => 'vehdoc_order',
        'posts_per_page' => -1,
        'meta_key'       => '_vehdoc_order_user',
        'meta_value'     => $user_id,
        'fields'         => 'ids',
    ));

    $total_spent   = 0;
    $active_orders = 0;
    $completed     = 0;

    foreach ($orders as $order_id) {
        $status = get_post_meta($order_id, '_vehdoc_order_status', true);
        $amount = floatval(get_post_meta($order_id, '_vehdoc_order_amount', true));

        if (in_array($status, array('pending', 'documents_received', 'processing', 'approved', 'ready_for_delivery'))) {
            $active_orders++;
        }
        if ($status === 'delivered') {
            $completed++;
        }
        if (get_post_meta($order_id, '_vehdoc_payment_verified', true)) {
            $total_spent += $amount;
        }
    }

    return array(
        'vehicles'      => $user_vehicles,
        'total_orders'  => count($orders),
        'active_orders' => $active_orders,
        'completed'     => $completed,
        'total_spent'   => $total_spent,
    );
}

/**
 * Get User's Recent Orders
 */
function vehdoc_get_user_orders($limit = 10) {
    $orders = get_posts(array(
        'post_type'      => 'vehdoc_order',
        'posts_per_page' => $limit,
        'meta_key'       => '_vehdoc_order_user',
        'meta_value'     => get_current_user_id(),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    $data = array();
    foreach ($orders as $order) {
        $service_id = get_post_meta($order->ID, '_vehdoc_order_service', true);
        $service    = $service_id ? get_post($service_id) : null;

        $data[] = array(
            'id'           => $order->ID,
            'order_number' => 'VHD-' . str_pad($order->ID, 6, '0', STR_PAD_LEFT),
            'service'      => $service ? $service->post_title : '',
            'amount'       => floatval(get_post_meta($order->ID, '_vehdoc_order_amount', true)),
            'status'       => get_post_meta($order->ID, '_vehdoc_order_status', true) ?: 'pending',
            'date'         => $order->post_date,
            'fast_track'   => (bool)get_post_meta($order->ID, '_vehdoc_fast_track', true),
        );
    }

    return $data;
}

/**
 * Get User's Vehicles
 */
function vehdoc_get_user_vehicles() {
    $vehicles = get_posts(array(
        'post_type'      => 'vehdoc_vehicle',
        'posts_per_page' => -1,
        'author'         => get_current_user_id(),
        'post_status'    => 'publish',
    ));

    $data = array();
    foreach ($vehicles as $vehicle) {
        $data[] = array(
            'id'             => $vehicle->ID,
            'make'           => get_post_meta($vehicle->ID, '_vehdoc_vehicle_make', true),
            'model'          => get_post_meta($vehicle->ID, '_vehdoc_vehicle_model', true),
            'year'           => get_post_meta($vehicle->ID, '_vehdoc_vehicle_year', true),
            'plate_number'   => get_post_meta($vehicle->ID, '_vehdoc_plate_number', true),
            'chassis_number' => get_post_meta($vehicle->ID, '_vehdoc_chassis_number', true),
            'engine_number'  => get_post_meta($vehicle->ID, '_vehdoc_engine_number', true),
            'color'          => get_post_meta($vehicle->ID, '_vehdoc_vehicle_color', true),
            'owner_name'     => get_post_meta($vehicle->ID, '_vehdoc_owner_name', true),
        );
    }

    return $data;
}

/**
 * Get Expiring Documents
 */
function vehdoc_get_expiring_documents($user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();

    $orders = get_posts(array(
        'post_type'      => 'vehdoc_order',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'AND',
            array('key' => '_vehdoc_order_user', 'value' => $user_id),
            array('key' => '_vehdoc_order_status', 'value' => 'delivered'),
            array('key' => '_vehdoc_expiry_date', 'compare' => 'EXISTS'),
        ),
    ));

    $expiring = array();
    $now = time();

    foreach ($orders as $order) {
        $expiry = get_post_meta($order->ID, '_vehdoc_expiry_date', true);
        if (empty($expiry)) continue;

        $expiry_time = strtotime($expiry);
        $days_left   = floor(($expiry_time - $now) / 86400);

        if ($days_left <= 30 && $days_left >= 0) {
            $service_id = get_post_meta($order->ID, '_vehdoc_order_service', true);
            $service    = $service_id ? get_post($service_id) : null;

            $expiring[] = array(
                'order_id'     => $order->ID,
                'service'      => $service ? $service->post_title : '',
                'expiry_date'  => $expiry,
                'days_left'    => $days_left,
                'order_number' => 'VHD-' . str_pad($order->ID, 6, '0', STR_PAD_LEFT),
            );
        }
    }

    usort($expiring, function($a, $b) {
        return $a['days_left'] - $b['days_left'];
    });

    return $expiring;
}

/**
 * Get Unread Notifications Count
 */
function vehdoc_get_unread_count() {
    $notifications = get_user_meta(get_current_user_id(), 'vehdoc_notifications', true) ?: array();
    return count(array_filter($notifications, function($n) {
        return empty($n['read']);
    }));
}

/**
 * Generate Referral Code
 */
function vehdoc_generate_referral_code($user_id) {
    $code = get_user_meta($user_id, 'vehdoc_referral_code', true);
    if (empty($code)) {
        $code = 'VHD' . strtoupper(substr(md5($user_id . time()), 0, 6));
        update_user_meta($user_id, 'vehdoc_referral_code', $code);
    }
    return $code;
}

/**
 * AJAX: Add Vehicle
 */
function vehdoc_ajax_add_vehicle() {
    check_ajax_referer('wp_rest', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Not authenticated'));
    }

    $make    = sanitize_text_field($_POST['make'] ?? '');
    $model   = sanitize_text_field($_POST['model'] ?? '');
    $year    = sanitize_text_field($_POST['year'] ?? '');
    $plate   = sanitize_text_field($_POST['plate_number'] ?? '');
    $chassis = sanitize_text_field($_POST['chassis_number'] ?? '');
    $engine  = sanitize_text_field($_POST['engine_number'] ?? '');
    $color   = sanitize_text_field($_POST['color'] ?? '');
    $owner   = sanitize_text_field($_POST['owner_name'] ?? '');

    if (empty($make) || empty($plate)) {
        wp_send_json_error(array('message' => 'Make and plate number are required'));
    }

    $post_id = wp_insert_post(array(
        'post_title'  => $make . ' ' . $model . ' — ' . $plate,
        'post_type'   => 'vehdoc_vehicle',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
    ));

    if (is_wp_error($post_id)) {
        wp_send_json_error(array('message' => 'Failed to add vehicle'));
    }

    update_post_meta($post_id, '_vehdoc_vehicle_make', $make);
    update_post_meta($post_id, '_vehdoc_vehicle_model', $model);
    update_post_meta($post_id, '_vehdoc_vehicle_year', $year);
    update_post_meta($post_id, '_vehdoc_plate_number', $plate);
    update_post_meta($post_id, '_vehdoc_chassis_number', $chassis);
    update_post_meta($post_id, '_vehdoc_engine_number', $engine);
    update_post_meta($post_id, '_vehdoc_vehicle_color', $color);
    update_post_meta($post_id, '_vehdoc_owner_name', $owner);

    wp_send_json_success(array(
        'message'    => 'Vehicle added successfully!',
        'vehicle_id' => $post_id,
    ));
}
add_action('wp_ajax_vehdoc_add_vehicle', 'vehdoc_ajax_add_vehicle');

/**
 * AJAX: Create Order
 */
function vehdoc_ajax_create_order() {
    check_ajax_referer('wp_rest', 'nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'Not authenticated'));
    }

    $service_id = intval($_POST['service_id'] ?? 0);
    $vehicle_id = intval($_POST['vehicle_id'] ?? 0);
    $fast_track = (bool)($_POST['fast_track'] ?? false);
    $delivery   = (bool)($_POST['delivery'] ?? true);
    $express    = (bool)($_POST['express_delivery'] ?? false);
    $address    = sanitize_textarea_field($_POST['delivery_address'] ?? '');

    $service = get_post($service_id);
    if (!$service || $service->post_type !== 'vehdoc_service') {
        wp_send_json_error(array('message' => 'Invalid service selected'));
    }

    $price = $fast_track
        ? floatval(get_post_meta($service_id, '_vehdoc_fast_track_price', true))
        : floatval(get_post_meta($service_id, '_vehdoc_service_price', true));

    if ($delivery) {
        $delivery_fee = $express
            ? floatval(get_option('vehdoc_express_delivery_fee', 5000))
            : floatval(get_post_meta($service_id, '_vehdoc_delivery_fee', true) ?: get_option('vehdoc_delivery_fee', 3000));
        $price += $delivery_fee;
    }

    $order_id = wp_insert_post(array(
        'post_title'  => 'Order — ' . $service->post_title,
        'post_type'   => 'vehdoc_order',
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
    ));

    if (is_wp_error($order_id)) {
        wp_send_json_error(array('message' => 'Failed to create order'));
    }

    $user_id = get_current_user_id();
    update_post_meta($order_id, '_vehdoc_order_user', $user_id);
    update_post_meta($order_id, '_vehdoc_order_service', $service_id);
    update_post_meta($order_id, '_vehdoc_order_vehicle', $vehicle_id);
    update_post_meta($order_id, '_vehdoc_order_amount', $price);
    update_post_meta($order_id, '_vehdoc_order_status', 'pending');
    update_post_meta($order_id, '_vehdoc_fast_track', $fast_track);
    update_post_meta($order_id, '_vehdoc_delivery_address', $address);
    update_post_meta($order_id, '_vehdoc_express_delivery', $express);

    wp_send_json_success(array(
        'message'      => 'Order created! Proceed to payment.',
        'order_id'     => $order_id,
        'order_number' => 'VHD-' . str_pad($order_id, 6, '0', STR_PAD_LEFT),
        'amount'       => $price,
    ));
}
add_action('wp_ajax_vehdoc_create_order', 'vehdoc_ajax_create_order');
