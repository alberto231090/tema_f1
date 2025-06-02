<?php get_header(); ?>            

<h1 style="text-align:center">Ultime news sul mondo della F1</h1>

<div class="container main-content">
<?php include('grid_posts.php') ?>
	<aside class="sidebar">
        <?php echo do_shortcode('[calendar id="4401"]'); ?>

    </aside>

</div>

<?php get_footer(); ?>
