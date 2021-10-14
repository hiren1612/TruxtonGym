<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_blog_style = explode('_', alex_stone_get_theme_option('blog_style'));
$alex_stone_columns = empty($alex_stone_blog_style[1]) ? 2 : max(2, $alex_stone_blog_style[1]);
$alex_stone_post_format = get_post_format();
$alex_stone_post_format = empty($alex_stone_post_format) ? 'standard' : str_replace('post-format-', '', $alex_stone_post_format);
$alex_stone_animation = alex_stone_get_theme_option('blog_animation');
$alex_stone_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($alex_stone_columns).' post_format_'.esc_attr($alex_stone_post_format) ); ?>
	<?php echo (!alex_stone_is_off($alex_stone_animation) ? ' data-animation="'.esc_attr(alex_stone_get_animation_classes($alex_stone_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($alex_stone_image[1]) && !empty($alex_stone_image[2])) echo intval($alex_stone_image[1]) .'x' . intval($alex_stone_image[2]); ?>"
	data-src="<?php if (!empty($alex_stone_image[0])) echo esc_url($alex_stone_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$alex_stone_image_hover = 'icon';
	if (in_array($alex_stone_image_hover, array('icons', 'zoom'))) $alex_stone_image_hover = 'dots';
	$alex_stone_components = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('meta_parts')) 
								? 'categories,date,counters,share'
								: alex_stone_array_get_keys_by_value(alex_stone_get_theme_option('meta_parts'));
	$alex_stone_counters = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('counters')) 
								? 'comments'
								: alex_stone_array_get_keys_by_value(alex_stone_get_theme_option('counters'));
	alex_stone_show_post_featured(array(
		'hover' => $alex_stone_image_hover,
		'thumb_size' => alex_stone_get_thumb_size( strpos(alex_stone_get_theme_option('body_style'), 'full')!==false || $alex_stone_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($alex_stone_components)
										? alex_stone_show_post_meta(apply_filters('alex_stone_filter_post_meta_args', array(
											'components' => $alex_stone_components,
											'counters' => $alex_stone_counters,
											'seo' => false,
											'echo' => false
											), $alex_stone_blog_style[0], $alex_stone_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'alex-stone') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>