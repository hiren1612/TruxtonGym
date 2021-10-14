<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.06
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

$alex_stone_header_id = str_replace('header-custom-', '', alex_stone_get_theme_option("header_style"));
if ((int) $alex_stone_header_id == 0) {
	$alex_stone_header_id = alex_stone_get_post_id(array(
												'name' => $alex_stone_header_id,
												'post_type' => defined('TRX_ADDONS_CPT_LAYOUT_PT') ? TRX_ADDONS_CPT_LAYOUT_PT : 'cpt_layouts'
												)
											);
}
$alex_stone_header_meta = get_post_meta($alex_stone_header_id, 'trx_addons_options', true);

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr($alex_stone_header_id); 
				?> top_panel_custom_<?php echo esc_attr(sanitize_title(get_the_title($alex_stone_header_id)));
				echo !empty($alex_stone_header_image) || !empty($alex_stone_header_video) 
					? ' with_bg_image' 
					: ' without_bg_image';
				if ($alex_stone_header_video!='') 
					echo ' with_bg_video';
				if ($alex_stone_header_image!='') 
					echo ' '.esc_attr(alex_stone_add_inline_css_class('background-image: url('.esc_url($alex_stone_header_image).');'));
				if (!empty($alex_stone_header_meta['margin']) != '') 
					echo ' '.esc_attr(alex_stone_add_inline_css_class('margin-bottom: '.esc_attr(alex_stone_prepare_css_value($alex_stone_header_meta['margin'])).';'));
				if (is_single() && has_post_thumbnail()) 
					echo ' with_featured_image';
				if (alex_stone_is_on(alex_stone_get_theme_option('header_fullheight'))) 
					echo ' header_fullheight alex_stone-full-height';
				?> scheme_<?php echo esc_attr(alex_stone_is_inherit(alex_stone_get_theme_option('header_scheme')) 
												? alex_stone_get_theme_option('color_scheme') 
												: alex_stone_get_theme_option('header_scheme'));
				?>"><?php

	// Background video
	if (!empty($alex_stone_header_video)) {
		get_template_part( 'templates/header-video' );
	}
		
	// Custom header's layout
	do_action('alex_stone_action_show_layout', $alex_stone_header_id);

	// Header widgets area
	get_template_part( 'templates/header-widgets' );
		
?></header>