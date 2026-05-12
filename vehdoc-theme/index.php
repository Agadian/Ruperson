<?php
/**
 * Vehdoc Index Template
 *
 * @package Vehdoc
 */

get_header(); ?>

<section class="page-section">
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="post-entry">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="post-content"><?php the_excerpt(); ?></div>
            </article>
        <?php endwhile; endif; ?>
    </div>
</section>

<?php get_footer(); ?>
