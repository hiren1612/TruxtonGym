<?php
/* Essential Grid support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_essential_grid_theme_setup9')) {
	add_action( 'after_setup_theme', 'alex_stone_essential_grid_theme_setup9', 9 );
	function alex_stone_essential_grid_theme_setup9() {
		if (alex_stone_exists_essential_grid()) {
			add_action( 'wp_enqueue_scripts', 							'alex_stone_essential_grid_frontend_scripts', 1100 );
			add_filter( 'alex_stone_filter_merge_styles',					'alex_stone_essential_grid_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'alex_stone_filter_tgmpa_required_plugins',		'alex_stone_essential_grid_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_essential_grid_tgmpa_required_plugins' ) ) {
	
	function alex_stone_essential_grid_tgmpa_required_plugins($list=array()) {
		if (alex_stone_storage_isset('required_plugins', 'essential-grid')) {
			$path = alex_stone_get_file_dir('plugins/essential-grid/essential-grid.zip');
			if (!empty($path) || alex_stone_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
						'name' 		=> alex_stone_storage_get_array('required_plugins', 'essential-grid'),
						'slug' 		=> 'essential-grid',
                        'version'	=> '3.0.9',
						'source'	=> !empty($path) ? $path : 'upload://essential-grid.zip',
						'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'alex_stone_exists_essential_grid' ) ) {
	function alex_stone_exists_essential_grid() {
		return defined('EG_PLUGIN_PATH');
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'alex_stone_essential_grid_frontend_scripts' ) ) {
	
	function alex_stone_essential_grid_frontend_scripts() {
		if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/essential-grid/essential-grid.css')!='')
			wp_enqueue_style( 'alex-stone-essential-grid',  alex_stone_get_file_url('plugins/essential-grid/essential-grid.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'alex_stone_essential_grid_merge_styles' ) ) {
	
	function alex_stone_essential_grid_merge_styles($list) {
		$list[] = 'plugins/essential-grid/essential-grid.css';
		return $list;
	}
}
?>