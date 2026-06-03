<?php
/**
 * Header template — site head, opening body markup, ambient cursor + mesh layer.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	<a class="screen-reader-text skip-link" href="#cmp-main"><?php esc_html_e( 'Skip to content', 'cactusman-portfolio' ); ?></a>

	<?php if ( is_front_page() ) : ?>
		<div class="cursor-dot" id="cursor" aria-hidden="true"></div>
	<?php endif; ?>
