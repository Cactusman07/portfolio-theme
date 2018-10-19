<?php
/**
 *  
 * The template for displaying 404 pages (Not Found)
 *
 */
?>

<?php get_header(); ?>
	<div id="page404">
        <div id="wrapper404">
            <div id="title" class="container">
                <h1 class="entry-title"><?php _e( 'Haven\'t found what you\'re looking for?' , 'portfolio' ); ?></h1> <!-- Page Title -->
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-7 col-md-5">
                        <div class="floating-box">    
                            <p>Don't worry - I'm still looking myself!</p>
                            <p>We all get a little lost at times, so here are some quick links to take you where you might need to go:</p>
                            <ul>
                                <li><a href="<?php echo get_home_url(); ?>">Take me home</a></li>
                                <li><a href="<?php echo get_home_url() . '/portfolio'; ?>">View portfolio work</a></li>
                                <li><a href="https://icanhas.cheezburger.com/lolcats/tag/new" target="_blank">Check out the latest cat memes</a></li>
                            </ul>
                            <p>Forget the website, just get in touch with me: <a href="mailto:sam.muir59@gmail.com">sam.muir59@gmail.com</a> or on +64 21 175 9457</p>
                        </div>
                    </div>
                </div>
            </div>         
        </div>

<?php get_footer(); ?>