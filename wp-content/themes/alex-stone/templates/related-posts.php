<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_link = get_permalink();
$alex_stone_post_format = get_post_format();
$alex_stone_post_format = empty($alex_stone_post_format) ? 'standard' : str_replace('post-format-', '', $alex_stone_post_format);
?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($alex_stone_post_format) ); ?>><?php
	alex_stone_show_post_featured(array(
		'thumb_size' => alex_stone_get_thumb_size( (int) alex_stone_get_theme_option('related_posts') == 1 ? 'huge' : 'big' ),
		'show_no_image' => false,
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. '<div class="post_categories">' . alex_stone_get_post_categories('') . '</div>'
							. '<h6 class="post_title entry-title"><a href="' . esc_url($alex_stone_link) . '">' . wp_kses_post( get_the_title() ) . '</a></h6>'
							. (in_array(get_post_type(), array('post', 'attachment'))
									? '<span class="post_date"><a href="' . esc_url($alex_stone_link) . '">' . alex_stone_get_date() . '</a></span>'
									: '')
						. '</div>'
		)
	);
?></div>