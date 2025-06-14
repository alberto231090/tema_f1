<?php
function f1_ferrari_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'main-menu' => __('Menu Principale', 'f1-ferrari')
    ));
}
add_action('after_setup_theme', 'f1_ferrari_theme_setup');

function f1_ferrari_enqueue_scripts() {
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'f1_ferrari_enqueue_scripts');

function get_f1_standings() {
    $response = wp_remote_get('https://api.openf1.org/v1/standings');
    if (is_wp_error($response)) {
        return 'Errore nel recupero dei dati.';
    }
    $data = wp_remote_retrieve_body($response);
    $standings = json_decode($data, true);
    if (empty($standings)) {
        return 'Nessun dato disponibile.';
    }
    $output = '<table class="f1-standings">';
    $output .= '<tr><th>Posizione</th><th>Pilota</th><th>Team</th><th>Punti</th></tr>';
    foreach ($standings as $position => $driver) {
        $output .= '<tr>';
        $output .= '<td>' . esc_html($position + 1) . '</td>';
        $output .= '<td>' . esc_html($driver['driver_name']) . '</td>';
        $output .= '<td>' . esc_html($driver['team_name']) . '</td>';
        $output .= '<td>' . esc_html($driver['points']) . '</td>';
        $output .= '</tr>';
    }
    $output .= '</table>';
    return $output;
}
add_shortcode('f1_standings', 'get_f1_standings');

add_filter('wp_lazy_loading_enabled', '__return_true');

function ottimizza_immagini($content) {
    $content = preg_replace('/<img(.*?)>/i', '<img loading="lazy" decoding="async"$1>', $content);
    return $content;
}
add_filter('the_content', 'ottimizza_immagini');

function defer_js_scripts($tag, $handle, $src) {
    if (is_admin()) return $tag;
    if (strpos($handle, 'adsbygoogle') !== false) {
        return '<script async src="' . $src . '" type="text/javascript"></script>';
    }
    return $tag;
}
add_filter('script_loader_tag', 'defer_js_scripts', 10, 3);

function add_og_twitter_meta_tags() {
    if (is_single()) {
        global $post;
        $title = f1_get_dynamic_title();
        $desc = get_the_excerpt($post);
        $url = f1_get_dynamic_url();
        $image = get_the_post_thumbnail_url($post, 'large');
        ?>
        <!-- Open Graph -->
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
        <meta property="og:description" content="<?php echo esc_attr($desc); ?>" />
        <meta property="og:url" content="<?php echo esc_url($url); ?>" />
        <meta property="og:image" content="<?php echo esc_url($image); ?>" />
        <meta property="og:site_name" content="Formula uno paddock" />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr($desc); ?>" />
        <meta name="twitter:image" content="<?php echo esc_url($image); ?>" />
        <meta name="twitter:url" content="<?php echo esc_url($url); ?>" />
        <?php
    }
}
add_action('wp_head', 'add_og_twitter_meta_tags');

function f1_ping_motori_di_ricerca($post_ID) {
    if (get_post_status($post_ID) !== 'publish') return;
    $sitemap_url = home_url('/sitemap_index.xml');
    wp_remote_get('https://www.google.com/ping?sitemap=' . urlencode($sitemap_url));
    wp_remote_get('https://www.bing.com/ping?sitemap=' . urlencode($sitemap_url));
    return $post_ID;
}
add_action('publish_post', 'f1_ping_motori_di_ricerca');
add_action('publish_page', 'f1_ping_motori_di_ricerca');

function f1_get_dynamic_title() {
    if (is_singular('post') && get_query_var('page') > 1) {
        global $post;
        $pages = explode('<!--nextpage-->', $post->post_content);
        $page_index = get_query_var('page') - 1;

        if (isset($pages[$page_index])) {
            $content = apply_filters('the_content', $pages[$page_index]);

            if (preg_match('/<h2[^>]*>(.*?)<\/h2>/i', $content, $matches)) {
                return wp_strip_all_tags($matches[1]);
            }
        }
    }
    return get_the_title();
}

function f1_get_dynamic_url() {
    if (is_singular('post') && get_query_var('page') > 1) {
        return trailingslashit(get_permalink()) . get_query_var('page') . '/';
    }
    return get_permalink();
}

add_filter('wpseo_opengraph_title', 'f1_get_dynamic_title');
add_filter('wpseo_twitter_title', 'f1_get_dynamic_title');

function f1_dynamic_paginated_desc($desc) {
    if (is_singular('post') && get_query_var('page') > 1) {
        global $post;
        $content = apply_filters('the_content', $post->post_content);
        if (preg_match('/<p[^>]*>(.*?)<\/p>/i', $content, $matches)) {
            return wp_strip_all_tags($matches[1]);
        }
    }
    return $desc;
}
add_filter('wpseo_opengraph_desc', 'f1_dynamic_paginated_desc');
add_filter('wpseo_twitter_description', 'f1_dynamic_paginated_desc');

add_filter('wpseo_opengraph_url', 'f1_get_dynamic_url');
add_filter('wpseo_twitter_url', 'f1_get_dynamic_url');

add_filter('wpseo_opengraph_image', function($image) {
    if (is_singular('post') && get_query_var('page') > 1) {
        global $post;
        $pages = explode('<!--nextpage-->', $post->post_content);
        $page_index = get_query_var('page') - 1;

        if (isset($pages[$page_index])) {
            $content = apply_filters('the_content', $pages[$page_index]);

            // cerca <img ... src="...">
            if (preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"]/i', $content, $matches)) {
                return esc_url($matches['src']);
            }
        }
    }
    return $image;
});


add_filter('pre_get_document_title', 'f1_get_dynamic_title');

add_filter('wpseo_title', 'f1_get_dynamic_title');
