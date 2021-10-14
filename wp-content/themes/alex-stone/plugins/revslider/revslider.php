<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_revslider_theme_setup9')) {
	add_action( 'after_setup_theme', 'alex_stone_revslider_theme_setup9', 9 );
	function alex_stone_revslider_theme_setup9() {
		if (alex_stone_exists_revslider()) {
			add_action( 'wp_enqueue_scripts', 					'alex_stone_revslider_frontend_scripts', 1100 );
			add_filter( 'alex_stone_filter_merge_styles',			'alex_stone_revslider_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'alex_stone_filter_tgmpa_required_plugins','alex_stone_revslider_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_revslider_tgmpa_required_plugins' ) ) {
	
	function alex_stone_revslider_tgmpa_required_plugins($list=array()) {
		if (alex_stone_storage_isset('required_plugins', 'revslider')) {
			$path = alex_stone_get_file_dir('plugins/revslider/revslider.zip');
			if (!empty($path) || alex_stone_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> alex_stone_storage_get_array('required_plugins', 'revslider'),
					'slug' 		=> 'revslider',
                    'version'	=> '6.3.2',
					'source'	=> !empty($path) ? $path : 'upload://revslider.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'alex_stone_exists_revslider' ) ) {
	function alex_stone_exists_revslider() {
		return function_exists('rev_slider_shortcode');
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'alex_stone_revslider_frontend_scripts' ) ) {
	
	function alex_stone_revslider_frontend_scripts() {
		if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/revslider/revslider.css')!='')
			wp_enqueue_style( 'alex-stone-revslider',  alex_stone_get_file_url('plugins/revslider/revslider.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'alex_stone_revslider_merge_styles' ) ) {
	
	function alex_stone_revslider_merge_styles($list) {
		$list[] = 'plugins/revslider/revslider.css';
		return $list;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if (alex_stone_exists_revslider()) { require_once ALEX_STONE_THEME_DIR . 'plugins/revslider/revslider.styles.php'; }

?>