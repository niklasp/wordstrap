<?php
/*
Template Name: sidebar-left
*/

get_header(); ?>

	<div class="row">

		<div class="col-xs-12 col-md-8 col-md-push-4">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>

			<?php endwhile; // end of the loop. ?>
		</main><!-- #main -->
		</div>
		<aside class="col-xs-12 col-md-4 col-md-pull-8" role="sidebar">
			<?php get_sidebar(); ?>		
		</aside>

	</div>


<?php get_footer(); ?>