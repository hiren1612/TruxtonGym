<?php
/* WPBakery Page Builder support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_vc_theme_setup9')) {
	add_action( 'after_setup_theme', 'alex_stone_vc_theme_setup9', 9 );
	function alex_stone_vc_theme_setup9() {
		if (alex_stone_exists_visual_composer()) {
			add_action( 'wp_enqueue_scripts', 								'alex_stone_vc_frontend_scripts', 1100 );
			add_filter( 'alex_stone_filter_merge_styles',						'alex_stone_vc_merge_styles' );
	
			// Add/Remove params in the standard VC shortcodes
			//-----------------------------------------------------
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,					'alex_stone_vc_add_params_classes', 10, 3 );
			
			// Color scheme
			$scheme = array(
				"param_name" => "scheme",
				"heading" => esc_html__("Color scheme", 'alex-stone'),
				"description" => wp_kses_data( __("Select color scheme to decorate this block", 'alex-stone') ),
				"group" => esc_html__('Colors', 'alex-stone'),
				"admin_label" => true,
				"value" => array_flip(alex_stone_get_list_schemes(true)),
				"type" => "dropdown"
			);
			vc_add_param("vc_section", $scheme);
			vc_add_param("vc_row", $scheme);
			vc_add_param("vc_row_inner", $scheme);
			vc_add_param("vc_column", $scheme);
			vc_add_param("vc_column_inner", $scheme);
			vc_add_param("vc_column_text", $scheme);
			
			// Alter height and hide on mobile for Empty Space
			vc_add_param("vc_empty_space", array(
				"param_name" => "alter_height",
				"heading" => esc_html__("Alter height", 'alex-stone'),
				"description" => wp_kses_data( __("Select alternative height instead value from the field above", 'alex-stone') ),
				"admin_label" => true,
				"value" => array(
					esc_html__('Tiny', 'alex-stone') => 'tiny',
					esc_html__('Small', 'alex-stone') => 'small',
					esc_html__('Medium', 'alex-stone') => 'medium',
					esc_html__('Large', 'alex-stone') => 'large',
					esc_html__('Huge', 'alex-stone') => 'huge',
					esc_html__('From the value above', 'alex-stone') => 'none'
				),
				"type" => "dropdown"
			));
			vc_add_param("vc_empty_space", array(
				"param_name" => "hide_on_mobile",
				"heading" => esc_html__("Hide on mobile", 'alex-stone'),
				"description" => wp_kses_data( __("Hide this block on the mobile devices, when the columns are arranged one under another", 'alex-stone') ),
				"admin_label" => true,
				"std" => 0,
				"value" => array(
					esc_html__("Hide on mobile", 'alex-stone') => "1",
					esc_html__("Hide on tablet", 'alex-stone') => "3",
					esc_html__("Hide on notebook", 'alex-stone') => "2" 
					),
				"type" => "checkbox"
			));
			
			// Add Narrow style to the Progress bars
			vc_add_param("vc_progress_bar", array(
				"param_name" => "narrow",
				"heading" => esc_html__("Narrow", 'alex-stone'),
				"description" => wp_kses_data( __("Use narrow style for the progress bar", 'alex-stone') ),
				"std" => 0,
				"value" => array(esc_html__("Narrow style", 'alex-stone') => "1" ),
				"type" => "checkbox"
			));
			
			// Add param 'Closeable' to the Message Box
			vc_add_param("vc_message", array(
				"param_name" => "closeable",
				"heading" => esc_html__("Closeable", 'alex-stone'),
				"description" => wp_kses_data( __("Add 'Close' button to the message box", 'alex-stone') ),
				"std" => 0,
				"value" => array(esc_html__("Closeable", 'alex-stone') => "1" ),
				"type" => "checkbox"
			));
		}
		if (is_admin()) {
			add_filter( 'alex_stone_filter_tgmpa_required_plugins',		'alex_stone_vc_tgmpa_required_plugins' );
			add_filter( 'vc_iconpicker-type-fontawesome',				'alex_stone_vc_iconpicker_type_fontawesome' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_vc_tgmpa_required_plugins' ) ) {
	
	function alex_stone_vc_tgmpa_required_plugins($list=array()) {
		if (alex_stone_storage_isset('required_plugins', 'js_composer')) {
			$path = alex_stone_get_file_dir('plugins/js_composer/js_composer.zip');
			if (!empty($path) || alex_stone_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> alex_stone_storage_get_array('required_plugins', 'js_composer'),
					'slug' 		=> 'js_composer',
                    'version'	=> '6.4.2',
					'source'	=> !empty($path) ? $path : 'upload://js_composer.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if WPBakery Page Builder installed and activated
if ( !function_exists( 'alex_stone_exists_visual_composer' ) ) {
	function alex_stone_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if WPBakery Page Builder in frontend editor mode
if ( !function_exists( 'alex_stone_vc_is_frontend' ) ) {
	function alex_stone_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}
	
// Enqueue VC custom styles
if ( !function_exists( 'alex_stone_vc_frontend_scripts' ) ) {
	
	function alex_stone_vc_frontend_scripts() {
		if (alex_stone_exists_visual_composer()) {
			if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/js_composer/js_composer.css')!='')
				wp_enqueue_style( 'alex-stone-js-composer',  alex_stone_get_file_url('plugins/js_composer/js_composer.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'alex_stone_vc_merge_styles' ) ) {
	
	function alex_stone_vc_merge_styles($list) {
		$list[] = 'plugins/js_composer/js_composer.css';
		return $list;
	}
}
	
// Add theme icons into VC iconpicker list
if ( !function_exists( 'alex_stone_vc_iconpicker_type_fontawesome' ) ) {
	
	function alex_stone_vc_iconpicker_type_fontawesome($icons) {
		$list = alex_stone_get_list_icons();
		if (!is_array($list) || count($list) == 0) return $icons;
		$rez = array();
		foreach ($list as $icon)
			$rez[] = array($icon => str_replace('icon-', '', $icon));
		return array_merge( $icons, array(esc_html__('Theme Icons', 'alex-stone') => $rez) );
	}
}



// Shortcodes support
//------------------------------------------------------------------------

// Add params to the standard VC shortcodes
if ( !function_exists( 'alex_stone_vc_add_params_classes' ) ) {
	
	function alex_stone_vc_add_params_classes($classes, $sc, $atts) {
		if (in_array($sc, array('vc_section', 'vc_row', 'vc_row_inner', 'vc_column', 'vc_column_inner', 'vc_column_text'))) {
			if (!empty($atts['scheme']) && !alex_stone_is_inherit($atts['scheme']))
				$classes .= ($classes ? ' ' : '') . 'scheme_' . $atts['scheme'];
		} else if (in_array($sc, array('vc_empty_space'))) {
			if (!empty($atts['alter_height']) && !alex_stone_is_off($atts['alter_height']))
				$classes .= ($classes ? ' ' : '') . 'height_' . $atts['alter_height'];
			if (!empty($atts['hide_on_mobile'])) {
				if (strpos($atts['hide_on_mobile'], '1')!==false)	$classes .= ($classes ? ' ' : '') . 'hide_on_mobile';
				if (strpos($atts['hide_on_mobile'], '2')!==false)	$classes .= ($classes ? ' ' : '') . 'hide_on_notebook';
				if (strpos($atts['hide_on_mobile'], '3')!==false)	$classes .= ($classes ? ' ' : '') . 'hide_on_tablet';
			}
		} else if (in_array($sc, array('vc_progress_bar'))) {
			if (!empty($atts['narrow']) && (int) $atts['narrow']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_progress_bar_narrow';
		} else if (in_array($sc, array('vc_message'))) {
			if (!empty($atts['closeable']) && (int) $atts['closeable']==1)
				$classes .= ($classes ? ' ' : '') . 'vc_message_box_closeable';
		}
		return $classes;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (alex_stone_exists_visual_composer()) { require_once ALEX_STONE_THEME_DIR . 'plugins/js_composer/js_composer.styles.php'; }
?>