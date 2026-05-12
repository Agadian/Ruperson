<?php
/**
 * Vehdoc 404 Page
 *
 * @package Vehdoc
 */

get_header(); ?>

<section class="page-section error-404">
    <div class="container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2>Page Not Found</h2>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            <div class="error-actions">
                <a href="<?php echo home_url('/'); ?>" class="btn btn-primary"><i class="fa-solid fa-home"></i> Go Home</a>
                <a href="<?php echo home_url('/services/'); ?>" class="btn btn-outline"><i class="fa-solid fa-clipboard"></i> View Services</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
