<?php
/**
 * Template Name: Track Order
 *
 * @package Vehdoc
 */

get_header(); ?>

<section class="page-hero">
    <div class="container">
        <h1 class="page-hero-title">Track Your Order</h1>
        <p class="page-hero-subtitle">Enter your order number to check the status of your document processing</p>
    </div>
</section>

<section class="tracking-page-section">
    <div class="container">
        <?php echo do_shortcode('[vehdoc_tracking]'); ?>
    </div>
</section>

<?php get_footer(); ?>
