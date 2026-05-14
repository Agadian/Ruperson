<?php
/**
 * Vehdoc Custom Post Types & Taxonomies
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Register Custom Post Types
 */
function vehdoc_register_post_types() {
    // Services
    register_post_type('vehdoc_service', array(
        'labels' => array(
            'name'               => __('Services', 'vehdoc'),
            'singular_name'      => __('Service', 'vehdoc'),
            'add_new'            => __('Add New Service', 'vehdoc'),
            'add_new_item'       => __('Add New Service', 'vehdoc'),
            'edit_item'          => __('Edit Service', 'vehdoc'),
            'all_items'          => __('All Services', 'vehdoc'),
            'search_items'       => __('Search Services', 'vehdoc'),
            'not_found'          => __('No services found', 'vehdoc'),
            'menu_name'          => __('Services', 'vehdoc'),
        ),
        'public'             => true,
        'has_archive'        => false,
        'rewrite'            => array('slug' => 'service'),
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon'          => 'dashicons-clipboard',
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ));

    // Orders
    register_post_type('vehdoc_order', array(
        'labels' => array(
            'name'               => __('Orders', 'vehdoc'),
            'singular_name'      => __('Order', 'vehdoc'),
            'add_new'            => __('Add New Order', 'vehdoc'),
            'edit_item'          => __('Edit Order', 'vehdoc'),
            'all_items'          => __('All Orders', 'vehdoc'),
            'search_items'       => __('Search Orders', 'vehdoc'),
            'not_found'          => __('No orders found', 'vehdoc'),
            'menu_name'          => __('Orders', 'vehdoc'),
        ),
        'public'             => false,
        'show_ui'            => true,
        'has_archive'        => false,
        'supports'           => array('title'),
        'menu_icon'          => 'dashicons-cart',
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ));

    // Vehicles
    register_post_type('vehdoc_vehicle', array(
        'labels' => array(
            'name'               => __('Vehicles', 'vehdoc'),
            'singular_name'      => __('Vehicle', 'vehdoc'),
            'add_new'            => __('Add New Vehicle', 'vehdoc'),
            'edit_item'          => __('Edit Vehicle', 'vehdoc'),
            'all_items'          => __('All Vehicles', 'vehdoc'),
            'search_items'       => __('Search Vehicles', 'vehdoc'),
            'not_found'          => __('No vehicles found', 'vehdoc'),
            'menu_name'          => __('Vehicles', 'vehdoc'),
        ),
        'public'             => false,
        'show_ui'            => true,
        'has_archive'        => false,
        'supports'           => array('title'),
        'menu_icon'          => 'dashicons-car',
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ));

    // Deliveries
    register_post_type('vehdoc_delivery', array(
        'labels' => array(
            'name'               => __('Deliveries', 'vehdoc'),
            'singular_name'      => __('Delivery', 'vehdoc'),
            'add_new'            => __('Add New Delivery', 'vehdoc'),
            'edit_item'          => __('Edit Delivery', 'vehdoc'),
            'all_items'          => __('All Deliveries', 'vehdoc'),
            'search_items'       => __('Search Deliveries', 'vehdoc'),
            'not_found'          => __('No deliveries found', 'vehdoc'),
            'menu_name'          => __('Deliveries', 'vehdoc'),
        ),
        'public'             => false,
        'show_ui'            => true,
        'has_archive'        => false,
        'supports'           => array('title'),
        'menu_icon'          => 'dashicons-location',
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ));

    // Transactions
    register_post_type('vehdoc_transaction', array(
        'labels' => array(
            'name'               => __('Transactions', 'vehdoc'),
            'singular_name'      => __('Transaction', 'vehdoc'),
            'all_items'          => __('All Transactions', 'vehdoc'),
            'search_items'       => __('Search Transactions', 'vehdoc'),
            'not_found'          => __('No transactions found', 'vehdoc'),
            'menu_name'          => __('Transactions', 'vehdoc'),
        ),
        'public'             => false,
        'show_ui'            => true,
        'has_archive'        => false,
        'supports'           => array('title'),
        'menu_icon'          => 'dashicons-money-alt',
        'show_in_rest'       => true,
        'capability_type'    => 'post',
    ));
}
add_action('init', 'vehdoc_register_post_types');

/**
 * Register Order Status Taxonomy
 */
function vehdoc_register_taxonomies() {
    register_taxonomy('vehdoc_order_status', 'vehdoc_order', array(
        'labels' => array(
            'name'          => __('Order Statuses', 'vehdoc'),
            'singular_name' => __('Order Status', 'vehdoc'),
            'menu_name'     => __('Statuses', 'vehdoc'),
        ),
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => true,
        'show_in_rest' => true,
    ));

    register_taxonomy('vehdoc_service_category', 'vehdoc_service', array(
        'labels' => array(
            'name'          => __('Service Categories', 'vehdoc'),
            'singular_name' => __('Service Category', 'vehdoc'),
            'menu_name'     => __('Categories', 'vehdoc'),
        ),
        'public'       => true,
        'show_ui'      => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array('slug' => 'service-category'),
    ));
}
add_action('init', 'vehdoc_register_taxonomies');

/**
 * Service Meta Boxes
 */
function vehdoc_service_meta_boxes() {
    add_meta_box('vehdoc_service_details', __('Service Details', 'vehdoc'), 'vehdoc_service_details_callback', 'vehdoc_service', 'normal', 'high');
}
add_action('add_meta_boxes', 'vehdoc_service_meta_boxes');

function vehdoc_service_details_callback($post) {
    wp_nonce_field('vehdoc_service_meta', 'vehdoc_service_nonce');

    $price           = get_post_meta($post->ID, '_vehdoc_service_price', true);
    $fast_track      = get_post_meta($post->ID, '_vehdoc_fast_track_price', true);
    $processing_time = get_post_meta($post->ID, '_vehdoc_service_time', true);
    $delivery_fee    = get_post_meta($post->ID, '_vehdoc_delivery_fee', true);
    $requirements    = get_post_meta($post->ID, '_vehdoc_requirements', true);
    $icon            = get_post_meta($post->ID, '_vehdoc_service_icon', true);
    ?>
    <table class="form-table vehdoc-meta-table">
        <tr>
            <th><label for="vehdoc_service_price"><?php _e('Price (₦)', 'vehdoc'); ?></label></th>
            <td><input type="number" id="vehdoc_service_price" name="vehdoc_service_price" value="<?php echo esc_attr($price); ?>" class="regular-text" step="100" min="0"></td>
        </tr>
        <tr>
            <th><label for="vehdoc_fast_track_price"><?php _e('Fast-Track Price (₦)', 'vehdoc'); ?></label></th>
            <td><input type="number" id="vehdoc_fast_track_price" name="vehdoc_fast_track_price" value="<?php echo esc_attr($fast_track); ?>" class="regular-text" step="100" min="0"></td>
        </tr>
        <tr>
            <th><label for="vehdoc_service_time"><?php _e('Processing Time', 'vehdoc'); ?></label></th>
            <td><input type="text" id="vehdoc_service_time" name="vehdoc_service_time" value="<?php echo esc_attr($processing_time); ?>" class="regular-text" placeholder="e.g. 3-5 business days"></td>
        </tr>
        <tr>
            <th><label for="vehdoc_delivery_fee"><?php _e('Delivery Fee (₦)', 'vehdoc'); ?></label></th>
            <td><input type="number" id="vehdoc_delivery_fee" name="vehdoc_delivery_fee" value="<?php echo esc_attr($delivery_fee); ?>" class="regular-text" step="100" min="0"></td>
        </tr>
        <tr>
            <th><label for="vehdoc_service_icon"><?php _e('Icon Class', 'vehdoc'); ?></label></th>
            <td><input type="text" id="vehdoc_service_icon" name="vehdoc_service_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text" placeholder="e.g. fa-solid fa-car"></td>
        </tr>
        <tr>
            <th><label for="vehdoc_requirements"><?php _e('Requirements (one per line)', 'vehdoc'); ?></label></th>
            <td><textarea id="vehdoc_requirements" name="vehdoc_requirements" rows="5" class="large-text"><?php echo esc_textarea($requirements); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function vehdoc_save_service_meta($post_id) {
    if (!isset($_POST['vehdoc_service_nonce']) || !wp_verify_nonce($_POST['vehdoc_service_nonce'], 'vehdoc_service_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array(
        'vehdoc_service_price'    => '_vehdoc_service_price',
        'vehdoc_fast_track_price' => '_vehdoc_fast_track_price',
        'vehdoc_service_time'     => '_vehdoc_service_time',
        'vehdoc_delivery_fee'     => '_vehdoc_delivery_fee',
        'vehdoc_service_icon'     => '_vehdoc_service_icon',
        'vehdoc_requirements'     => '_vehdoc_requirements',
    );

    foreach ($fields as $input => $meta_key) {
        if (isset($_POST[$input])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$input]));
        }
    }
}
add_action('save_post_vehdoc_service', 'vehdoc_save_service_meta');

/**
 * Order Meta Boxes
 */
function vehdoc_order_meta_boxes() {
    add_meta_box('vehdoc_order_details', __('Order Details', 'vehdoc'), 'vehdoc_order_details_callback', 'vehdoc_order', 'normal', 'high');
}
add_action('add_meta_boxes', 'vehdoc_order_meta_boxes');

function vehdoc_order_details_callback($post) {
    wp_nonce_field('vehdoc_order_meta', 'vehdoc_order_nonce');

    $user_id    = get_post_meta($post->ID, '_vehdoc_order_user', true);
    $service_id = get_post_meta($post->ID, '_vehdoc_order_service', true);
    $vehicle_id = get_post_meta($post->ID, '_vehdoc_order_vehicle', true);
    $amount     = get_post_meta($post->ID, '_vehdoc_order_amount', true);
    $status     = get_post_meta($post->ID, '_vehdoc_order_status', true) ?: 'pending';
    $payment_ref = get_post_meta($post->ID, '_vehdoc_payment_ref', true);
    $fast_track = get_post_meta($post->ID, '_vehdoc_fast_track', true);
    $notes      = get_post_meta($post->ID, '_vehdoc_order_notes', true);

    $statuses = array(
        'pending'            => __('Pending', 'vehdoc'),
        'documents_received' => __('Documents Received', 'vehdoc'),
        'processing'         => __('Processing', 'vehdoc'),
        'approved'           => __('Approved', 'vehdoc'),
        'ready_for_delivery' => __('Ready for Delivery', 'vehdoc'),
        'delivered'          => __('Delivered', 'vehdoc'),
        'cancelled'          => __('Cancelled', 'vehdoc'),
    );

    $user = $user_id ? get_user_by('ID', $user_id) : null;
    $service = $service_id ? get_post($service_id) : null;
    ?>
    <table class="form-table vehdoc-meta-table">
        <tr>
            <th><?php _e('Customer', 'vehdoc'); ?></th>
            <td><?php echo $user ? esc_html($user->display_name . ' (' . $user->user_email . ')') : __('N/A', 'vehdoc'); ?></td>
        </tr>
        <tr>
            <th><?php _e('Service', 'vehdoc'); ?></th>
            <td><?php echo $service ? esc_html($service->post_title) : __('N/A', 'vehdoc'); ?></td>
        </tr>
        <tr>
            <th><?php _e('Amount', 'vehdoc'); ?></th>
            <td>₦<?php echo number_format(floatval($amount)); ?></td>
        </tr>
        <tr>
            <th><?php _e('Payment Reference', 'vehdoc'); ?></th>
            <td><?php echo esc_html($payment_ref ?: 'N/A'); ?></td>
        </tr>
        <tr>
            <th><?php _e('Fast Track', 'vehdoc'); ?></th>
            <td><?php echo $fast_track ? __('Yes', 'vehdoc') : __('No', 'vehdoc'); ?></td>
        </tr>
        <tr>
            <th><label for="vehdoc_order_status"><?php _e('Status', 'vehdoc'); ?></label></th>
            <td>
                <select id="vehdoc_order_status" name="vehdoc_order_status">
                    <?php foreach ($statuses as $key => $label) : ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($status, $key); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="vehdoc_order_notes"><?php _e('Admin Notes', 'vehdoc'); ?></label></th>
            <td><textarea id="vehdoc_order_notes" name="vehdoc_order_notes" rows="4" class="large-text"><?php echo esc_textarea($notes); ?></textarea></td>
        </tr>
    </table>
    <?php

    // Show uploaded documents
    $documents = get_post_meta($post->ID, '_vehdoc_documents', true);
    if (!empty($documents) && is_array($documents)) {
        echo '<h3>' . __('Uploaded Documents', 'vehdoc') . '</h3>';
        echo '<div class="vehdoc-documents-grid">';
        foreach ($documents as $doc) {
            $url = wp_get_attachment_url($doc);
            $filename = basename(get_attached_file($doc));
            echo '<div class="vehdoc-document-item">';
            echo '<a href="' . esc_url($url) . '" target="_blank"><i class="dashicons dashicons-media-document"></i> ' . esc_html($filename) . '</a>';
            echo '</div>';
        }
        echo '</div>';
    }
}

function vehdoc_save_order_meta($post_id) {
    if (!isset($_POST['vehdoc_order_nonce']) || !wp_verify_nonce($_POST['vehdoc_order_nonce'], 'vehdoc_order_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['vehdoc_order_status'])) {
        $old_status = get_post_meta($post_id, '_vehdoc_order_status', true);
        $new_status = sanitize_text_field($_POST['vehdoc_order_status']);
        update_post_meta($post_id, '_vehdoc_order_status', $new_status);

        if ($old_status !== $new_status) {
            vehdoc_log_status_change($post_id, $old_status, $new_status);
            vehdoc_notify_status_change($post_id, $new_status);
        }
    }

    if (isset($_POST['vehdoc_order_notes'])) {
        update_post_meta($post_id, '_vehdoc_order_notes', sanitize_textarea_field($_POST['vehdoc_order_notes']));
    }
}
add_action('save_post_vehdoc_order', 'vehdoc_save_order_meta');

/**
 * Log status changes with timestamps
 */
function vehdoc_log_status_change($order_id, $old_status, $new_status) {
    $history = get_post_meta($order_id, '_vehdoc_status_history', true) ?: array();
    $history[] = array(
        'from'      => $old_status,
        'to'        => $new_status,
        'timestamp' => current_time('mysql'),
        'by'        => get_current_user_id(),
    );
    update_post_meta($order_id, '_vehdoc_status_history', $history);
}

/**
 * Notify user of status change
 */
function vehdoc_notify_status_change($order_id, $new_status) {
    $user_id = get_post_meta($order_id, '_vehdoc_order_user', true);
    if (!$user_id) return;

    $user = get_user_by('ID', $user_id);
    if (!$user) return;

    $service_id = get_post_meta($order_id, '_vehdoc_order_service', true);
    $service    = get_post($service_id);

    $status_labels = array(
        'pending'            => 'Pending',
        'documents_received' => 'Documents Received',
        'processing'         => 'Processing',
        'approved'           => 'Approved',
        'ready_for_delivery' => 'Ready for Delivery',
        'delivered'          => 'Delivered',
        'cancelled'          => 'Cancelled',
    );

    $status_label = $status_labels[$new_status] ?? $new_status;

    $subject = sprintf('[Vehdoc] Order #%d Status Update: %s', $order_id, $status_label);
    $message = sprintf(
        "Hi %s,\n\nYour order #%d for \"%s\" has been updated.\n\nNew Status: %s\n\nLog in to your dashboard to view details:\n%s\n\n— Vehdoc Team",
        $user->display_name,
        $order_id,
        $service ? $service->post_title : 'Vehicle Service',
        $status_label,
        home_url('/dashboard/')
    );

    wp_mail($user->user_email, $subject, $message);

    // Store notification for dashboard
    $notifications = get_user_meta($user_id, 'vehdoc_notifications', true) ?: array();
    array_unshift($notifications, array(
        'type'      => 'status_update',
        'order_id'  => $order_id,
        'status'    => $new_status,
        'message'   => sprintf('Order #%d updated to: %s', $order_id, $status_label),
        'read'      => false,
        'timestamp' => current_time('mysql'),
    ));
    update_user_meta($user_id, 'vehdoc_notifications', array_slice($notifications, 0, 50));
}

/**
 * Vehicle Meta Boxes
 */
function vehdoc_vehicle_meta_boxes() {
    add_meta_box('vehdoc_vehicle_details', __('Vehicle Details', 'vehdoc'), 'vehdoc_vehicle_details_callback', 'vehdoc_vehicle', 'normal', 'high');
}
add_action('add_meta_boxes', 'vehdoc_vehicle_meta_boxes');

function vehdoc_vehicle_details_callback($post) {
    wp_nonce_field('vehdoc_vehicle_meta', 'vehdoc_vehicle_nonce');

    $fields = array(
        '_vehdoc_vehicle_make'    => __('Make', 'vehdoc'),
        '_vehdoc_vehicle_model'   => __('Model', 'vehdoc'),
        '_vehdoc_vehicle_year'    => __('Year', 'vehdoc'),
        '_vehdoc_plate_number'    => __('Plate Number', 'vehdoc'),
        '_vehdoc_chassis_number'  => __('Chassis Number', 'vehdoc'),
        '_vehdoc_engine_number'   => __('Engine Number', 'vehdoc'),
        '_vehdoc_vehicle_color'   => __('Color', 'vehdoc'),
        '_vehdoc_owner_name'      => __('Owner Name', 'vehdoc'),
        '_vehdoc_owner_address'   => __('Owner Address', 'vehdoc'),
    );
    ?>
    <table class="form-table vehdoc-meta-table">
        <?php foreach ($fields as $key => $label) : ?>
        <tr>
            <th><label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
            <td><input type="text" id="<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr(get_post_meta($post->ID, $key, true)); ?>" class="regular-text"></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

function vehdoc_save_vehicle_meta($post_id) {
    if (!isset($_POST['vehdoc_vehicle_nonce']) || !wp_verify_nonce($_POST['vehdoc_vehicle_nonce'], 'vehdoc_vehicle_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = array(
        '_vehdoc_vehicle_make', '_vehdoc_vehicle_model', '_vehdoc_vehicle_year',
        '_vehdoc_plate_number', '_vehdoc_chassis_number', '_vehdoc_engine_number',
        '_vehdoc_vehicle_color', '_vehdoc_owner_name', '_vehdoc_owner_address',
    );

    foreach ($fields as $key) {
        if (isset($_POST[$key])) {
            update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
        }
    }
}
add_action('save_post_vehdoc_vehicle', 'vehdoc_save_vehicle_meta');

/**
 * Admin Columns for Orders
 */
function vehdoc_order_columns($columns) {
    return array(
        'cb'          => '<input type="checkbox" />',
        'title'       => __('Order', 'vehdoc'),
        'customer'    => __('Customer', 'vehdoc'),
        'service'     => __('Service', 'vehdoc'),
        'amount'      => __('Amount', 'vehdoc'),
        'status'      => __('Status', 'vehdoc'),
        'date'        => __('Date', 'vehdoc'),
    );
}
add_filter('manage_vehdoc_order_posts_columns', 'vehdoc_order_columns');

function vehdoc_order_column_data($column, $post_id) {
    switch ($column) {
        case 'customer':
            $user_id = get_post_meta($post_id, '_vehdoc_order_user', true);
            $user = $user_id ? get_user_by('ID', $user_id) : null;
            echo $user ? esc_html($user->display_name) : '—';
            break;
        case 'service':
            $service_id = get_post_meta($post_id, '_vehdoc_order_service', true);
            $service = $service_id ? get_post($service_id) : null;
            echo $service ? esc_html($service->post_title) : '—';
            break;
        case 'amount':
            echo '₦' . number_format(floatval(get_post_meta($post_id, '_vehdoc_order_amount', true)));
            break;
        case 'status':
            $status = get_post_meta($post_id, '_vehdoc_order_status', true) ?: 'pending';
            $labels = array(
                'pending' => '<span class="vehdoc-badge badge-pending">Pending</span>',
                'documents_received' => '<span class="vehdoc-badge badge-received">Docs Received</span>',
                'processing' => '<span class="vehdoc-badge badge-processing">Processing</span>',
                'approved' => '<span class="vehdoc-badge badge-approved">Approved</span>',
                'ready_for_delivery' => '<span class="vehdoc-badge badge-ready">Ready</span>',
                'delivered' => '<span class="vehdoc-badge badge-delivered">Delivered</span>',
                'cancelled' => '<span class="vehdoc-badge badge-cancelled">Cancelled</span>',
            );
            echo $labels[$status] ?? esc_html($status);
            break;
    }
}
add_action('manage_vehdoc_order_posts_custom_column', 'vehdoc_order_column_data', 10, 2);
