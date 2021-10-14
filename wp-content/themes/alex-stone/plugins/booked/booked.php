<?php
/* Booked Appointments support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_booked_theme_setup9')) {
	add_action( 'after_setup_theme', 'alex_stone_booked_theme_setup9', 9 );
	function alex_stone_booked_theme_setup9() {
		if (alex_stone_exists_booked()) {
			add_action( 'wp_enqueue_scripts', 							'alex_stone_booked_frontend_scripts', 1100 );
			add_filter( 'alex_stone_filter_merge_styles',					'alex_stone_booked_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'alex_stone_filter_tgmpa_required_plugins',		'alex_stone_booked_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_booked_tgmpa_required_plugins' ) ) {
	
	function alex_stone_booked_tgmpa_required_plugins($list=array()) {
		if (alex_stone_storage_isset('required_plugins', 'booked')) {
			$path = alex_stone_get_file_dir('plugins/booked/booked.zip');
			if (!empty($path) || alex_stone_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> alex_stone_storage_get_array('required_plugins', 'booked'),
					'slug' 		=> 'booked',
                    'version'	=> '2.3',
					'source' 	=> !empty($path) ? $path : 'upload://booked.zip',
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'alex_stone_exists_booked' ) ) {
	function alex_stone_exists_booked() {
		return class_exists('booked_plugin');
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'alex_stone_booked_frontend_scripts' ) ) {
	
	function alex_stone_booked_frontend_scripts() {
		if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/booked/booked.css')!='')
			wp_enqueue_style( 'alex-stone-booked',  alex_stone_get_file_url('plugins/booked/booked.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'alex_stone_booked_merge_styles' ) ) {
	
	function alex_stone_booked_merge_styles($list) {
		$list[] = 'plugins/booked/booked.css';
		return $list;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (alex_stone_exists_booked()) { require_once ALEX_STONE_THEME_DIR . 'plugins/booked/booked.styles.php'; }
?>