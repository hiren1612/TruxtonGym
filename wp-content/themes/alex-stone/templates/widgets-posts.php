<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_post_id    = get_the_ID();
$alex_stone_post_date  = alex_stone_get_date();
$alex_stone_post_title = get_the_title();
$alex_stone_post_link  = get_permalink();
$alex_stone_post_author_id   = get_the_author_meta('ID');
$alex_stone_post_author_name = get_the_author_meta('display_name');
$alex_stone_post_author_url  = get_author_posts_url($alex_stone_post_author_id, '');

$alex_stone_args = get_query_var('alex_stone_args_widgets_posts');
$alex_stone_show_date = isset($alex_stone_args['show_date']) ? (int) $alex_stone_args['show_date'] : 1;
$alex_stone_show_image = isset($alex_stone_args['show_image']) ? (int) $alex_stone_args['show_image'] : 1;
$alex_stone_show_author = isset($alex_stone_args['show_author']) ? (int) $alex_stone_args['show_author'] : 1;
$alex_stone_show_counters = isset($alex_stone_args['show_counters']) ? (int) $alex_stone_args['show_counters'] : 1;
$alex_stone_show_categories = isset($alex_stone_args['show_categories']) ? (int) $alex_stone_args['show_categories'] : 1;

$alex_stone_output = alex_stone_storage_get('alex_stone_output_widgets_posts');

$alex_stone_post_counters_output = '';
if ( $alex_stone_show_counters ) {
	$alex_stone_post_counters_output = '<span class="post_info_item post_info_counters">'
								. alex_stone_get_post_counters('comments')
							. '</span>';
}


$alex_stone_output .= '<article class="post_item with_thumb">';

if ($alex_stone_show_image) {
	$alex_stone_post_thumb = get_the_post_thumbnail($alex_stone_post_id, alex_stone_get_thumb_size('tiny'), array(
		'alt' => the_title_attribute( array( 'echo' => false ) )
	));
	if ($alex_stone_post_thumb) $alex_stone_output .= '<div class="post_thumb">' . ($alex_stone_post_link ? '<a href="' . esc_url($alex_stone_post_link) . '">' : '') . ($alex_stone_post_thumb) . ($alex_stone_post_link ? '</a>' : '') . '</div>';
}

$alex_stone_output .= '<div class="post_content">'
			. ($alex_stone_show_categories 
					? '<div class="post_categories">'
						. alex_stone_get_post_categories()
						. $alex_stone_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($alex_stone_post_link ? '<a href="' . esc_url($alex_stone_post_link) . '">' : '') . ($alex_stone_post_title) . ($alex_stone_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('alex_stone_filter_get_post_info', 
								'<div class="post_info">'
									. ($alex_stone_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($alex_stone_post_link ? '<a href="' . esc_url($alex_stone_post_link) . '" class="post_info_date">' : '') 
											. esc_html($alex_stone_post_date) 
											. ($alex_stone_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($alex_stone_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'alex-stone') . ' ' 
											. ($alex_stone_post_link ? '<a href="' . esc_url($alex_stone_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($alex_stone_post_author_name) 
											. ($alex_stone_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$alex_stone_show_categories && $alex_stone_post_counters_output
										? $alex_stone_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
alex_stone_storage_set('alex_stone_output_widgets_posts', $alex_stone_output);
?>