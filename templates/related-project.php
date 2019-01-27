<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$portfolios = Filterable_Portfolio_Utils::get_related_portfolios();

if ( count( $portfolios ) < 1 ) {
	return;
}

$option      = get_option( 'filterable_portfolio' );
$theme       = ! empty( $option['portfolio_theme'] ) ? $option['portfolio_theme'] : '';
$theme       = in_array( $theme, array( 'one', 'two' ) ) ? $theme : 'one';
$items_class = 'grids portfolio-items related-projects';
$items_class .= ' fp-theme-' . $theme;
$title       = esc_html__( 'Related Projects', 'filterable-portfolio' );
if ( ! empty( $options['related_projects_text'] ) ) {
	$title = esc_html( $options['related_projects_text'] );
}
?>
<h4 class="related-projects-title"><?php echo $title; ?></h4>
<div class="<?php echo $items_class; ?>">
	<?php
	$temp_post = $GLOBALS['post'];
	foreach ( $portfolios as $portfolio ) {
		setup_postdata( $portfolio );
		$GLOBALS['post'] = $portfolio;
		do_action( 'filterable_portfolio_loop_post' );
	}
	wp_reset_postdata();
	$GLOBALS['post'] = $temp_post;
	?>
</div>