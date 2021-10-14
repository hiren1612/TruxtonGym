<?php
/**
 * The Front Page template file.
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.31
 */

get_header();

// If front-page is a static page
if (get_option('show_on_front') == 'page') {

	// If Front Page Builder is enabled - display sections
	if (alex_stone_is_on(alex_stone_get_theme_option('front_page_enabled'))) {

		if ( have_posts() ) the_post();

		$alex_stone_sections = alex_stone_array_get_keys_by_value(alex_stone_get_theme_option('front_page_sections'), 1, false);
		if (is_array($alex_stone_sections)) {
			foreach ($alex_stone_sections as $alex_stone_section) {
				get_template_part("front-page/section", $alex_stone_section);
			}
		}
	
	// Else - display native page content
	} else
		get_template_part('page');

// Else get index template to show posts
} else
	get_template_part('index');

get_footer();
?>