<?php
/**
 * Template part for displaying portfolio content
 *
 * @package Filterable_Portfolio
 * @since 1.3.3
 */

if ( ! defined( 'WPINC' ) ) {
	die; // If this file is called directly, abort.
}

$options     = Filterable_Portfolio_Helper::get_options();
$button_text = esc_html( $options['details_button_text'] );

$item_class   = array( 'filterable-portfolio-item', 'portfolio-item', 'grid' );
$item_class[] = esc_attr( $options['columns_phone'] );
$item_class[] = esc_attr( $options['columns_tablet'] );
$item_class[] = esc_attr( $options['columns_desktop'] );
$item_class[] = esc_attr( $options['columns'] );

$categories_slug = array();
$categories      = get_the_terms( get_the_ID(), 'portfolio_cat' );
if ( $categories && ! is_wp_error( $categories ) ) {
	$categories_slug = wp_list_pluck( $categories, 'slug' );
	$item_class      = array_merge( $item_class, $categories_slug );
}

$image_size        = esc_attr( $options['image_size'] );
$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
$project_url      = get_post_meta( $id, '_project_url', true );
?>
<div id="id-<?php echo get_the_ID(); ?>" class="<?php echo implode( ' ', $item_class ) ?>">
    <figure class="filterable-portfolio-item__content">
        <a href="<?php echo esc_url( $project_url ); ?>" rel="bookmark" class="filterable-portfolio-item__media">
			<?php echo wp_get_attachment_image( $post_thumbnail_id, $image_size ) ?>
        </a>
        <figcaption class="filterable-portfolio-item__supporting-text">
            <h4 class="filterable-portfolio-item__title"><?php echo get_the_title(); ?></h4>
            <a href="<?php echo esc_url( $project_url ); ?>" rel="bookmark"
               class="button filterable-portfolio-item__action"><?php echo $button_text; ?></a>
        </figcaption>
    </figure>
</div>
