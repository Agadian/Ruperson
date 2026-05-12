<?php
/**
 * Vehdoc Single Post Template
 *
 * @package Vehdoc
 */

get_header(); ?>

<section class="page-section single-post">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article class="post-entry">
                <h1><?php the_title(); ?></h1>
                <div class="post-meta">
                    <span><i class="fa-regular fa-calendar"></i> <?php the_date(); ?></span>
                    <span><i class="fa-regular fa-user"></i> <?php the_author(); ?></span>
                </div>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail"><?php the_post_thumbnail('large'); ?></div>
                <?php endif; ?>
                <div class="post-content"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
