<?php
/**
 * The template to display default site header
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */


$alex_stone_header_css = $alex_stone_header_image = '';
$alex_stone_header_video = alex_stone_get_header_video();
if (true || empty($alex_stone_header_video)) {
	$alex_stone_header_image = get_header_image();
	if (alex_stone_is_on(alex_stone_get_theme_option('header_image_override')) && apply_filters('alex_stone_filter_allow_override_header_image', true)) {
		if (is_category()) {
			if (($alex_stone_cat_img = alex_stone_get_category_image()) != '')
				$alex_stone_header_image = $alex_stone_cat_img;
		} else if (is_singular() || alex_stone_storage_isset('blog_archive')) {
			if (has_post_thumbnail()) {
				$alex_stone_header_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				if (is_array($alex_stone_header_image)) $alex_stone_header_image = $alex_stone_header_image[0];
			} else
				$alex_stone_header_image = '';
		}
	}
}

?><header class="top_panel top_panel_default<?php
					echo !empty($alex_stone_header_image) || !empty($alex_stone_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($alex_stone_header_video!='') echo ' with_bg_video';
					if ($alex_stone_header_image!='') echo ' '.esc_attr(alex_stone_add_inline_css_class('background-image: url('.esc_url($alex_stone_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (alex_stone_is_on(alex_stone_get_theme_option('header_fullheight'))) echo ' header_fullheight alex_stone-full-height';
					?> scheme_<?php echo esc_attr(alex_stone_is_inherit(alex_stone_get_theme_option('header_scheme')) 
													? alex_stone_get_theme_option('color_scheme') 
													: alex_stone_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($alex_stone_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (alex_stone_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Header for single posts
	

?></header>