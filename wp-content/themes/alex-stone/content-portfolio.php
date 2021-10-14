<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($alex_stone_columns).' post_format_'.esc_attr($alex_stone_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!alex_stone_is_off($alex_stone_animation) ? ' data-animation="'.esc_attr(alex_stone_get_animation_classes($alex_stone_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$alex_stone_image_hover = alex_stone_get_theme_option('image_hover');
	// Featured image
	alex_stone_show_post_featured(array(
		'thumb_size' => alex_stone_get_thumb_size(strpos(alex_stone_get_theme_option('body_style'), 'full')!==false || $alex_stone_columns < 3 ? 'masonry-big' : 'masonry'),
		'show_no_image' => true,
		'class' => $alex_stone_image_hover == 'dots' ? 'hover_with_info' : '',
		'post_info' => $alex_stone_image_hover == 'dots' ? '<div class="post_info">'.esc_html(get_the_title()).'</div>' : ''
	));
	?>
</article>