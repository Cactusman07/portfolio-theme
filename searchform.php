<?php
/**
 * Search form.
 *
 * @package CactusmanPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="cmp-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="cmp-search-input"><?php esc_html_e( 'Search', 'cactusman-portfolio' ); ?></label>
	<input id="cmp-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'search the site…', 'cactusman-portfolio' ); ?>">
	<button type="submit" class="btn btn-primary">→</button>
</form>
