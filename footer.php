<footer>
    <style>
        .footer-container {
            background: #000;
            padding: 40px 0;
            color: white;
            text-align: center;
        }
        .social-icons a {
            margin: 0 10px;
            display: inline-block;
        }
        .social-icons img {
            width: 30px;
            height: 30px;
        }
    </style>
    <div class="footer-container">
        <div class="social-icons">
            <a href="https://www.instagram.com" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/instagram.png" alt="Instagram"></a>
            <a href="https://www.youtube.com" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/youtube.png" alt="YouTube"></a>
            <a href="https://www.twitter.com" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/twitter.png" alt="Twitter"></a>
        </div>
        <p>&copy; <?php echo date("Y"); ?> F1 Ferrari. Tutti i diritti riservati.</p>
    </div>
    <?php wp_footer(); ?>
</footer>
</body>
</html>
