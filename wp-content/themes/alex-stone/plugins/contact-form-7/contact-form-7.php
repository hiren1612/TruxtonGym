<?php
/* Contact Form 7 support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_cf7_theme_setup9')) {
	add_action( 'after_setup_theme', 'alex_stone_cf7_theme_setup9', 9 );
	function alex_stone_cf7_theme_setup9() {
		
		if (alex_stone_exists_cf7()) {
			add_action( 'wp_enqueue_scripts', 								'alex_stone_cf7_frontend_scripts', 1100 );
			add_filter( 'alex_stone_filter_merge_styles',						'alex_stone_cf7_merge_styles' );
		}
		if (is_admin()) {
			add_filter( 'alex_stone_filter_tgmpa_required_plugins',			'alex_stone_cf7_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_cf7_tgmpa_required_plugins' ) ) {
	
	function alex_stone_cf7_tgmpa_required_plugins($list=array()) {
		if (alex_stone_storage_isset('required_plugins', 'contact-form-7')) {
			// CF7 plugin
			$list[] = array(
					'name' 		=> alex_stone_storage_get_array('required_plugins', 'contact-form-7'),
					'slug' 		=> 'contact-form-7',
					'required' 	=> false
			);

		}
		return $list;
	}
}



// Check if cf7 installed and activated
if ( !function_exists( 'alex_stone_exists_cf7' ) ) {
	function alex_stone_exists_cf7() {
		return class_exists('WPCF7');
	}
}
	
// Enqueue custom styles
if ( !function_exists( 'alex_stone_cf7_frontend_scripts' ) ) {
	
	function alex_stone_cf7_frontend_scripts() {
		if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/contact-form-7/contact-form-7.css')!='')
			wp_enqueue_style( 'alex-stone-contact-form-7',  alex_stone_get_file_url('plugins/contact-form-7/contact-form-7.css'), array(), null );
	}
}
	
// Merge custom styles
if ( !function_exists( 'alex_stone_cf7_merge_styles' ) ) {
	
	function alex_stone_cf7_merge_styles($list) {
		$list[] = 'plugins/contact-form-7/contact-form-7.css';
		return $list;
	}
}
?>