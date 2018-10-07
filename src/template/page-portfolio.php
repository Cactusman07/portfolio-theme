<?php
/**
 * Template Name: Portfolio Layout
 *
 */
?>

<?php get_header(); ?>
	<?php while ( have_posts() ) : the_post(); ?>

    <canvas id="matrix"></canvas>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div id="container">
				<div id="content" class="pageContent">

				<h1 class="entry-title"><?php the_title(); ?></h1> <!-- Page Title -->
					<div class="entry-content-page">
						<?php the_content(); ?>
					</div>

				</div><!-- #content -->         
			</div><!-- #container -->
		</main><!-- .site-main -->
	</div><!-- .content-area -->

	<?php endwhile; wp_reset_query(); ?>	
<?php get_footer(); ?>
