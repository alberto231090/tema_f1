<?php get_header(); ?>

<div class="container grid-container">
    <div class="posts-grid">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="post-item">
            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
            <?php endif; ?>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div><?php the_content(); ?></div>
        </div>
        <?php endwhile; endif; ?>
    </div>

    <div class="sidebar">
        <h2>Prossimi GP F1</h2>
        <ul>
            <li>26 Maggio - Monaco</li>
            <li>9 Giugno - Canada</li>
            <li>23 Giugno - Spagna</li>
            <li>30 Giugno - Austria</li>
        </ul>
    </div>
</div>
<?php get_footer(); ?>
