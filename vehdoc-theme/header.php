<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Preloader -->
<div class="vehdoc-preloader" id="preloader">
    <div class="preloader-inner">
        <div class="preloader-logo">
            <span class="logo-text">Veh<span class="logo-accent">doc</span></span>
        </div>
        <div class="preloader-spinner"></div>
    </div>
</div>

<!-- Scroll Progress -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- Navigation -->
<header class="vehdoc-header" id="mainHeader">
    <div class="container">
        <nav class="vehdoc-nav">
            <a href="<?php echo home_url('/'); ?>" class="vehdoc-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <span class="logo-text">Veh<span class="logo-accent">doc</span></span>
                <?php endif; ?>
            </a>

            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="<?php echo home_url('/'); ?>" class="nav-link">Home</a></li>
                    <li><a href="<?php echo home_url('/services/'); ?>" class="nav-link">Services</a></li>
                    <li><a href="<?php echo home_url('/about-us/'); ?>" class="nav-link">About</a></li>
                    <li><a href="<?php echo home_url('/#how-it-works'); ?>" class="nav-link">How It Works</a></li>
                    <li><a href="<?php echo home_url('/track-order/'); ?>" class="nav-link">Track Order</a></li>
                    <li><a href="<?php echo home_url('/contact-us/'); ?>" class="nav-link">Contact</a></li>
                </ul>
            </div>

            <div class="nav-actions">
                <!-- Theme Toggle -->
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                    <i class="fa-solid fa-moon" id="themeIcon"></i>
                </button>

                <?php if (is_user_logged_in()) : ?>
                    <div class="nav-user-menu">
                        <a href="<?php echo home_url('/dashboard/'); ?>" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-grid-2"></i> Dashboard
                        </a>
                        <?php
                        $unread = vehdoc_get_unread_count();
                        if ($unread > 0) :
                        ?>
                            <span class="notification-badge"><?php echo $unread; ?></span>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <a href="<?php echo home_url('/login/'); ?>" class="btn btn-outline btn-sm">Log In</a>
                    <a href="<?php echo home_url('/register/'); ?>" class="btn btn-primary btn-sm">Get Started</a>
                <?php endif; ?>

                <button class="hamburger" id="hamburger" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </nav>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileOverlay"></div>

<main class="vehdoc-main">
