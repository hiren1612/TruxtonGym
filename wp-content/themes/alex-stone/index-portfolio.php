<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

alex_stone_storage_set('blog_archive', true);

// Load scripts for both 'Gallery' and 'Portfolio' layouts!
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'masonry' );
wp_enqueue_script( 'classie', alex_stone_get_file_url('js/theme.gallery/classie.min.js'), array(), null, true );
wp_enqueue_script( 'alex-stone-gallery-script', alex_stone_get_file_url('js/theme.gallery/theme.gallery.js'), array(), null, true );

get_header(); 

if (have_posts()) {

	echo get_query_var('blog_archive_start');

	$alex_stone_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$alex_stone_sticky_out = alex_stone_get_theme_option('sticky_style')=='columns' 
							&& is_array($alex_stone_stickies) && count($alex_stone_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$alex_stone_cat = alex_stone_get_theme_option('parent_cat');
	$alex_stone_post_type = alex_stone_get_theme_option('post_type');
	$alex_stone_taxonomy = alex_stone_get_post_type_taxonomy($alex_stone_post_type);
	$alex_stone_show_filters = alex_stone_get_theme_option('show_filters');
	$alex_stone_tabs = array();
	if (!alex_stone_is_off($alex_stone_show_filters)) {
		$alex_stone_args = array(
			'type'			=> $alex_stone_post_type,
			'child_of'		=> $alex_stone_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'include'		=> '',
			'number'		=> '',
			'taxonomy'		=> $alex_stone_taxonomy,
			'pad_counts'	=> false
		);
		$alex_stone_portfolio_list = get_terms($alex_stone_args);
		if (is_array($alex_stone_portfolio_list) && count($alex_stone_portfolio_list) > 0) {
			$alex_stone_tabs[$alex_stone_cat] = esc_html__('All', 'alex-stone');
			foreach ($alex_stone_portfolio_list as $alex_stone_term) {
				if (isset($alex_stone_term->term_id)) $alex_stone_tabs[$alex_stone_term->term_id] = $alex_stone_term->name;
			}
		}
	}
	if (count($alex_stone_tabs) > 0) {
		$alex_stone_portfolio_filters_ajax = true;
		$alex_stone_portfolio_filters_active = $alex_stone_cat;
		$alex_stone_portfolio_filters_id = 'portfolio_filters';
		if (!is_customize_preview())
			wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
		?>
		<div class="portfolio_filters alex_stone_tabs alex_stone_tabs_ajax">
			<ul class="portfolio_titles alex_stone_tabs_titles">
				<?php
				foreach ($alex_stone_tabs as $alex_stone_id=>$alex_stone_title) {
					?><li><a href="<?php echo esc_url(alex_stone_get_hash_link(sprintf('#%s_%s_content', $alex_stone_portfolio_filters_id, $alex_stone_id))); ?>" data-tab="<?php echo esc_attr($alex_stone_id); ?>"><?php echo esc_html($alex_stone_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$alex_stone_ppp = alex_stone_get_theme_option('posts_per_page');
			if (alex_stone_is_inherit($alex_stone_ppp)) $alex_stone_ppp = '';
			foreach ($alex_stone_tabs as $alex_stone_id=>$alex_stone_title) {
				$alex_stone_portfolio_need_content = $alex_stone_id==$alex_stone_portfolio_filters_active || !$alex_stone_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $alex_stone_portfolio_filters_id, $alex_stone_id)); ?>"
					class="portfolio_content alex_stone_tabs_content"
					data-blog-template="<?php echo esc_attr(alex_stone_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(alex_stone_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($alex_stone_ppp); ?>"
					data-post-type="<?php echo esc_attr($alex_stone_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($alex_stone_taxonomy); ?>"
					data-cat="<?php echo esc_attr($alex_stone_id); ?>"
					data-parent-cat="<?php echo esc_attr($alex_stone_cat); ?>"
					data-need-content="<?php echo (false===$alex_stone_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($alex_stone_portfolio_need_content) 
						alex_stone_show_portfolio_posts(array(
							'cat' => $alex_stone_id,
							'parent_cat' => $alex_stone_cat,
							'taxonomy' => $alex_stone_taxonomy,
							'post_type' => $alex_stone_post_type,
							'page' => 1,
							'sticky' => $alex_stone_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		alex_stone_show_portfolio_posts(array(
			'cat' => $alex_stone_cat,
			'parent_cat' => $alex_stone_cat,
			'taxonomy' => $alex_stone_taxonomy,
			'post_type' => $alex_stone_post_type,
			'page' => 1,
			'sticky' => $alex_stone_sticky_out
			)
		);
	}

	echo get_query_var('blog_archive_end');

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>