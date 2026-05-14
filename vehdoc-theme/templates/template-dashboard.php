<?php
/**
 * Template Name: Dashboard
 *
 * @package Vehdoc
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$user     = wp_get_current_user();
$tab      = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'overview';
$stats    = vehdoc_get_user_stats();
$orders   = vehdoc_get_user_orders(20);
$vehicles = vehdoc_get_user_vehicles();
$notifications = get_user_meta($user->ID, 'vehdoc_notifications', true) ?: array();
$unread_count  = vehdoc_get_unread_count();
$expiring      = vehdoc_get_expiring_documents();
$referral_code = vehdoc_generate_referral_code($user->ID);

get_header(); ?>

<section class="dashboard-section">
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar" id="dashSidebar">
            <div class="sidebar-header">
                <div class="user-avatar">
                    <?php echo get_avatar($user->ID, 60); ?>
                </div>
                <div class="user-info">
                    <strong><?php echo esc_html($user->display_name); ?></strong>
                    <span><?php echo esc_html($user->user_email); ?></span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="?tab=overview" class="sidebar-link <?php echo $tab === 'overview' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-grid-2"></i> Overview
                </a>
                <a href="?tab=vehicles" class="sidebar-link <?php echo $tab === 'vehicles' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-car"></i> My Vehicles
                    <span class="sidebar-badge"><?php echo $stats['vehicles']; ?></span>
                </a>
                <a href="?tab=orders" class="sidebar-link <?php echo $tab === 'orders' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-clipboard-list"></i> My Orders
                    <?php if ($stats['active_orders'] > 0) : ?>
                        <span class="sidebar-badge active"><?php echo $stats['active_orders']; ?></span>
                    <?php endif; ?>
                </a>
                <a href="?tab=new-order" class="sidebar-link <?php echo $tab === 'new-order' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-plus-circle"></i> New Order
                </a>
                <a href="?tab=notifications" class="sidebar-link <?php echo $tab === 'notifications' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-bell"></i> Notifications
                    <?php if ($unread_count > 0) : ?>
                        <span class="sidebar-badge active"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="?tab=profile" class="sidebar-link <?php echo $tab === 'profile' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-gear"></i> Profile
                </a>
                <a href="<?php echo wp_logout_url(home_url('/')); ?>" class="sidebar-link sidebar-logout">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="dashboard-content">
            <!-- Mobile Sidebar Toggle -->
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>

            <?php if ($tab === 'overview') : ?>
            <!-- Overview Tab -->
            <div class="dashboard-tab" id="tab-overview">
                <div class="dash-header">
                    <h1>Welcome back, <?php echo esc_html($user->first_name ?: $user->display_name); ?>!</h1>
                    <p>Here's an overview of your vehicle documentation activities.</p>
                </div>

                <!-- Stats Cards -->
                <div class="dash-stats-grid">
                    <div class="dash-stat-card">
                        <div class="stat-icon bg-blue"><i class="fa-solid fa-car"></i></div>
                        <div><span class="stat-value"><?php echo $stats['vehicles']; ?></span><span class="stat-label">Vehicles</span></div>
                    </div>
                    <div class="dash-stat-card">
                        <div class="stat-icon bg-purple"><i class="fa-solid fa-clipboard-list"></i></div>
                        <div><span class="stat-value"><?php echo $stats['total_orders']; ?></span><span class="stat-label">Total Orders</span></div>
                    </div>
                    <div class="dash-stat-card">
                        <div class="stat-icon bg-orange"><i class="fa-solid fa-clock"></i></div>
                        <div><span class="stat-value"><?php echo $stats['active_orders']; ?></span><span class="stat-label">Active</span></div>
                    </div>
                    <div class="dash-stat-card">
                        <div class="stat-icon bg-green"><i class="fa-solid fa-check-circle"></i></div>
                        <div><span class="stat-value"><?php echo $stats['completed']; ?></span><span class="stat-label">Completed</span></div>
                    </div>
                </div>

                <!-- Expiring Documents Alert -->
                <?php if (!empty($expiring)) : ?>
                <div class="dash-alert dash-alert-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Documents Expiring Soon</strong>
                        <p>You have <?php echo count($expiring); ?> document(s) expiring within 30 days.</p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Recent Orders -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h2>Recent Orders</h2>
                        <a href="?tab=orders" class="btn btn-sm btn-outline">View All</a>
                    </div>
                    <?php if (!empty($orders)) : ?>
                    <div class="dash-table-responsive">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Service</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach (array_slice($orders, 0, 5) as $order) : ?>
                                <tr>
                                    <td><strong><?php echo esc_html($order['order_number']); ?></strong></td>
                                    <td><?php echo esc_html($order['service']); ?></td>
                                    <td>₦<?php echo number_format($order['amount']); ?></td>
                                    <td><span class="status-badge status-<?php echo esc_attr($order['status']); ?>"><?php echo esc_html(ucwords(str_replace('_', ' ', $order['status']))); ?></span></td>
                                    <td><?php echo date('M j, Y', strtotime($order['date'])); ?></td>
                                    <td><a href="?tab=tracking&order_id=<?php echo $order['id']; ?>" class="btn btn-sm btn-ghost">Track</a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else : ?>
                    <div class="dash-empty">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <p>No orders yet. <a href="?tab=new-order">Create your first order</a></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php elseif ($tab === 'vehicles') : ?>
            <!-- Vehicles Tab -->
            <div class="dashboard-tab" id="tab-vehicles">
                <div class="dash-header">
                    <h1>My Vehicles</h1>
                    <button class="btn btn-primary" onclick="document.getElementById('addVehicleModal').classList.add('active')">
                        <i class="fa-solid fa-plus"></i> Add Vehicle
                    </button>
                </div>

                <?php if (!empty($vehicles)) : ?>
                <div class="vehicles-grid">
                    <?php foreach ($vehicles as $v) : ?>
                    <div class="vehicle-card">
                        <div class="vehicle-icon"><i class="fa-solid fa-car"></i></div>
                        <div class="vehicle-details">
                            <h3><?php echo esc_html($v['make'] . ' ' . $v['model']); ?></h3>
                            <div class="vehicle-meta">
                                <span><i class="fa-solid fa-calendar"></i> <?php echo esc_html($v['year']); ?></span>
                                <span><i class="fa-solid fa-hashtag"></i> <?php echo esc_html($v['plate_number']); ?></span>
                            </div>
                            <?php if ($v['chassis_number']) : ?>
                            <div class="vehicle-extra">
                                <small>Chassis: <?php echo esc_html($v['chassis_number']); ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="vehicle-actions">
                            <a href="?tab=new-order&vehicle_id=<?php echo $v['id']; ?>" class="btn btn-sm btn-primary">Order Service</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else : ?>
                <div class="dash-empty">
                    <i class="fa-solid fa-car"></i>
                    <h3>No Vehicles Added</h3>
                    <p>Add your first vehicle to start processing documents.</p>
                    <button class="btn btn-primary" onclick="document.getElementById('addVehicleModal').classList.add('active')">
                        <i class="fa-solid fa-plus"></i> Add Vehicle
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- Add Vehicle Modal -->
            <div class="modal" id="addVehicleModal">
                <div class="modal-overlay" onclick="this.parentElement.classList.remove('active')"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Add New Vehicle</h2>
                        <button class="modal-close" onclick="this.closest('.modal').classList.remove('active')">&times;</button>
                    </div>
                    <form id="addVehicleForm" class="modal-form">
                        <div class="form-alert" id="vehicleAlert" style="display:none"></div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>Make *</label>
                                <input type="text" name="make" required class="form-input" placeholder="e.g. Toyota">
                            </div>
                            <div class="form-group">
                                <label>Model *</label>
                                <input type="text" name="model" required class="form-input" placeholder="e.g. Camry">
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>Year</label>
                                <input type="number" name="year" class="form-input" placeholder="e.g. 2022" min="1900" max="2030">
                            </div>
                            <div class="form-group">
                                <label>Plate Number *</label>
                                <input type="text" name="plate_number" required class="form-input" placeholder="e.g. LAG-234-XY">
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>Chassis Number</label>
                                <input type="text" name="chassis_number" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Engine Number</label>
                                <input type="text" name="engine_number" class="form-input">
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text" name="color" class="form-input" placeholder="e.g. Silver">
                            </div>
                            <div class="form-group">
                                <label>Owner Name</label>
                                <input type="text" name="owner_name" class="form-input" value="<?php echo esc_attr($user->display_name); ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Add Vehicle</button>
                    </form>
                </div>
            </div>

            <?php elseif ($tab === 'orders') : ?>
            <!-- Orders Tab -->
            <div class="dashboard-tab" id="tab-orders">
                <div class="dash-header">
                    <h1>My Orders</h1>
                    <a href="?tab=new-order" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Order</a>
                </div>

                <?php if (!empty($orders)) : ?>
                <div class="dash-table-responsive">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Service</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Fast Track</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><strong><?php echo esc_html($order['order_number']); ?></strong></td>
                                <td><?php echo esc_html($order['service']); ?></td>
                                <td>₦<?php echo number_format($order['amount']); ?></td>
                                <td><span class="status-badge status-<?php echo esc_attr($order['status']); ?>"><?php echo esc_html(ucwords(str_replace('_', ' ', $order['status']))); ?></span></td>
                                <td><?php echo $order['fast_track'] ? '<i class="fa-solid fa-bolt text-orange"></i> Yes' : 'No'; ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['date'])); ?></td>
                                <td>
                                    <a href="?tab=tracking&order_id=<?php echo $order['id']; ?>" class="btn btn-sm btn-ghost">Track</a>
                                    <?php if ($order['status'] === 'pending') : ?>
                                        <a href="?tab=upload&order_id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline">Upload Docs</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                <div class="dash-empty">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <h3>No Orders Yet</h3>
                    <p>Create your first order to get started.</p>
                    <a href="?tab=new-order" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Order</a>
                </div>
                <?php endif; ?>
            </div>

            <?php elseif ($tab === 'tracking') : ?>
            <!-- Tracking Tab -->
            <div class="dashboard-tab" id="tab-tracking">
                <?php
                $order_id = intval($_GET['order_id'] ?? 0);
                $tracking = $order_id ? vehdoc_get_tracking_data($order_id) : null;
                ?>
                <?php if ($tracking) : ?>
                <div class="dash-header">
                    <h1>Track Order <?php echo esc_html($tracking['order_number']); ?></h1>
                    <a href="?tab=orders" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Orders</a>
                </div>

                <div class="tracking-container">
                    <div class="tracking-progress">
                        <?php foreach ($tracking['steps'] as $index => $step) : ?>
                        <div class="tracking-step <?php echo $step['completed'] ? 'completed' : ''; ?> <?php echo $step['current'] ? 'current' : ''; ?>">
                            <div class="tracking-circle">
                                <i class="fa-solid <?php echo esc_attr($step['icon']); ?>"></i>
                            </div>
                            <?php if ($index < count($tracking['steps']) - 1) : ?>
                                <div class="tracking-line"></div>
                            <?php endif; ?>
                            <div class="tracking-info">
                                <h4><?php echo esc_html($step['label']); ?></h4>
                                <p><?php echo esc_html($step['description']); ?></p>
                                <?php if ($step['timestamp']) : ?>
                                    <span class="tracking-time"><?php echo date('M j, Y g:i A', strtotime($step['timestamp'])); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else : ?>
                <div class="dash-empty">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <h3>Order Not Found</h3>
                    <p>Please select an order to track from your orders list.</p>
                    <a href="?tab=orders" class="btn btn-primary">View Orders</a>
                </div>
                <?php endif; ?>
            </div>

            <?php elseif ($tab === 'new-order') : ?>
            <!-- New Order Tab -->
            <div class="dashboard-tab" id="tab-new-order">
                <div class="dash-header">
                    <h1>Create New Order</h1>
                </div>

                <?php
                $all_services = get_posts(array('post_type' => 'vehdoc_service', 'posts_per_page' => -1, 'post_status' => 'publish'));
                $selected_service = intval($_GET['service'] ?? 0);
                $selected_vehicle = intval($_GET['vehicle_id'] ?? 0);
                ?>

                <form id="newOrderForm" class="order-form">
                    <div class="form-alert" id="orderAlert" style="display:none"></div>

                    <div class="order-step" id="orderStep1">
                        <h2><span class="step-num">1</span> Select Service</h2>
                        <div class="services-select-grid">
                            <?php foreach ($all_services as $svc) :
                                $price = get_post_meta($svc->ID, '_vehdoc_service_price', true);
                                $ft_price = get_post_meta($svc->ID, '_vehdoc_fast_track_price', true);
                                $time = get_post_meta($svc->ID, '_vehdoc_service_time', true);
                            ?>
                            <label class="service-select-card <?php echo $selected_service == $svc->ID ? 'selected' : ''; ?>">
                                <input type="radio" name="service_id" value="<?php echo $svc->ID; ?>" <?php checked($selected_service, $svc->ID); ?> required>
                                <div class="ssc-content">
                                    <h4><?php echo esc_html($svc->post_title); ?></h4>
                                    <span class="ssc-price">₦<?php echo number_format(floatval($price)); ?></span>
                                    <span class="ssc-time"><i class="fa-regular fa-clock"></i> <?php echo esc_html($time); ?></span>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="order-step" id="orderStep2">
                        <h2><span class="step-num">2</span> Select Vehicle</h2>
                        <?php if (!empty($vehicles)) : ?>
                        <div class="vehicle-select-grid">
                            <?php foreach ($vehicles as $v) : ?>
                            <label class="vehicle-select-card <?php echo $selected_vehicle == $v['id'] ? 'selected' : ''; ?>">
                                <input type="radio" name="vehicle_id" value="<?php echo $v['id']; ?>" <?php checked($selected_vehicle, $v['id']); ?>>
                                <div class="vsc-content">
                                    <i class="fa-solid fa-car"></i>
                                    <strong><?php echo esc_html($v['make'] . ' ' . $v['model']); ?></strong>
                                    <span><?php echo esc_html($v['plate_number']); ?></span>
                                </div>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        <?php else : ?>
                        <p>No vehicles added yet. <button type="button" class="btn btn-sm btn-outline" onclick="document.getElementById('addVehicleModal').classList.add('active')">Add Vehicle</button></p>
                        <?php endif; ?>
                    </div>

                    <div class="order-step" id="orderStep3">
                        <h2><span class="step-num">3</span> Options & Delivery</h2>
                        <div class="form-group">
                            <label class="checkbox-card">
                                <input type="checkbox" name="fast_track" value="1">
                                <div class="cc-content">
                                    <i class="fa-solid fa-bolt"></i>
                                    <div>
                                        <strong>Fast-Track Processing</strong>
                                        <span>Get your documents processed faster</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="checkbox-card">
                                <input type="checkbox" name="delivery" value="1" checked>
                                <div class="cc-content">
                                    <i class="fa-solid fa-truck-fast"></i>
                                    <div>
                                        <strong>Doorstep Delivery</strong>
                                        <span>Receive documents at your doorstep</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="delivery-details" id="deliveryDetails">
                            <div class="form-group">
                                <label>Delivery Address</label>
                                <textarea name="delivery_address" class="form-input" rows="2" placeholder="Enter your delivery address"><?php echo esc_textarea(get_user_meta($user->ID, 'vehdoc_address', true)); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="checkbox-card">
                                    <input type="checkbox" name="express_delivery" value="1">
                                    <div class="cc-content">
                                        <i class="fa-solid fa-rocket"></i>
                                        <div>
                                            <strong>Express Delivery</strong>
                                            <span>Same-day or next-day delivery (Lagos, Abuja, PH)</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary">
                        <h3>Order Summary</h3>
                        <div class="summary-rows" id="orderSummaryRows">
                            <div class="summary-row"><span>Service</span><span id="summaryService">—</span></div>
                            <div class="summary-row"><span>Service Fee</span><span id="summaryPrice">₦0</span></div>
                            <div class="summary-row"><span>Delivery</span><span id="summaryDelivery">₦0</span></div>
                            <div class="summary-row total"><span>Total</span><span id="summaryTotal">₦0</span></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg" id="createOrderBtn">
                        <span class="btn-text">Proceed to Payment</span>
                        <span class="btn-loader" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                </form>
            </div>

            <?php elseif ($tab === 'upload') : ?>
            <!-- Document Upload Tab -->
            <div class="dashboard-tab" id="tab-upload">
                <div class="dash-header">
                    <h1>Upload Documents</h1>
                    <a href="?tab=orders" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back</a>
                </div>
                <div class="upload-zone" id="uploadZone">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="hidden" name="order_id" value="<?php echo intval($_GET['order_id'] ?? 0); ?>">
                        <div class="upload-area" id="uploadArea">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <h3>Drag & Drop or Click to Upload</h3>
                            <p>Accepted formats: PDF, JPG, PNG (Max 10MB each)</p>
                            <input type="file" name="documents[]" id="docFileInput" multiple accept=".pdf,.jpg,.jpeg,.png" class="file-input">
                            <button type="button" class="btn btn-outline" onclick="document.getElementById('docFileInput').click()">Choose Files</button>
                        </div>
                        <div class="upload-preview" id="uploadPreview"></div>
                        <button type="submit" class="btn btn-primary btn-block btn-lg" id="uploadBtn" style="display:none">
                            <span class="btn-text">Upload Documents</span>
                            <span class="btn-loader" style="display:none"><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                    </form>
                </div>
            </div>

            <?php elseif ($tab === 'notifications') : ?>
            <!-- Notifications Tab -->
            <div class="dashboard-tab" id="tab-notifications">
                <div class="dash-header">
                    <h1>Notifications</h1>
                </div>
                <?php if (!empty($notifications)) : ?>
                <div class="notifications-list">
                    <?php foreach ($notifications as $i => $notif) : ?>
                    <div class="notification-item <?php echo empty($notif['read']) ? 'unread' : ''; ?>">
                        <div class="notif-icon">
                            <i class="fa-solid <?php echo $notif['type'] === 'expiry_reminder' ? 'fa-bell' : 'fa-info-circle'; ?>"></i>
                        </div>
                        <div class="notif-content">
                            <p><?php echo esc_html($notif['message']); ?></p>
                            <span class="notif-time"><?php echo esc_html(human_time_diff(strtotime($notif['timestamp'])) . ' ago'); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else : ?>
                <div class="dash-empty">
                    <i class="fa-solid fa-bell"></i>
                    <h3>No Notifications</h3>
                    <p>You're all caught up!</p>
                </div>
                <?php endif; ?>
            </div>

            <?php elseif ($tab === 'profile') : ?>
            <!-- Profile Tab -->
            <div class="dashboard-tab" id="tab-profile">
                <div class="dash-header">
                    <h1>My Profile</h1>
                </div>
                <div class="dash-card">
                    <form id="profileForm" class="profile-form">
                        <div class="form-alert" id="profileAlert" style="display:none"></div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="first_name" value="<?php echo esc_attr($user->first_name); ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" value="<?php echo esc_attr($user->last_name); ?>" class="form-input">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo esc_attr($user->user_email); ?>" class="form-input" disabled>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="tel" name="phone" value="<?php echo esc_attr(get_user_meta($user->ID, 'vehdoc_phone', true)); ?>" class="form-input">
                            </div>
                            <div class="form-group">
                                <label>State</label>
                                <select name="state" class="form-input">
                                    <option value="">Select State</option>
                                    <?php foreach (vehdoc_nigerian_states() as $state) : ?>
                                        <option value="<?php echo esc_attr($state); ?>" <?php selected(get_user_meta($user->ID, 'vehdoc_state', true), $state); ?>><?php echo esc_html($state); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-input" rows="2"><?php echo esc_textarea(get_user_meta($user->ID, 'vehdoc_address', true)); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                    </form>
                </div>

                <?php if (get_option('vehdoc_enable_referral')) : ?>
                <div class="dash-card">
                    <h2>Referral Program</h2>
                    <p>Share your referral code and earn ₦<?php echo number_format(floatval(get_option('vehdoc_referral_bonus', 1000))); ?> for each friend who signs up and places an order.</p>
                    <div class="referral-code-box">
                        <code><?php echo esc_html($referral_code); ?></code>
                        <button type="button" class="btn btn-sm btn-outline" onclick="navigator.clipboard.writeText('<?php echo esc_attr($referral_code); ?>'); this.textContent='Copied!'">Copy Code</button>
                    </div>
                    <p class="referral-link">Share link: <code><?php echo esc_url(home_url('/register/?ref=' . $referral_code)); ?></code></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
