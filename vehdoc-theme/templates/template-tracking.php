<?php
/**
 * Template Name: Track Order
 *
 * @package Vehdoc
 */

get_header();

$order_number = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '';
$tracking     = null;

if ($order_number && is_user_logged_in()) {
    $tracking = vehdoc_get_tracking_by_number($order_number);
}
?>

<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge"><i class="fa-solid fa-location-dot"></i> Order Tracking</span>
        <h1 class="page-hero-title">Track Your Order</h1>
        <p class="page-hero-subtitle">Enter your order number to check the real-time status of your document processing</p>
    </div>
</section>

<section class="tracking-page-section">
    <div class="container">
        <!-- Search Form -->
        <div class="tracking-search-card animate-fade-up">
            <form class="tracking-search-form" method="get" action="">
                <div class="tracking-search-group">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="order" placeholder="Enter your order number (e.g. VHD-000001)" value="<?php echo esc_attr($order_number); ?>" class="form-input" required>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-search"></i> Track
                    </button>
                </div>
            </form>
            <?php if (!is_user_logged_in()) : ?>
            <p class="tracking-login-note"><i class="fa-solid fa-lock"></i> Please <a href="<?php echo home_url('/login/'); ?>">log in</a> to track your orders.</p>
            <?php endif; ?>
        </div>

        <?php if ($tracking) : ?>
        <!-- Tracking Results -->
        <div class="tracking-results animate-fade-up">
            <div class="tracking-result-header">
                <div>
                    <h2>Order <?php echo esc_html($tracking['order_number']); ?></h2>
                    <span class="tracking-service"><?php echo esc_html($tracking['service']); ?></span>
                </div>
                <span class="status-badge status-<?php echo esc_attr($tracking['status']); ?>">
                    <?php echo esc_html(ucwords(str_replace('_', ' ', $tracking['status']))); ?>
                </span>
            </div>

            <div class="tracking-progress-horizontal">
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

        <?php elseif ($order_number && is_user_logged_in()) : ?>
        <div class="tracking-not-found animate-fade-up">
            <i class="fa-solid fa-circle-exclamation"></i>
            <h3>Order Not Found</h3>
            <p>We couldn't find an order matching "<strong><?php echo esc_html($order_number); ?></strong>". Please check the order number and try again, or view your orders from the <a href="<?php echo home_url('/dashboard/?tab=orders'); ?>">dashboard</a>.</p>
        </div>
        <?php endif; ?>

        <!-- Tracking Info Cards -->
        <div class="tracking-info-grid animate-fade-up">
            <div class="tracking-info-card">
                <i class="fa-solid fa-clock"></i>
                <h4>Processing Status</h4>
                <p>Your order goes through 6 stages: Pending, Documents Received, Processing, Approved, Ready for Delivery, and Delivered.</p>
            </div>
            <div class="tracking-info-card">
                <i class="fa-solid fa-bell"></i>
                <h4>Notifications</h4>
                <p>You'll receive email and SMS updates at each stage. Check your notifications in the dashboard for detailed updates.</p>
            </div>
            <div class="tracking-info-card">
                <i class="fa-solid fa-headset"></i>
                <h4>Need Help?</h4>
                <p>If your order seems stuck or you have questions, <a href="<?php echo home_url('/contact-us/'); ?>">contact our support team</a> for immediate assistance.</p>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
