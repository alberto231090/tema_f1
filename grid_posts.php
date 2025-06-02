<div class="posts-grid">

<?php
$all_posts = get_posts([
    'numberposts' => 10,
    'post_type' => 'post',
    'post_status' => 'publish',
]);

foreach ($all_posts as $post) :
    setup_postdata($post);
    $content = apply_filters('the_content', $post->post_content);
    $pages = explode('<!--nextpage-->', $content);
    $permalink = get_permalink($post);
    $title = get_the_title($post);
    $original_excerpt = get_the_excerpt($post);

    foreach ($pages as $index => $page_content) :
        $page_number = $index + 1;
        $page_url = $page_number === 1 ? $permalink : trailingslashit($permalink) . $page_number;
        $excerpt = wp_trim_words(strip_tags($page_content), 50);

        if (preg_match('/<h2[^>]*>(.*?)<\/h2>/', $page_content, $matches)) {
            $subheading = strip_tags($matches[1]);
        } else {
            $subheading = $title . ' – Pagina ' . $page_number;
        }

        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $page_content, $img_matches)) {
            $img_src = $img_matches[1];
        } elseif (has_post_thumbnail($post)) {
            $img_src = get_the_post_thumbnail_url($post, 'large');
        } else {
            $img_src = 'https://via.placeholder.com/200x130';
        }
?>
    <div class="post-item">
        <a href="<?php echo $page_url; ?>" class="post-image">
            <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($subheading); ?>" loading="lazy" decoding="async">
        </a>
        
            <h2><a href="<?php echo $page_url; ?>"><?php echo esc_html($subheading); ?></a></h2>
            <?php if (!empty($original_excerpt)) : ?>
                
            <?php endif; ?>
            <p><?php echo esc_html($excerpt); ?></p>
        </div>
    
<?php
    endforeach;
endforeach;
wp_reset_postdata();
?>

</div>
