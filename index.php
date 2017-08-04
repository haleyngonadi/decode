<?php
/**
 * @package WordPress
 * @subpackage Translated
 * @since Translated 1.0
 */

get_header(); ?>


		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

		

			<?php while ( have_posts() ) : the_post(); ?>

				<h2><?php the_title()?></h2>
				<?php the_content();?>
			<?php endwhile;?>

		
		<?php else :?>
			<p> Sorry, no content was found.</p>
		<?php endif;?>
		

		</main><!-- .site-main -->