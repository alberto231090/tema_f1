<?php get_header(); ?>

<div class="single-container">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php
	if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}

	?>
        <article <?php post_class(); ?>>

            <!-- Titolo con struttura semantica -->
            <header class="post-header">
                <h1 class="post-title"><?php the_title(); ?></h1>
            </header>

            <!-- Contenuto -->
            <div class="post-content">
                <?php the_content(); ?>
            


    
</div>
<?php
global $post, $page, $numpages;

// Dividi il contenuto del post in pagine
$pages = explode('<!--nextpage-->', $post->post_content);

// Estrai il primo <h2> da ogni pagina
$titoli = [];
foreach ($pages as $parte) {
    if (preg_match('/<h2[^>]*>(.*?)<\/h2>/i', $parte, $match)) {
        $titoli[] = strip_tags($match[1]);
    } else {
        $titoli[] = 'Pagina ' . (count($titoli) + 1);
    }
}

// Stampa la paginazione
echo '<div class="post-pagination">Sezioni: ';
foreach ($titoli as $index => $titolo) {
    $num = $index + 1;
$url = trailingslashit(get_permalink()) . $num;


    if ($num == $page) {
        echo '<span class="current">' . esc_html($titolo) . '</span> ';
    } else {
        echo '<a href="' . esc_url($url) . '">' . esc_html($titolo) . '</a> ';
    }
}
echo '</div>';
?>

	</article>

    <?php endwhile; endif; ?>


</div>


<?php get_footer(); ?>
