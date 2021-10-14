<?php
/* SiteOrigin Panels support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_sop_theme_setup9')) {
	add_action( 'after_setup_theme', 'alex_stone_sop_theme_setup9', 9 );
	function alex_stone_sop_theme_setup9() {
		if (alex_stone_exists_sop()) {
			add_action( 'wp_enqueue_scripts', 							'alex_stone_sop_frontend_scripts', 1100 );
			add_filter( 'alex_stone_filter_merge_styles',					'alex_stone_sop_merge_styles' );
			add_filter( 'siteorigin_panels_general_style_fields',		'alex_stone_sop_add_row_params', 10, 3 );
			add_filter( 'siteorigin_panels_general_style_attributes',	'alex_stone_sop_row_style_attributes', 10, 2 );
		}
		if (is_admin()) {
			add_filter( 'alex_stone_filter_tgmpa_required_plugins',		'alex_stone_sop_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_sop_tgmpa_required_plugins' ) ) {
	
	function alex_stone_sop_tgmpa_required_plugins($list=array()) {
		if (alex_stone_storage_isset('required_plugins', 'siteorigin-panels')) {
			$list[] = array(
					'name' 		=> esc_html__('SiteOrigin Panels (free Page Builder)', 'alex-stone'),
					'slug' 		=> 'siteorigin-panels',
					'required' 	=> false
			);
			$list[] = array(
					'name' 		=> esc_html__('SiteOrigin Panels Widgets bundle', 'alex-stone'),
					'slug' 		=> 'so-widgets-bundle',
					'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if SiteOrigin Panels is installed and activated
if ( !function_exists( 'alex_stone_exists_sop' ) ) {
	function alex_stone_exists_sop() {
		return class_exists('SiteOrigin_Panels');
	}
}

// Check if SiteOrigin Widgets Bundle is installed and activated
if ( !function_exists( 'alex_stone_exists_sow' ) ) {
	function alex_stone_exists_sow() {
		return class_exists('SiteOrigin_Widgets_Bundle');
	}
}
	
// Enqueue plugin's custom styles
if ( !function_exists( 'alex_stone_sop_frontend_scripts' ) ) {
	
	function alex_stone_sop_frontend_scripts() {
		if (alex_stone_exists_sop()) {
			if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/siteorigin-panels/siteorigin-panels.css')!='')
				wp_enqueue_style( 'alex-stone-siteorigin-panels',  alex_stone_get_file_url('plugins/siteorigin-panels/siteorigin-panels.css'), array(), null );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'alex_stone_sop_merge_styles' ) ) {
	
	function alex_stone_sop_merge_styles($list) {
		$list[] = 'plugins/siteorigin-panels/siteorigin-panels.css';
		return $list;
	}
}



// Shortcodes support
//------------------------------------------------------------------------

// Add params to the standard SOP rows
if ( !function_exists( 'alex_stone_sop_add_row_params' ) ) {
	
	function alex_stone_sop_add_row_params($fields, $post_id, $args) {
		$fields['scheme'] = array(
			'name'        => esc_html__( 'Color scheme', 'alex-stone' ),
			'description' => wp_kses_data( __( 'Select color scheme to decorate this block', 'alex-stone' )),
			'group'       => 'design',
			'priority'    => 3,
			'default'     => 'inherit',
			'options'     => alex_stone_get_list_schemes(true),
			'type'        => 'select'
		);
		return $fields;
	}
}

// Add layouts specific classes to the standard SOP rows
if ( !function_exists( 'alex_stone_sop_row_style_attributes' ) ) {
	
	function alex_stone_sop_row_style_attributes($attributes, $style) {
		if ( !empty($style['scheme']) && !trx_addons_is_inherit($style['scheme']) )
			$attributes['class'][] = 'scheme_' . $style['scheme'];
		return $attributes;
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (alex_stone_exists_sop()) { require_once ALEX_STONE_THEME_DIR . 'plugins/siteorigin-panels/siteorigin-panels.styles.php'; }
?>