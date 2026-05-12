<?php
/**
 * Vehdoc Page Template
 *
 * @package Vehdoc
 */

get_header(); ?>

<section class="page-section page-default">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <h1 class="page-title"><?php the_title(); ?></h1>
            <div class="page-content"><?php the_content(); ?></div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
