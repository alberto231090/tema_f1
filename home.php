<?php get_header(); ?>
            
<?php include('countdown.php'); ?>

<h1 style="text-align:center"> 
<?php bloginfo('description'); ?>
</h1>

<div class="container main-content">
    <div class="posts-grid">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="post-item">

            <?php if ( has_post_thumbnail() ) : ?>
                <a href="<?php the_permalink(); ?>">
                    <?php 
                    // ⚠️ Ottimizzazione immagine
                    the_post_thumbnail('medium', [
                        'loading' => 'lazy',
                        'decoding' => 'async',
                        'alt' => get_the_title()
                    ]); 
                    ?>
                </a>
            <?php endif; ?>

            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php the_excerpt(); ?></p>

        </div>
        <?php endwhile; endif; ?>

    </div>

    <aside class="sidebar">
        <?php echo do_shortcode('[calendar id="16622"]'); ?>
        <?php include('classifica.php'); ?>
    </aside>

</div>

<?php get_footer(); ?>
