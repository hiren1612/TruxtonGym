<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_post_format = get_post_format();
$alex_stone_post_format = empty($alex_stone_post_format) ? 'standard' : str_replace('post-format-', '', $alex_stone_post_format);
$alex_stone_animation = alex_stone_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($alex_stone_post_format) ); ?>
	<?php echo (!alex_stone_is_off($alex_stone_animation) ? ' data-animation="'.esc_attr(alex_stone_get_animation_classes($alex_stone_animation)).'"' : ''); ?>
	><?php if (!is_sticky()) { ?><div class="post_excerpt_colunm post_excerpt_info">
		<?php
			//post meta
			do_action('alex_stone_action_before_post_meta'); 

			// Post meta
			$alex_stone_components = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('meta_parts')) 
										? 'categories,date,counters,edit'
										: alex_stone_array_get_keys_by_value(alex_stone_get_theme_option('meta_parts'));
			$alex_stone_counters = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('counters')) 
										? 'views,likes,comments'
										: alex_stone_array_get_keys_by_value(alex_stone_get_theme_option('counters'));

			if (!empty($alex_stone_components))
				alex_stone_show_post_meta(apply_filters('alex_stone_filter_post_meta_args', array(
					'components' => $alex_stone_components,
					'counters' => $alex_stone_counters,
					'seo' => false
					), 'excerpt', 1)
				);
		?>
	</div><?php } ?><div class="post_excerpt_colunm post_excerpt_content"><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"><?php esc_html_e('sticky post', 'alex-stone'); ?></span><?php
	}

	// Featured image
	alex_stone_show_post_featured(array( 'thumb_size' => alex_stone_get_thumb_size( strpos(alex_stone_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

	// Title and post meta
	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php
			do_action('alex_stone_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

	if ( is_sticky() && !is_paged() ) {
			//post meta
			do_action('alex_stone_action_before_post_meta'); 

			// Post meta
			$alex_stone_components = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('meta_parts')) 
										? 'date,counters'
										: 'date,counters';
			$alex_stone_counters = alex_stone_is_inherit(alex_stone_get_theme_option_from_meta('counters')) 
										? 'comments'
										: 'comments';

			if (!empty($alex_stone_components))
				alex_stone_show_post_meta(apply_filters('alex_stone_filter_post_meta_args', array(
					'components' => $alex_stone_components,
					'counters' => $alex_stone_counters,
					'seo' => false
					), 'excerpt', 1)
				);
	}

			?>
		</div><!-- .post_header --><?php
	}
	
	// Post content
	?><div class="post_content entry-content"><?php
		if (alex_stone_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'alex-stone' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'alex-stone' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$alex_stone_show_learn_more = !in_array($alex_stone_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
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
			?></div><?php
			// More button
			if ( $alex_stone_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'alex-stone'); ?></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
</div></article>