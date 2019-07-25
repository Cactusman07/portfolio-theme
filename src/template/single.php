<?php
/**
 * Single blog post page
 *
 */
?>

<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>

	<div id="blogSingleContainer" style="background-image:url('<?php echo the_post_thumbnail_url(); ?>')">
		<div id="title" class="container">
			<h1 class="entry-title"><?php the_title(); ?></h1> <!-- Page Title -->
		</div>
		<div id="primary" class="content-area">
			<div class="breadcrumb">
				<div class="container">
					<a href="<?php echo get_home_url(); ?>"> Home </a><span class="divider">||</span><a href="<?php echo get_home_url() . '/blog'; ?>"> Blog </a>
				</div>
			</div>
			<main id="main" class="site-main" role="main">
				<div id="content" class="pageContent container">	
					<div class="entry-content-page row">
						<div class="col-xs-12">
							<?php the_content(); ?>
						</div>
					</div>
				</div><!-- #content -->         
			</main><!-- .site-main -->
		</div><!-- .content-area -->

	<?php endwhile; wp_reset_query(); ?>
<?php get_footer(); ?>