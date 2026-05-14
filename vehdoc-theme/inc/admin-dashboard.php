<?php
/**
 * Vehdoc Admin Dashboard & Analytics
 *
 * @package Vehdoc
 */

if (!defined('ABSPATH')) exit;

/**
 * Analytics Dashboard Page
 */
function vehdoc_analytics_page() {
    global $wpdb;

    $total_users  = count_users()['total_users'];
    $total_orders = wp_count_posts('vehdoc_order')->publish;

    $revenue = $wpdb->get_var("
        SELECT COALESCE(SUM(pm.meta_value), 0)
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_amount'
        AND p.post_type = 'vehdoc_order' AND p.post_status = 'publish'
    ");

    $statuses = array('pending', 'documents_received', 'processing', 'approved', 'ready_for_delivery', 'delivered', 'cancelled');
    $status_counts = array();
    foreach ($statuses as $s) {
        $status_counts[$s] = intval($wpdb->get_var($wpdb->prepare("
            SELECT COUNT(*) FROM {$wpdb->postmeta} pm
            JOIN {$wpdb->posts} p ON p.ID = pm.post_id
            WHERE pm.meta_key = '_vehdoc_order_status' AND pm.meta_value = %s
            AND p.post_type = 'vehdoc_order'
        ", $s)));
    }

    $monthly_revenue = $wpdb->get_results("
        SELECT DATE_FORMAT(p.post_date, '%Y-%m') as month,
               DATE_FORMAT(p.post_date, '%b %Y') as label,
               COALESCE(SUM(pm.meta_value), 0) as revenue,
               COUNT(*) as orders
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_amount'
        AND p.post_type = 'vehdoc_order' AND p.post_status = 'publish'
        AND p.post_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(p.post_date, '%Y-%m')
        ORDER BY month ASC
    ");

    $popular_services = $wpdb->get_results("
        SELECT pm.meta_value as service_id, COUNT(*) as count
        FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_service'
        AND p.post_type = 'vehdoc_order'
        GROUP BY pm.meta_value ORDER BY count DESC LIMIT 10
    ");

    $recent_orders = get_posts(array(
        'post_type'      => 'vehdoc_order',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));
    ?>
    <div class="wrap vehdoc-admin-wrap vehdoc-analytics">
        <h1><i class="dashicons dashicons-chart-area"></i> <?php _e('Vehdoc Analytics', 'vehdoc'); ?></h1>

        <!-- Stats Cards -->
        <div class="vehdoc-stats-grid">
            <div class="vehdoc-stat-card stat-users">
                <div class="stat-icon"><i class="dashicons dashicons-groups"></i></div>
                <div class="stat-content">
                    <span class="stat-number"><?php echo number_format($total_users); ?></span>
                    <span class="stat-label"><?php _e('Total Users', 'vehdoc'); ?></span>
                </div>
            </div>
            <div class="vehdoc-stat-card stat-revenue">
                <div class="stat-icon"><i class="dashicons dashicons-money-alt"></i></div>
                <div class="stat-content">
                    <span class="stat-number">₦<?php echo number_format(floatval($revenue)); ?></span>
                    <span class="stat-label"><?php _e('Total Revenue', 'vehdoc'); ?></span>
                </div>
            </div>
            <div class="vehdoc-stat-card stat-orders">
                <div class="stat-icon"><i class="dashicons dashicons-cart"></i></div>
                <div class="stat-content">
                    <span class="stat-number"><?php echo number_format($total_orders); ?></span>
                    <span class="stat-label"><?php _e('Total Orders', 'vehdoc'); ?></span>
                </div>
            </div>
            <div class="vehdoc-stat-card stat-pending">
                <div class="stat-icon"><i class="dashicons dashicons-clock"></i></div>
                <div class="stat-content">
                    <span class="stat-number"><?php echo number_format($status_counts['pending'] + $status_counts['processing']); ?></span>
                    <span class="stat-label"><?php _e('Pending/Processing', 'vehdoc'); ?></span>
                </div>
            </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="vehdoc-admin-card">
            <h2><?php _e('Order Status Breakdown', 'vehdoc'); ?></h2>
            <div class="vehdoc-status-bars">
                <?php
                $status_labels = array(
                    'pending'            => array('Pending', '#f59e0b'),
                    'documents_received' => array('Docs Received', '#3b82f6'),
                    'processing'         => array('Processing', '#8b5cf6'),
                    'approved'           => array('Approved', '#10b981'),
                    'ready_for_delivery' => array('Ready', '#06b6d4'),
                    'delivered'          => array('Delivered', '#22c55e'),
                    'cancelled'          => array('Cancelled', '#ef4444'),
                );
                foreach ($status_labels as $key => $info) :
                    $count = $status_counts[$key] ?? 0;
                    $pct   = $total_orders > 0 ? round(($count / $total_orders) * 100) : 0;
                ?>
                <div class="status-bar-row">
                    <span class="status-label"><?php echo esc_html($info[0]); ?></span>
                    <div class="status-bar-track">
                        <div class="status-bar-fill" style="width:<?php echo $pct; ?>%;background:<?php echo $info[1]; ?>"></div>
                    </div>
                    <span class="status-count"><?php echo $count; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="vehdoc-admin-row">
            <!-- Monthly Revenue Chart -->
            <div class="vehdoc-admin-card vehdoc-card-wide">
                <h2><?php _e('Monthly Revenue', 'vehdoc'); ?></h2>
                <div class="vehdoc-chart-container">
                    <canvas id="vehdocRevenueChart"></canvas>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof Chart === 'undefined') return;
                        var ctx = document.getElementById('vehdocRevenueChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: <?php echo json_encode(array_column($monthly_revenue, 'label')); ?>,
                                datasets: [{
                                    label: 'Revenue (₦)',
                                    data: <?php echo json_encode(array_map('floatval', array_column($monthly_revenue, 'revenue'))); ?>,
                                    backgroundColor: 'rgba(37, 99, 235, 0.8)',
                                    borderRadius: 6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { y: { beginAtZero: true, ticks: { callback: function(v) { return '₦' + v.toLocaleString(); } } } },
                                plugins: { legend: { display: false } }
                            }
                        });
                    });
                </script>
            </div>

            <!-- Popular Services -->
            <div class="vehdoc-admin-card">
                <h2><?php _e('Most Requested Services', 'vehdoc'); ?></h2>
                <table class="widefat striped">
                    <thead><tr><th><?php _e('Service', 'vehdoc'); ?></th><th><?php _e('Orders', 'vehdoc'); ?></th></tr></thead>
                    <tbody>
                    <?php foreach ($popular_services as $ps) :
                        $svc = get_post($ps->service_id); ?>
                        <tr>
                            <td><?php echo $svc ? esc_html($svc->post_title) : '—'; ?></td>
                            <td><strong><?php echo intval($ps->count); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="vehdoc-admin-card">
            <h2><?php _e('Recent Orders', 'vehdoc'); ?></h2>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php _e('Order', 'vehdoc'); ?></th>
                        <th><?php _e('Customer', 'vehdoc'); ?></th>
                        <th><?php _e('Service', 'vehdoc'); ?></th>
                        <th><?php _e('Amount', 'vehdoc'); ?></th>
                        <th><?php _e('Status', 'vehdoc'); ?></th>
                        <th><?php _e('Date', 'vehdoc'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recent_orders as $ro) :
                    $uid = get_post_meta($ro->ID, '_vehdoc_order_user', true);
                    $u   = $uid ? get_user_by('ID', $uid) : null;
                    $sid = get_post_meta($ro->ID, '_vehdoc_order_service', true);
                    $sv  = $sid ? get_post($sid) : null;
                    $st  = get_post_meta($ro->ID, '_vehdoc_order_status', true) ?: 'pending';
                    $amt = get_post_meta($ro->ID, '_vehdoc_order_amount', true);
                ?>
                <tr>
                    <td><a href="<?php echo get_edit_post_link($ro->ID); ?>">VHD-<?php echo str_pad($ro->ID, 6, '0', STR_PAD_LEFT); ?></a></td>
                    <td><?php echo $u ? esc_html($u->display_name) : '—'; ?></td>
                    <td><?php echo $sv ? esc_html($sv->post_title) : '—'; ?></td>
                    <td>₦<?php echo number_format(floatval($amt)); ?></td>
                    <td><span class="vehdoc-badge badge-<?php echo esc_attr($st); ?>"><?php echo esc_html(ucwords(str_replace('_', ' ', $st))); ?></span></td>
                    <td><?php echo get_the_date('M j, Y', $ro); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <?php
}

/**
 * Admin Export Customers
 */
function vehdoc_export_customers() {
    if (!current_user_can('manage_options')) return;
    if (!isset($_GET['vehdoc_export']) || $_GET['vehdoc_export'] !== 'customers') return;

    $users = get_users(array('role__not_in' => array('administrator')));

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="vehdoc-customers-' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Name', 'Email', 'Phone', 'State', 'Registered', 'Total Orders', 'Total Spent'));

    foreach ($users as $user) {
        $orders = get_posts(array(
            'post_type'      => 'vehdoc_order',
            'posts_per_page' => -1,
            'meta_key'       => '_vehdoc_order_user',
            'meta_value'     => $user->ID,
            'fields'         => 'ids',
        ));

        $spent = 0;
        foreach ($orders as $oid) {
            $spent += floatval(get_post_meta($oid, '_vehdoc_order_amount', true));
        }

        fputcsv($output, array(
            $user->ID,
            $user->display_name,
            $user->user_email,
            get_user_meta($user->ID, 'vehdoc_phone', true),
            get_user_meta($user->ID, 'vehdoc_state', true),
            get_user_meta($user->ID, 'vehdoc_registered_at', true),
            count($orders),
            $spent,
        ));
    }

    fclose($output);
    exit;
}
add_action('admin_init', 'vehdoc_export_customers');

/**
 * Admin Dashboard Widget
 */
function vehdoc_dashboard_widget() {
    wp_add_dashboard_widget('vehdoc_overview', 'Vehdoc Overview', 'vehdoc_dashboard_widget_content');
}
add_action('wp_dashboard_setup', 'vehdoc_dashboard_widget');

function vehdoc_dashboard_widget_content() {
    global $wpdb;

    $total_orders = wp_count_posts('vehdoc_order')->publish;
    $pending = $wpdb->get_var("
        SELECT COUNT(*) FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_status' AND pm.meta_value = 'pending'
        AND p.post_type = 'vehdoc_order'
    ");

    $revenue = $wpdb->get_var("
        SELECT COALESCE(SUM(pm.meta_value), 0) FROM {$wpdb->postmeta} pm
        JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = '_vehdoc_order_amount'
        AND p.post_type = 'vehdoc_order' AND p.post_status = 'publish'
    ");
    ?>
    <div class="vehdoc-wp-widget">
        <div class="widget-stat"><strong><?php echo number_format($total_orders); ?></strong> Total Orders</div>
        <div class="widget-stat"><strong><?php echo number_format(intval($pending)); ?></strong> Pending Orders</div>
        <div class="widget-stat"><strong>₦<?php echo number_format(floatval($revenue)); ?></strong> Total Revenue</div>
        <p><a href="<?php echo admin_url('admin.php?page=vehdoc-analytics'); ?>" class="button button-primary">View Full Analytics</a></p>
    </div>
    <?php
}
