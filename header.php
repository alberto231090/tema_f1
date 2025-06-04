<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">


  <meta name="keywords" content="Formula 1, Ferrari, F1, motorsport, Leclerc, Hamilton">
  
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header" >
 
    <div class="header-title">
<h2>
  <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
    <?php bloginfo('name'); ?>
  </a>
</h2>
<p><?php bloginfo('description'); ?></p>
    </div>
    </header>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preload" href="https://fonts.gstatic.com" as="font" type="font/woff2" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

<script>
  window.addEventListener('DOMContentLoaded', function () {
    let adsLoaded = false;
    function loadAdsense() {
      if (adsLoaded) return;
      adsLoaded = true;

      const script = document.createElement('script');
      script.src = "https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1892473664508324";
      script.setAttribute("crossorigin", "anonymous");
      script.async = true;
      document.head.appendChild(script);
    }

    window.addEventListener('scroll', loadAdsense, { once: true });
    window.addEventListener('click', loadAdsense, { once: true });
  });
</script>
<?php if (is_single()): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php the_permalink(); ?>"
  },
  "headline": "<?php echo esc_js(get_the_title()); ?>",
  "description": "<?php echo esc_js(get_the_excerpt()); ?>",
  "image": [
    "<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>"
  ],
  "datePublished": "<?php echo get_the_date('c'); ?>",
  "dateModified": "<?php echo get_the_modified_date('c'); ?>",
  "author": {
    "@type": "Person",
    "name": "<?php the_author(); ?>"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Formula Uno Paddock",
    "logo": {
      "@type": "ImageObject",
      "url": "https://www.formulapaddock.it/wp-content/uploads/2025/03/logo.png",
      "width": 600,
      "height": 60
    }
  }
}
</script>
<?php endif; ?>
	<?php
$image_id = get_post_thumbnail_id($post);
$image_data = wp_get_attachment_image_src($image_id, 'large');
$image = $image_data[0];

// Se termina in .webp, prova a forzare jpg
if (str_ends_with($image, '.webp')) {
    $jpg_version = preg_replace('/\.webp$/', '.jpg', $image);
    if (@getimagesize($jpg_version)) {
        $image = $jpg_version;
    }
}
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-KBHJR9HMLS"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-KBHJR9HMLS');
</script>
