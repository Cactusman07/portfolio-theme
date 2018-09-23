<?php get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

	<div id="header">
		<img src="wp-content/themes/portfolio/images/Rough Logo_Favicon.svg" id="header-logo">
	</div>
    <canvas class="connecting-dots" id="connecting-dots"></canvas>
    <div class="fixed">
        <div id="logo-container">
            <div id="reset-size">
                <img src="wp-content/themes/portfolio/images/S-sharp-hollow.svg" id="s">
                <p id="am">am</p>
                <div class="line"></div>
                <img src="wp-content/themes/portfolio/images/M-hollowed.svg" id="m">
                <p id="uir"> uir</p>
            </div>
            <div id="title">
                <h1>&#60;<?php the_title(); ?> /&#62;</h1>
            </div>
        </div>
        <div class="section-inView" data-background-color="#FFF9A2"></div>
    </div>
    <div class="black-rollover scrolling">
        <div class="fixed">
            <div id="logo-container">
                <div id="reset-size">
                    <img src="wp-content/themes/portfolio/images/S-sharp-hollow-white.svg" id="s">
                    <p id="am">am</p>
                    <div class="line-2"></div>
                    <img src="wp-content/themes/portfolio/images/M-hollowed-white.svg" id="m">
                    <p id="uir"> uir</p>
                </div>
                <div id="title">
                    <h1>&#60;<?php the_title(); ?> /&#62;</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="scrolling about-container">
        <div id="about">
            <div id="about-description">
                <?php the_content(); ?>
            </div>
        </div>
        <div class="section-inView section2" data-background-color="#CDD8F9"></div>
    </div>
    
    <?php endwhile; wp_reset_query(); ?>

<?php get_footer(); ?>