<?php
/**
 * The template to display the featured image in the single post
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

if ( get_query_var('alex_stone_header_image')=='' && is_singular() && has_post_thumbnail() && in_array(get_post_type(), array('post', 'page')) )  {
	$alex_stone_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
	if (!empty($alex_stone_src[0])) {
		alex_stone_sc_layouts_showed('featured', true);
		?><div class="sc_layouts_featured with_image <?php echo esc_attr(alex_stone_add_inline_css_class('background-image:url('.esc_url($alex_stone_src[0]).');')); ?>"></div><?php
	}
}
?>