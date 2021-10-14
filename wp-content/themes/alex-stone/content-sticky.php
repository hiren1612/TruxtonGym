<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$alex_stone_post_format = get_post_format();
$alex_stone_post_format = empty($alex_stone_post_format) ? 'standard' : str_replace('post-format-', '', $alex_stone_post_format);
$alex_stone_animation = alex_stone_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($alex_stone_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($alex_stone_post_format) ); ?>
	<?php echo (!alex_stone_is_off($alex_stone_animation) ? ' data-animation="'.esc_attr(alex_stone_get_animation_classes($alex_stone_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	alex_stone_show_post_featured(array(
		'thumb_size' => alex_stone_get_thumb_size($alex_stone_columns==1 ? 'big' : ($alex_stone_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($alex_stone_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			alex_stone_show_post_meta(apply_filters('alex_stone_filter_post_meta_args', array(), 'sticky', $alex_stone_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>