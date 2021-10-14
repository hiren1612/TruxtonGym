<?php
/**
 * The template to display blog archive
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */

/*
Template Name: Blog archive
*/

/**
 * Make page with this template and put it into menu
 * to display posts as blog archive
 * You can setup output parameters (blog style, posts per page, parent category, etc.)
 * in the Theme Options section (under the page content)
 * You can build this page in the WPBakery Page Builder to make custom page layout:
 * just insert %%CONTENT%% in the desired place of content
 */

// Get template page's content
$alex_stone_content = '';
$alex_stone_blog_archive_mask = '%%CONTENT%%';
$alex_stone_blog_archive_subst = sprintf('<div class="blog_archive">%s</div>', $alex_stone_blog_archive_mask);
if ( have_posts() ) {
	the_post(); 
	if (($alex_stone_content = apply_filters('the_content', get_the_content())) != '') {
		if (($alex_stone_pos = strpos($alex_stone_content, $alex_stone_blog_archive_mask)) !== false) {
			$alex_stone_content = preg_replace('/(\<p\>\s*)?'.$alex_stone_blog_archive_mask.'(\s*\<\/p\>)/i', $alex_stone_blog_archive_subst, $alex_stone_content);
		} else
			$alex_stone_content .= $alex_stone_blog_archive_subst;
		$alex_stone_content = explode($alex_stone_blog_archive_mask, $alex_stone_content);
		// Add VC custom styles to the inline CSS
		$vc_custom_css = get_post_meta( get_the_ID(), '_wpb_shortcodes_custom_css', true );
		if ( !empty( $vc_custom_css ) ) alex_stone_add_inline_css(strip_tags($vc_custom_css));
	}
}

// Prepare args for a new query
$alex_stone_args = array(
	'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish'
);
$alex_stone_args = alex_stone_query_add_posts_and_cats($alex_stone_args, '', alex_stone_get_theme_option('post_type'), alex_stone_get_theme_option('parent_cat'));
$alex_stone_page_number = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
if ($alex_stone_page_number > 1) {
	$alex_stone_args['paged'] = $alex_stone_page_number;
	$alex_stone_args['ignore_sticky_posts'] = true;
}
$alex_stone_ppp = alex_stone_get_theme_option('posts_per_page');
if ((int) $alex_stone_ppp != 0)
	$alex_stone_args['posts_per_page'] = (int) $alex_stone_ppp;
// Make a new query
query_posts( $alex_stone_args );
// Set a new query as main WP Query
$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];

// Set query vars in the new query!
if (is_array($alex_stone_content) && count($alex_stone_content) == 2) {
	set_query_var('blog_archive_start', $alex_stone_content[0]);
	set_query_var('blog_archive_end', $alex_stone_content[1]);
}

get_template_part('index');
?>