<?php
/**
 * Vehdoc REST API Endpoints
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Register REST API Routes
 */
function vehdoc_register_rest_routes() {
    $namespace = 'vehdoc/v1';

    // Services
    register_rest_route($namespace, '/services', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_get_services',
        'permission_callback' => '__return_true',
    ));

    register_rest_route($namespace, '/services/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_get_service',
        'permission_callback' => '__return_true',
    ));

    // User Registration
    register_rest_route($namespace, '/register', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_register',
        'permission_callback' => '__return_true',
    ));

    // User Login
    register_rest_route($namespace, '/login', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_login',
        'permission_callback' => '__return_true',
    ));

    // User Profile
    register_rest_route($namespace, '/profile', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_get_profile',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    register_rest_route($namespace, '/profile', array(
        'methods'             => 'PUT',
        'callback'            => 'vehdoc_api_update_profile',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    // Vehicles
    register_rest_route($namespace, '/vehicles', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_get_vehicles',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    register_rest_route($namespace, '/vehicles', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_create_vehicle',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    register_rest_route($namespace, '/vehicles/(?P<id>\d+)', array(
        'methods'             => 'PUT',
        'callback'            => 'vehdoc_api_update_vehicle',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    register_rest_route($namespace, '/vehicles/(?P<id>\d+)', array(
        'methods'             => 'DELETE',
        'callback'            => 'vehdoc_api_delete_vehicle',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    // Orders
    register_rest_route($namespace, '/orders', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_get_orders',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    register_rest_route($namespace, '/orders', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_create_order',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    register_rest_route($namespace, '/orders/(?P<id>\d+)', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_get_order',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    // Documents Upload
    register_rest_route($namespace, '/orders/(?P<id>\d+)/documents', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_upload_documents',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    // Payments
    register_rest_route($namespace, '/payments/verify', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_verify_payment',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    // Notifications
    register_rest_route($namespace, '/notifications', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_get_notifications',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    register_rest_route($namespace, '/notifications/(?P<id>\d+)/read', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_mark_notification_read',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    // Delivery
    register_rest_route($namespace, '/delivery/schedule', array(
        'methods'             => 'POST',
        'callback'            => 'vehdoc_api_schedule_delivery',
        'permission_callback' => 'vehdoc_api_check_auth',
    ));

    // Dashboard Stats (admin)
    register_rest_route($namespace, '/admin/stats', array(
        'methods'             => 'GET',
        'callback'            => 'vehdoc_api_admin_stats',
        'permission_callback' => 'vehdoc_api_check_admin',
    ));

    // Admin: Update order status
    register_rest_route($namespace, '/admin/orders/(?P<id>\d+)/status', array(
        'methods'             => 'PUT',
        'callback'            => 'vehdoc_api_admin_update_status',
        'permission_callback' => 'vehdoc_api_check_admin',
    ));
}
add_action('rest_api_init', 'vehdoc_register_rest_routes');

/**
 * Auth Check
 */
function vehdoc_api_check_auth($request) {
    return is_user_logged_in();
}

function vehdoc_api_check_admin($request) {
    return current_user_can('manage_options');
}

/**
 * GET Services
 */
function vehdoc_api_get_services($request) {
    $services = get_posts(array(
        'post_type'      => 'vehdoc_service',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ));

    $data = array();
    foreach ($services as $service) {
        $data[] = vehdoc_format_service($service);
    }

    return rest_ensure_response($data);
}

function vehdoc_api_get_service($request) {
    $service = get_post($request['id']);
    if (!$service || $service->post_type !== 'vehdoc_service') {
        return new WP_Error('not_found', 'Service not found', array('status' => 404));
    }
    return rest_ensure_response(vehdoc_format_service($service));
}

function vehdoc_format_service($service) {
    $requirements = get_post_meta($service->ID, '_vehdoc_requirements', true);
    return array(
        'id'               => $service->ID,
        'title'            => $service->post_title,
        'description'      => $service->post_content,
        'excerpt'          => $service->post_excerpt,
        'price'            => floatval(get_post_meta($service->ID, '_vehdoc_service_price', true)),
        'fast_track_price' => floatval(get_post_meta($service->ID, '_vehdoc_fast_track_price', true)),
        'processing_time'  => get_post_meta($service->ID, '_vehdoc_service_time', true),
        'delivery_fee'     => floatval(get_post_meta($service->ID, '_vehdoc_delivery_fee', true)),
        'icon'             => get_post_meta($service->ID, '_vehdoc_service_icon', true),
        'requirements'     => $requirements ? array_filter(array_map('trim', explode("\n", $requirements))) : array(),
        'thumbnail'        => get_the_post_thumbnail_url($service->ID, 'vehdoc-service'),
    );
}

/**
 * User Registration API
 */
function vehdoc_api_register($request) {
    $params = $request->get_json_params();

    $email     = sanitize_email($params['email'] ?? '');
    $password  = $params['password'] ?? '';
    $full_name = sanitize_text_field($params['full_name'] ?? '');
    $phone     = sanitize_text_field($params['phone'] ?? '');

    if (empty($email) || empty($password) || empty($full_name) || empty($phone)) {
        return new WP_Error('missing_fields', 'All fields are required', array('status' => 400));
    }

    if (email_exists($email)) {
        return new WP_Error('email_exists', 'Email already registered', array('status' => 409));
    }

    $username = sanitize_user(strtolower(str_replace(' ', '', $full_name)) . rand(100, 999));
    $user_id  = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        return $user_id;
    }

    wp_update_user(array(
        'ID'           => $user_id,
        'display_name' => $full_name,
        'first_name'   => explode(' ', $full_name)[0],
        'last_name'    => implode(' ', array_slice(explode(' ', $full_name), 1)),
    ));

    update_user_meta($user_id, 'vehdoc_phone', $phone);
    update_user_meta($user_id, 'vehdoc_registered_at', current_time('mysql'));

    return rest_ensure_response(array(
        'success' => true,
        'user_id' => $user_id,
        'message' => 'Registration successful',
    ));
}

/**
 * User Login API
 */
function vehdoc_api_login($request) {
    $params   = $request->get_json_params();
    $email    = sanitize_email($params['email'] ?? '');
    $password = $params['password'] ?? '';

    $user = wp_authenticate($email, $password);
    if (is_wp_error($user)) {
        return new WP_Error('invalid_credentials', 'Invalid email or password', array('status' => 401));
    }

    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID);

    return rest_ensure_response(array(
        'success' => true,
        'user'    => array(
            'id'           => $user->ID,
            'email'        => $user->user_email,
            'display_name' => $user->display_name,
            'phone'        => get_user_meta($user->ID, 'vehdoc_phone', true),
        ),
    ));
}

/**
 * GET Profile
 */
function vehdoc_api_get_profile($request) {
    $user = wp_get_current_user();
    return rest_ensure_response(array(
        'id'           => $user->ID,
        'email'        => $user->user_email,
        'display_name' => $user->display_name,
        'first_name'   => $user->first_name,
        'last_name'    => $user->last_name,
        'phone'        => get_user_meta($user->ID, 'vehdoc_phone', true),
        'address'      => get_user_meta($user->ID, 'vehdoc_address', true),
        'state'        => get_user_meta($user->ID, 'vehdoc_state', true),
        'city'         => get_user_meta($user->ID, 'vehdoc_city', true),
        'referral_code'=> get_user_meta($user->ID, 'vehdoc_referral_code', true),
        'registered'   => get_user_meta($user->ID, 'vehdoc_registered_at', true),
    ));
}

/**
 * UPDATE Profile
 */
function vehdoc_api_update_profile($request) {
    $params  = $request->get_json_params();
    $user_id = get_current_user_id();

    $allowed = array('first_name', 'last_name', 'display_name');
    $user_data = array('ID' => $user_id);
    foreach ($allowed as $field) {
        if (isset($params[$field])) {
            $user_data[$field] = sanitize_text_field($params[$field]);
        }
    }
    wp_update_user($user_data);

    $meta_fields = array('vehdoc_phone', 'vehdoc_address', 'vehdoc_state', 'vehdoc_city');
    foreach ($meta_fields as $field) {
        $key = str_replace('vehdoc_', '', $field);
        if (isset($params[$key])) {
            update_user_meta($user_id, $field, sanitize_text_field($params[$key]));
        }
    }

    return rest_ensure_response(array('success' => true, 'message' => 'Profile updated'));
}

/**
 * GET Vehicles
 */
function vehdoc_api_get_vehicles($request) {
    $vehicles = get_posts(array(
        'post_type'      => 'vehdoc_vehicle',
        'posts_per_page' => -1,
        'author'         => get_current_user_id(),
        'post_status'    => 'publish',
    ));

    $data = array();
    foreach ($vehicles as $vehicle) {
        $data[] = vehdoc_format_vehicle($vehicle);
    }

    return rest_ensure_response($data);
}

function vehdoc_format_vehicle($vehicle) {
    return array(
        'id'             => $vehicle->ID,
        'title'          => $vehicle->post_title,
        'make'           => get_post_meta($vehicle->ID, '_vehdoc_vehicle_make', true),
        'model'          => get_post_meta($vehicle->ID, '_vehdoc_vehicle_model', true),
        'year'           => get_post_meta($vehicle->ID, '_vehdoc_vehicle_year', true),
        'plate_number'   => get_post_meta($vehicle->ID, '_vehdoc_plate_number', true),
        'chassis_number' => get_post_meta($vehicle->ID, '_vehdoc_chassis_number', true),
        'engine_number'  => get_post_meta($vehicle->ID, '_vehdoc_engine_number', true),
        'color'          => get_post_meta($vehicle->ID, '_vehdoc_vehicle_color', true),
        'owner_name'     => get_post_meta($vehicle->ID, '_vehdoc_owner_name', true),
        'owner_address'  => get_post_meta($vehicle->ID, '_vehdoc_owner_address', true),
    );
}

/**
 * CREATE Vehicle
 */
function vehdoc_api_create_vehicle($request) {
    $params = $request->get_json_params();
    $user_id = get_current_user_id();

    $plate = sanitize_text_field($params['plate_number'] ?? '');
    $make  = sanitize_text_field($params['make'] ?? '');
    $model = sanitize_text_field($params['model'] ?? '');

    if (empty($plate) || empty($make)) {
        return new WP_Error('missing_fields', 'Plate number and make are required', array('status' => 400));
    }

    $post_id = wp_insert_post(array(
        'post_title'  => $make . ' ' . $model . ' — ' . $plate,
        'post_type'   => 'vehdoc_vehicle',
        'post_status' => 'publish',
        'post_author' => $user_id,
    ));

    if (is_wp_error($post_id)) return $post_id;

    $meta_map = array(
        'make' => '_vehdoc_vehicle_make', 'model' => '_vehdoc_vehicle_model',
        'year' => '_vehdoc_vehicle_year', 'plate_number' => '_vehdoc_plate_number',
        'chassis_number' => '_vehdoc_chassis_number', 'engine_number' => '_vehdoc_engine_number',
        'color' => '_vehdoc_vehicle_color', 'owner_name' => '_vehdoc_owner_name',
        'owner_address' => '_vehdoc_owner_address',
    );

    foreach ($meta_map as $param => $meta_key) {
        if (isset($params[$param])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($params[$param]));
        }
    }

    return rest_ensure_response(array(
        'success'    => true,
        'vehicle_id' => $post_id,
        'message'    => 'Vehicle added successfully',
    ));
}

/**
 * UPDATE Vehicle
 */
function vehdoc_api_update_vehicle($request) {
    $vehicle = get_post($request['id']);
    if (!$vehicle || $vehicle->post_author != get_current_user_id()) {
        return new WP_Error('not_found', 'Vehicle not found', array('status' => 404));
    }

    $params = $request->get_json_params();
    $meta_map = array(
        'make' => '_vehdoc_vehicle_make', 'model' => '_vehdoc_vehicle_model',
        'year' => '_vehdoc_vehicle_year', 'plate_number' => '_vehdoc_plate_number',
        'chassis_number' => '_vehdoc_chassis_number', 'engine_number' => '_vehdoc_engine_number',
        'color' => '_vehdoc_vehicle_color', 'owner_name' => '_vehdoc_owner_name',
        'owner_address' => '_vehdoc_owner_address',
    );

    foreach ($meta_map as $param => $meta_key) {
        if (isset($params[$param])) {
            update_post_meta($vehicle->ID, $meta_key, sanitize_text_field($params[$param]));
        }
    }

    return rest_ensure_response(array('success' => true, 'message' => 'Vehicle updated'));
}

/**
 * DELETE Vehicle
 */
function vehdoc_api_delete_vehicle($request) {
    $vehicle = get_post($request['id']);
    if (!$vehicle || $vehicle->post_author != get_current_user_id()) {
        return new WP_Error('not_found', 'Vehicle not found', array('status' => 404));
    }

    wp_delete_post($vehicle->ID, true);
    return rest_ensure_response(array('success' => true, 'message' => 'Vehicle deleted'));
}

/**
 * GET Orders
 */
function vehdoc_api_get_orders($request) {
    $args = array(
        'post_type'      => 'vehdoc_order',
        'posts_per_page' => intval($request->get_param('per_page') ?: 20),
        'paged'          => intval($request->get_param('page') ?: 1),
        'post_status'    => 'publish',
        'meta_key'       => '_vehdoc_order_user',
        'meta_value'     => get_current_user_id(),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $orders = get_posts($args);
    $data   = array();

    foreach ($orders as $order) {
        $data[] = vehdoc_format_order($order);
    }

    return rest_ensure_response($data);
}

function vehdoc_api_get_order($request) {
    $order = get_post($request['id']);
    if (!$order || get_post_meta($order->ID, '_vehdoc_order_user', true) != get_current_user_id()) {
        return new WP_Error('not_found', 'Order not found', array('status' => 404));
    }
    return rest_ensure_response(vehdoc_format_order($order));
}

function vehdoc_format_order($order) {
    $service_id = get_post_meta($order->ID, '_vehdoc_order_service', true);
    $vehicle_id = get_post_meta($order->ID, '_vehdoc_order_vehicle', true);
    $service    = $service_id ? get_post($service_id) : null;

    return array(
        'id'              => $order->ID,
        'order_number'    => 'VHD-' . str_pad($order->ID, 6, '0', STR_PAD_LEFT),
        'service'         => $service ? $service->post_title : '',
        'service_id'      => intval($service_id),
        'vehicle_id'      => intval($vehicle_id),
        'amount'          => floatval(get_post_meta($order->ID, '_vehdoc_order_amount', true)),
        'status'          => get_post_meta($order->ID, '_vehdoc_order_status', true) ?: 'pending',
        'fast_track'      => (bool)get_post_meta($order->ID, '_vehdoc_fast_track', true),
        'payment_ref'     => get_post_meta($order->ID, '_vehdoc_payment_ref', true),
        'payment_method'  => get_post_meta($order->ID, '_vehdoc_payment_method', true),
        'delivery_address'=> get_post_meta($order->ID, '_vehdoc_delivery_address', true),
        'delivery_date'   => get_post_meta($order->ID, '_vehdoc_delivery_date', true),
        'express_delivery'=> (bool)get_post_meta($order->ID, '_vehdoc_express_delivery', true),
        'status_history'  => get_post_meta($order->ID, '_vehdoc_status_history', true) ?: array(),
        'documents'       => vehdoc_get_order_documents($order->ID),
        'created_at'      => $order->post_date,
        'updated_at'      => $order->post_modified,
    );
}

function vehdoc_get_order_documents($order_id) {
    $doc_ids = get_post_meta($order_id, '_vehdoc_documents', true) ?: array();
    $docs = array();
    foreach ($doc_ids as $doc_id) {
        $docs[] = array(
            'id'       => $doc_id,
            'url'      => wp_get_attachment_url($doc_id),
            'filename' => basename(get_attached_file($doc_id)),
            'type'     => get_post_mime_type($doc_id),
        );
    }
    return $docs;
}

/**
 * CREATE Order
 */
function vehdoc_api_create_order($request) {
    $params  = $request->get_json_params();
    $user_id = get_current_user_id();

    $service_id = intval($params['service_id'] ?? 0);
    $vehicle_id = intval($params['vehicle_id'] ?? 0);
    $fast_track = (bool)($params['fast_track'] ?? false);
    $delivery   = (bool)($params['delivery'] ?? true);
    $express    = (bool)($params['express_delivery'] ?? false);
    $address    = sanitize_textarea_field($params['delivery_address'] ?? '');
    $date       = sanitize_text_field($params['delivery_date'] ?? '');

    $service = get_post($service_id);
    if (!$service || $service->post_type !== 'vehdoc_service') {
        return new WP_Error('invalid_service', 'Invalid service', array('status' => 400));
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
        'post_author' => $user_id,
    ));

    if (is_wp_error($order_id)) return $order_id;

    update_post_meta($order_id, '_vehdoc_order_user', $user_id);
    update_post_meta($order_id, '_vehdoc_order_service', $service_id);
    update_post_meta($order_id, '_vehdoc_order_vehicle', $vehicle_id);
    update_post_meta($order_id, '_vehdoc_order_amount', $price);
    update_post_meta($order_id, '_vehdoc_order_status', 'pending');
    update_post_meta($order_id, '_vehdoc_fast_track', $fast_track);
    update_post_meta($order_id, '_vehdoc_delivery_address', $address);
    update_post_meta($order_id, '_vehdoc_delivery_date', $date);
    update_post_meta($order_id, '_vehdoc_express_delivery', $express);
    update_post_meta($order_id, '_vehdoc_status_history', array(
        array('from' => '', 'to' => 'pending', 'timestamp' => current_time('mysql'), 'by' => $user_id),
    ));

    return rest_ensure_response(array(
        'success'      => true,
        'order_id'     => $order_id,
        'order_number' => 'VHD-' . str_pad($order_id, 6, '0', STR_PAD_LEFT),
        'amount'       => $price,
        'message'      => 'Order created successfully',
    ));
}

/**
 * Upload Documents
 */
function vehdoc_api_upload_documents($request) {
    $order = get_post($request['id']);
    if (!$order || get_post_meta($order->ID, '_vehdoc_order_user', true) != get_current_user_id()) {
        return new WP_Error('not_found', 'Order not found', array('status' => 404));
    }

    $files = $request->get_file_params();
    if (empty($files['documents'])) {
        return new WP_Error('no_files', 'No documents uploaded', array('status' => 400));
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $existing_docs = get_post_meta($order->ID, '_vehdoc_documents', true) ?: array();
    $uploaded = array();

    $docs = $files['documents'];
    $file_count = is_array($docs['name']) ? count($docs['name']) : 1;

    for ($i = 0; $i < $file_count; $i++) {
        $file = array(
            'name'     => is_array($docs['name']) ? $docs['name'][$i] : $docs['name'],
            'type'     => is_array($docs['type']) ? $docs['type'][$i] : $docs['type'],
            'tmp_name' => is_array($docs['tmp_name']) ? $docs['tmp_name'][$i] : $docs['tmp_name'],
            'error'    => is_array($docs['error']) ? $docs['error'][$i] : $docs['error'],
            'size'     => is_array($docs['size']) ? $docs['size'][$i] : $docs['size'],
        );

        $_FILES['document'] = $file;
        $attachment_id = media_handle_upload('document', $order->ID);

        if (!is_wp_error($attachment_id)) {
            $existing_docs[] = $attachment_id;
            $uploaded[] = array(
                'id'       => $attachment_id,
                'url'      => wp_get_attachment_url($attachment_id),
                'filename' => $file['name'],
            );
        }
    }

    update_post_meta($order->ID, '_vehdoc_documents', $existing_docs);

    if (!empty($uploaded)) {
        $status = get_post_meta($order->ID, '_vehdoc_order_status', true);
        if ($status === 'pending') {
            update_post_meta($order->ID, '_vehdoc_order_status', 'documents_received');
            vehdoc_log_status_change($order->ID, 'pending', 'documents_received');
        }
    }

    return rest_ensure_response(array(
        'success'   => true,
        'documents' => $uploaded,
        'message'   => count($uploaded) . ' document(s) uploaded',
    ));
}

/**
 * Verify Payment
 */
function vehdoc_api_verify_payment($request) {
    $params = $request->get_json_params();
    $order_id    = intval($params['order_id'] ?? 0);
    $reference   = sanitize_text_field($params['reference'] ?? '');
    $gateway     = sanitize_text_field($params['gateway'] ?? 'paystack');

    $order = get_post($order_id);
    if (!$order || get_post_meta($order->ID, '_vehdoc_order_user', true) != get_current_user_id()) {
        return new WP_Error('not_found', 'Order not found', array('status' => 404));
    }

    $verified = vehdoc_verify_payment_with_gateway($reference, $gateway);

    if ($verified) {
        update_post_meta($order_id, '_vehdoc_payment_ref', $reference);
        update_post_meta($order_id, '_vehdoc_payment_method', $gateway);
        update_post_meta($order_id, '_vehdoc_payment_verified', true);
        update_post_meta($order_id, '_vehdoc_payment_date', current_time('mysql'));

        // Create transaction record
        $tx_id = wp_insert_post(array(
            'post_title'  => 'Payment — Order #' . $order_id,
            'post_type'   => 'vehdoc_transaction',
            'post_status' => 'publish',
        ));
        if ($tx_id && !is_wp_error($tx_id)) {
            update_post_meta($tx_id, '_vehdoc_tx_order', $order_id);
            update_post_meta($tx_id, '_vehdoc_tx_user', get_current_user_id());
            update_post_meta($tx_id, '_vehdoc_tx_amount', get_post_meta($order_id, '_vehdoc_order_amount', true));
            update_post_meta($tx_id, '_vehdoc_tx_reference', $reference);
            update_post_meta($tx_id, '_vehdoc_tx_gateway', $gateway);
            update_post_meta($tx_id, '_vehdoc_tx_status', 'success');
        }

        return rest_ensure_response(array('success' => true, 'message' => 'Payment verified'));
    }

    return new WP_Error('payment_failed', 'Payment verification failed', array('status' => 400));
}

/**
 * GET Notifications
 */
function vehdoc_api_get_notifications($request) {
    $notifications = get_user_meta(get_current_user_id(), 'vehdoc_notifications', true) ?: array();
    return rest_ensure_response($notifications);
}

function vehdoc_api_mark_notification_read($request) {
    $user_id = get_current_user_id();
    $index   = intval($request['id']);
    $notifications = get_user_meta($user_id, 'vehdoc_notifications', true) ?: array();

    if (isset($notifications[$index])) {
        $notifications[$index]['read'] = true;
        update_user_meta($user_id, 'vehdoc_notifications', $notifications);
    }

    return rest_ensure_response(array('success' => true));
}

/**
 * Schedule Delivery
 */
function vehdoc_api_schedule_delivery($request) {
    $params   = $request->get_json_params();
    $order_id = intval($params['order_id'] ?? 0);
    $address  = sanitize_textarea_field($params['address'] ?? '');
    $date     = sanitize_text_field($params['preferred_date'] ?? '');
    $time     = sanitize_text_field($params['preferred_time'] ?? '');
    $express  = (bool)($params['express'] ?? false);

    $order = get_post($order_id);
    if (!$order || get_post_meta($order->ID, '_vehdoc_order_user', true) != get_current_user_id()) {
        return new WP_Error('not_found', 'Order not found', array('status' => 404));
    }

    $delivery_id = wp_insert_post(array(
        'post_title'  => 'Delivery — Order #' . $order_id,
        'post_type'   => 'vehdoc_delivery',
        'post_status' => 'publish',
    ));

    if (is_wp_error($delivery_id)) return $delivery_id;

    update_post_meta($delivery_id, '_vehdoc_delivery_order', $order_id);
    update_post_meta($delivery_id, '_vehdoc_delivery_user', get_current_user_id());
    update_post_meta($delivery_id, '_vehdoc_delivery_address', $address);
    update_post_meta($delivery_id, '_vehdoc_delivery_date', $date);
    update_post_meta($delivery_id, '_vehdoc_delivery_time', $time);
    update_post_meta($delivery_id, '_vehdoc_delivery_express', $express);
    update_post_meta($delivery_id, '_vehdoc_delivery_status', 'scheduled');

    update_post_meta($order_id, '_vehdoc_delivery_id', $delivery_id);
    update_post_meta($order_id, '_vehdoc_delivery_address', $address);
    update_post_meta($order_id, '_vehdoc_delivery_date', $date);

    return rest_ensure_response(array(
        'success'     => true,
        'delivery_id' => $delivery_id,
        'message'     => 'Delivery scheduled successfully',
    ));
}

/**
 * Admin Stats
 */
function vehdoc_api_admin_stats($request) {
    global $wpdb;

    $total_users  = count_users()['total_users'];
    $total_orders = wp_count_posts('vehdoc_order')->publish;

    $revenue = $wpdb->get_var("
        SELECT SUM(pm.meta_value)
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_amount'
        AND p.post_type = 'vehdoc_order'
        AND p.post_status = 'publish'
    ");

    $pending = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_status'
        AND pm.meta_value = %s
        AND p.post_type = 'vehdoc_order'
    ", 'pending'));

    $completed = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*)
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_status'
        AND pm.meta_value = %s
        AND p.post_type = 'vehdoc_order'
    ", 'delivered'));

    // Monthly revenue for chart
    $monthly = $wpdb->get_results("
        SELECT DATE_FORMAT(p.post_date, '%Y-%m') as month, SUM(pm.meta_value) as revenue
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_amount'
        AND p.post_type = 'vehdoc_order'
        AND p.post_status = 'publish'
        AND p.post_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(p.post_date, '%Y-%m')
        ORDER BY month ASC
    ");

    // Most requested services
    $popular = $wpdb->get_results("
        SELECT pm.meta_value as service_id, COUNT(*) as count
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_service'
        AND p.post_type = 'vehdoc_order'
        GROUP BY pm.meta_value
        ORDER BY count DESC
        LIMIT 5
    ");

    $popular_services = array();
    foreach ($popular as $item) {
        $service = get_post($item->service_id);
        $popular_services[] = array(
            'name'  => $service ? $service->post_title : 'Unknown',
            'count' => intval($item->count),
        );
    }

    return rest_ensure_response(array(
        'total_users'      => intval($total_users),
        'total_orders'     => intval($total_orders),
        'total_revenue'    => floatval($revenue ?: 0),
        'pending_orders'   => intval($pending),
        'completed_orders' => intval($completed),
        'monthly_revenue'  => $monthly,
        'popular_services' => $popular_services,
    ));
}

/**
 * Admin: Update Order Status
 */
function vehdoc_api_admin_update_status($request) {
    $params = $request->get_json_params();
    $order  = get_post($request['id']);

    if (!$order || $order->post_type !== 'vehdoc_order') {
        return new WP_Error('not_found', 'Order not found', array('status' => 404));
    }

    $new_status = sanitize_text_field($params['status'] ?? '');
    $valid = array('pending', 'documents_received', 'processing', 'approved', 'ready_for_delivery', 'delivered', 'cancelled');

    if (!in_array($new_status, $valid)) {
        return new WP_Error('invalid_status', 'Invalid status', array('status' => 400));
    }

    $old_status = get_post_meta($order->ID, '_vehdoc_order_status', true);
    update_post_meta($order->ID, '_vehdoc_order_status', $new_status);
    vehdoc_log_status_change($order->ID, $old_status, $new_status);
    vehdoc_notify_status_change($order->ID, $new_status);

    return rest_ensure_response(array('success' => true, 'message' => 'Status updated to ' . $new_status));
}
