<?php
/**
 * The template to show mobile menu
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0
 */
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr(alex_stone_get_theme_option('menu_mobile_fullscreen') > 0 ? 'fullscreen' : 'narrow'); ?> scheme_dark">
	<div class="menu_mobile_inner">
		<a class="menu_mobile_close icon-cancel"></a><?php

		// Logo
		set_query_var('alex_stone_logo_args', array('type' => 'mobile'));
		get_template_part( 'templates/header-logo' );
		set_query_var('alex_stone_logo_args', array());

		// Mobile menu
		$alex_stone_menu_mobile = alex_stone_get_nav_menu('menu_mobile');
		if (empty($alex_stone_menu_mobile)) {
			$alex_stone_menu_mobile = apply_filters('alex_stone_filter_get_mobile_menu', '');
			if (empty($alex_stone_menu_mobile)) $alex_stone_menu_mobile = alex_stone_get_nav_menu('menu_main');
			if (empty($alex_stone_menu_mobile)) $alex_stone_menu_mobile = alex_stone_get_nav_menu();
		}
		if (!empty($alex_stone_menu_mobile)) {
			if (!empty($alex_stone_menu_mobile))
				$alex_stone_menu_mobile = str_replace(
					array('menu_main', 'id="menu-', 'sc_layouts_menu_nav', 'sc_layouts_hide_on_mobile', 'hide_on_mobile'),
					array('menu_mobile', 'id="menu_mobile-', '', '', ''),
					$alex_stone_menu_mobile
					);
			if (strpos($alex_stone_menu_mobile, '<nav ')===false)
				$alex_stone_menu_mobile = sprintf('<nav class="menu_mobile_nav_area">%s</nav>', $alex_stone_menu_mobile);
			alex_stone_show_layout(apply_filters('alex_stone_filter_menu_mobile_layout', $alex_stone_menu_mobile));
		}


		// Social icons
		alex_stone_show_layout(alex_stone_get_socials_links(), '<div class="socials_mobile">', '</div>');
		?>
	</div>
</div>
