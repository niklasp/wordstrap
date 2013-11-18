<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package wordstrap
 */
?>

	</div><!-- #content -->
	</div>
    
    <footer id="footer" class="content-info" role="contentinfo">
    <div class="container">
		<div class="row">
		      <?php dynamic_sidebar('sidebar-footer'); ?>
			  <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
		</div>
    </div>
	</footer>

<?php wp_footer(); ?>

</body>
</html>