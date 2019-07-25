<?php
/**
 * Template Name: Blog Layout
 *
 */
?>

<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<canvas id="world"></canvas>	
	<div id="blogContainer">
		<div id="title" class="container">
			<div class="rect" id="rect1"></div>
			<div class="rect" id="rect2"></div>
			<div class="rect" id="rect3"></div>
			<h1 class="entry-title"><?php the_title(); ?></h1> <!-- Page Title -->
		</div>
		<div id="primary" class="content-area">
			<div class="breadcrumb">
				<div class="container">
					<a href="<?php echo get_home_url(); ?>"> Home </a>
				</div>
			</div>
			<main id="main" class="site-main" role="main">
				<div id="content" class="pageContent container">
					<div class="entry-content-page row">
						<div class="col-xs-12">
							<?php the_content(); ?>
						</div>
					</div>
					<div class="row">
						<?php 
							$args = array( 
								'post_type' 			=> 'post'
							);
							$loop = new WP_Query( $args );
							while ( $loop->have_posts() ) : $loop->the_post(); 
						?>
							
						<a href="<?php the_permalink(); ?>" class="blogItemLink">
							<div class="blog-item col-lg-3 col-md-4 col-sm-6 col-xs-12">		
								<div class="item-background" style="background-image:url('<?php the_post_thumbnail_url(); ?>');'">
									<div class="blogItemTitle">
										<h3><?php the_title(); ?></h3>
									</div>
									<div class="blogItemExcerpt">
										<?php the_excerpt(); ?>
									</div>
									<div class="blogItemOverlay"></div>
								</div>
							</div>
						</a>

						<?php endwhile; ?>

					</div>
				</div><!-- #content -->         
			</main><!-- .site-main -->
		</div><!-- .content-area -->

	<?php endwhile; wp_reset_query(); ?>
<?php get_footer(); ?>