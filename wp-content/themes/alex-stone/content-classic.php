<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_blog_style = explode('_', alex_stone_get_theme_option('blog_style'));
$alex_stone_columns = empty($alex_stone_blog_style[1]) ? 2 : max(2, $alex_stone_blog_style[1]);
$alex_stone_expanded = !alex_stone_sidebar_present() && alex_stone_is_on(alex_stone_get_theme_option('expand_content'));
$alex_stone_post_format = get_post_format();
$alex_stone_post_format = empty($alex_stone_post_format) ? 'standard' : str_replace('post-format-', '', $alex_stone_post_format);
$alex_stone_animation = alex_stone_get_theme_option('blog_animation');
$alex_stone_components = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('meta_parts')) 
							? 'categories,date,counters'.($alex_stone_columns < 3 ? ',edit' : '')
							: alex_stone_array_get_keys_by_value(alex_stone_get_theme_option('meta_parts'));
$alex_stone_counters = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('counters')) 
							? 'comments'
							: alex_stone_array_get_keys_by_value(alex_stone_get_theme_option('counters'));

?><div class="<?php echo trim($alex_stone_blog_style[0]) == 'classic' ? 'column' : 'masonry_item masonry_item'; ?>-1_<?php echo esc_attr($alex_stone_columns); ?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($alex_stone_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($alex_stone_columns)
					. ' post_layout_'.esc_attr($alex_stone_blog_style[0]) 
					. ' post_layout_'.esc_attr($alex_stone_blog_style[0]).'_'.esc_attr($alex_stone_columns)
					); ?>
	<?php echo (!alex_stone_is_off($alex_stone_animation) ? ' data-animation="'.esc_attr(alex_stone_get_animation_classes($alex_stone_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	alex_stone_show_post_featured( array( 'thumb_size' => alex_stone_get_thumb_size($alex_stone_blog_style[0] == 'classic'
													? (strpos(alex_stone_get_theme_option('body_style'), 'full')!==false 
															? ( $alex_stone_columns > 2 ? 'big' : 'huge' )
															: (	$alex_stone_columns > 2
																? ($alex_stone_expanded ? 'med' : 'small')
																: ($alex_stone_expanded ? 'big' : 'med')
																)
														)
													: (strpos(alex_stone_get_theme_option('body_style'), 'full')!==false 
															? ( $alex_stone_columns > 2 ? 'masonry-big' : 'full' )
															: (	$alex_stone_columns <= 2 && $alex_stone_expanded ? 'masonry-big' : 'masonry')
														)
								) ) );

	if ( !in_array($alex_stone_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php 
			do_action('alex_stone_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('alex_stone_action_before_post_meta'); 

			// Post meta
			if (!empty($alex_stone_components))
				alex_stone_show_post_meta(apply_filters('alex_stone_filter_post_meta_args', array(
					'components' => $alex_stone_components,
					'counters' => $alex_stone_counters,
					'seo' => false
					), $alex_stone_blog_style[0], $alex_stone_columns)
				);

			do_action('alex_stone_action_after_post_meta'); 
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$alex_stone_show_learn_more = false;
			if (has_excerpt()) {
				the_excerpt();
			} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
				the_content( '' );
			} else if (in_array($alex_stone_post_format, array('link', 'aside', 'status'))) {
				the_content();
			} else if ($alex_stone_post_format == 'quote') {
				if (($quote = alex_stone_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
					alex_stone_show_layout(wpautop($quote));
				else
					the_excerpt();
			} else if (substr(get_the_content(), 0, 1)!='[') {
				the_excerpt();
			}
			?>
		</div>
		<?php
		// Post meta
		if (in_array($alex_stone_post_format, array('link', 'aside', 'status', 'quote'))) {
			if (!empty($alex_stone_components))
				alex_stone_show_post_meta(apply_filters('alex_stone_filter_post_meta_args', array(
					'components' => $alex_stone_components,
					'counters' => $alex_stone_counters
					), $alex_stone_blog_style[0], $alex_stone_columns)
				);
		}
		// More button
		if ( $alex_stone_show_learn_more ) {
			?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'alex-stone'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>