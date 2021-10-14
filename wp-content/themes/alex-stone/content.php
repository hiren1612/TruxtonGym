<?php
/**
 * The default template to display the content of the single post, page or attachment
 *
 * Used for index/archive/search.
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

$alex_stone_seo = alex_stone_is_on(alex_stone_get_theme_option('seo_snippets'));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_'.esc_attr(get_post_type()) 
												. ' post_format_'.esc_attr(str_replace('post-format-', '', get_post_format())) 
												);
		if ($alex_stone_seo) {
			?> itemscope="itemscope" 
			   itemprop="mainEntityOfPage" 
			   itemtype="//schema.org/<?php echo esc_attr(alex_stone_get_markup_schema()); ?>"
			   itemid="<?php echo esc_url(get_the_permalink()); ?>"
			   content="<?php the_title_attribute(); ?>"<?php
		}
?>><?php

	do_action('alex_stone_action_before_post_data'); 

	// Structured data snippets
	if ($alex_stone_seo)
		get_template_part('templates/seo');
	if ( is_single() ) {
	?>
	<div class="post_excerpt_colunm post_excerpt_info">
		<?php

			// Post meta
			if (!alex_stone_sc_layouts_showed('postmeta') && alex_stone_is_on(alex_stone_get_theme_option('show_post_meta'))) {
				alex_stone_show_post_meta(apply_filters('alex_stone_filter_post_meta_args', array(
					'components' => 'categories,date,counters',
					'counters' => 'comments',
					'seo' => alex_stone_is_on(alex_stone_get_theme_option('seo_snippets'))
					), 'single', 1)
				);
			}

		?>
	</div><div class="post_excerpt_colunm post_excerpt_content"><?php
	}
	// Featured image
	if ( alex_stone_is_off(alex_stone_get_theme_option('hide_featured_on_single'))
			&& !alex_stone_sc_layouts_showed('featured') 
			&& strpos(get_the_content(), '[trx_widget_banner]')===false) {
		do_action('alex_stone_action_before_post_featured'); 
		alex_stone_show_post_featured();
		do_action('alex_stone_action_after_post_featured'); 
	} else if (has_post_thumbnail()) {
		?><meta itemprop="image" "//schema.org/ImageObject" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>"><?php
	}

	// Title and post meta
	if ( (!alex_stone_sc_layouts_showed('title') || !alex_stone_sc_layouts_showed('postmeta')) && !in_array(get_post_format(), array('link', 'aside', 'status', 'quote')) ) {
		do_action('alex_stone_action_before_post_title'); 
			// Post title
			if (!alex_stone_sc_layouts_showed('title')) {
		?>
		<div class="post_header entry-header">
			<?php
				the_title( '<h3 class="post_title entry-title"'.($alex_stone_seo ? ' itemprop="headline"' : '').'>', '</h3>' );

			?>
		</div><!-- .post_header -->
		<?php
			}
		do_action('alex_stone_action_after_post_title'); 
	}

	do_action('alex_stone_action_before_post_content'); 

	// Post content
	?>
	<div class="post_content entry-content" itemprop="articleBody">
		<?php
		the_content( );

		do_action('alex_stone_action_before_post_pagination'); 

		wp_link_pages( array(
			'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'alex-stone' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'alex-stone' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );

		// Taxonomies and share
		if ( is_single() && !is_attachment() ) {

			do_action('alex_stone_action_before_post_meta'); 

			?><div class="post_meta post_meta_single"><?php
				
				// Post taxonomies
				the_tags( '<span class="post_meta_item post_tags"><span class="post_meta_label">'.esc_html__('Tags:', 'alex-stone').'</span> ', ', ', '</span>' );

				// Share
				if (alex_stone_is_on(alex_stone_get_theme_option('show_share_links'))) {
					alex_stone_show_share_links(array(
							'type' => 'block',
							'caption' => 'Share:',
							'before' => '<span class="post_meta_item post_share">',
							'after' => '</span>'
						));
				}
			?></div><?php

			do_action('alex_stone_action_after_post_meta'); 
		}
		?>
	</div><!-- .entry-content -->
	<?php
	if ( is_single() ) {
	?>	
	</div><?php
	}	

	do_action('alex_stone_action_after_post_content'); 

	// Author bio.
	if ( alex_stone_get_theme_option('show_author_info')==1 && is_single() && !is_attachment() && get_the_author_meta( 'description' ) ) {
		do_action('alex_stone_action_before_post_author'); 
		get_template_part( 'templates/author-bio' );
		do_action('alex_stone_action_after_post_author'); 
	}

	do_action('alex_stone_action_after_post_data'); 
	?>
</article>
