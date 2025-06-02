<?php get_header(); ?>

<div class="single-container">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb('<p id="breadcrumbs">','</p>');
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
            global $post, $page;

            // Sicurezza: inizializza $page se non definito
            if ( !isset($page) || !$page ) {
                $page = 1;
            }

            // Assicurati che il contenuto esista
            if ( isset($post->post_content) ) {
                // Dividi il contenuto del post in pagine
                $pages = explode('<!--nextpage-->', $post->post_content);

                // Estrai <h2> e <img> da ogni sezione
                $sezioni = [];

                foreach ($pages as $parte) {
                    // Titolo
                    if (preg_match('/<h2[^>]*>(.*?)<\/h2>/i', $parte, $match)) {
                        $titolo = strip_tags($match[1]);
                    } else {
                        $titolo = 'Pagina ' . (count($sezioni) + 1);
                    }

                    // Immagine
                    if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $parte, $img_match)) {
                        $img_src = $img_match[1];
                    } else {
                        $img_src = null;
                    }

                    $sezioni[] = ['titolo' => $titolo, 'img' => $img_src];
                }

                // Stampa la paginazione
                echo '<div class="post-pagination">Sezioni: ';
                foreach ($sezioni as $index => $sezione) {
                    $num = $index + 1;
                    $url = trailingslashit(get_permalink()) . $num;
                    $titolo = esc_html($sezione['titolo']);
                    $img_tag = $sezione['img'] ? '<img src="' . esc_url($sezione['img']) . '" alt="' . $titolo . '">' : '';

                    if ($num == $page) {
                        echo '<span class="current">' . $img_tag . $titolo . '</span> ';
                    } else {
                        echo '<a href="' . esc_url($url) . '">' . $img_tag . $titolo . '</a> ';
                    }
                }
                echo '</div>';
            }
            ?>

        </article>

    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
