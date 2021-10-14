<?php
/**
 * Setup theme-specific fonts and colors
 *
 * @package WordPress
 * @subpackage ALEX_STONE
 * @since ALEX_STONE 1.0.22
 */

if (!defined("ALEX_STONE_THEME_FREE")) define("ALEX_STONE_THEME_FREE", false);

// Theme storage
$ALEX_STONE_STORAGE = array(
	// Theme required plugin's slugs
	'required_plugins' => array_merge(

		// List of plugins for both - FREE and PREMIUM versions
		//-----------------------------------------------------
		array(
			// Required plugins
			// DON'T COMMENT OR REMOVE NEXT LINES!
			'trx_addons'					=> esc_html__('ThemeREX Addons', 'alex-stone'),
			
			// Recommended (supported) plugins
			// If plugin not need - comment (or remove) it
			'instagram-feed'				=> esc_html__('Instagram Feed', 'alex-stone'),
			'woocommerce'					=> esc_html__('WooCommerce', 'alex-stone'),
            'elegro-payment'				=> esc_html__('elegro Crypto Payment', 'alex-stone'),
            'yith-woocommerce-compare'		=> esc_html__('YITH WooCommerce Сompare', 'alex-stone'),
            'yith-woocommerce-wishlist'		=> esc_html__('YITH WooCommerce Wishlist', 'alex-stone'),
            'contact-form-7'				=> esc_html__('Contact Form 7', 'alex-stone'),
            'trx_updater'				    => esc_html__('ThemeREX Updater', 'alex-stone')
		),

		// List of plugins for PREMIUM version only
		//-----------------------------------------------------
		ALEX_STONE_THEME_FREE ? array() : array(

			// Recommended (supported) plugins
			// If plugin not need - comment (or remove) it
			'booked'						=> esc_html__('Booked Appointments', 'alex-stone'),
			'js_composer'					=> esc_html__('WPBakery Page Builder', 'alex-stone'),
			'essential-grid'				=> esc_html__('Essential Grid', 'alex-stone'),
			'revslider'						=> esc_html__('Revolution Slider', 'alex-stone')
		)
	),
	
	// Theme-specific URLs (will be escaped in place of the output)
	'theme_demo_url' => 'http://alex-stone.themerex.net',
	'theme_doc_url' => 'http://alex-stone.themerex.net/doc/',
	'theme_support_url' => 'https://themerex.net/support',
    'theme_download_url'=> 'https://1.envato.market/c/1262870/275988/4415?subId1=ancora&u=themeforest.net/item/alex-stone-personal-gym-trainer-theme/20793046',
);

// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)

if ( !function_exists('alex_stone_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'alex_stone_customizer_theme_setup1', 1 );
	function alex_stone_customizer_theme_setup1() {

		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		alex_stone_storage_set('settings', array(
			
			'duplicate_options'		=> 'none',		// none  - use separate options for template and child-theme
													// child - duplicate theme options from the main theme to the child-theme only
													// both  - sinchronize changes in the theme options between main and child themes
			
			'custmize_refresh'		=> 'auto',		// Refresh method for preview area in the Appearance - Customize:
													// auto - refresh preview area on change each field with Theme Options
													// manual - refresh only obn press button 'Refresh' at the top of Customize frame
		
			'max_load_fonts'		=> 5,			// Max fonts number to load from Google fonts or from uploaded fonts
		
			'comment_maxlength'		=> 1000,		// Max length of the message from contact form

			'comment_after_name'	=> true,		// Place 'comment' field before the 'name' and 'email'
			
			'socials_type'			=> 'icons',		// Type of socials:
													// icons - use font icons to present social networks
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_type'			=> 'icons',		// Type of other icons:
													// icons - use font icons to present icons
													// images - use images from theme's folder trx_addons/css/icons.png
			
			'icons_selector'		=> 'internal',	// Icons selector in the shortcodes:
													// standard VC icons selector (very slow and don't support images)
													// internal - internal popup with plugin's or theme's icons list (fast)
			'disable_jquery_ui'		=> false,		// Prevent loading custom jQuery UI libraries in the third-party plugins
		
			'use_mediaelements'		=> true,		// Load script "Media Elements" to play video and audio
			
			'tgmpa_upload'			=> false,		// Allow upload not pre-packaged plugins via TGMPA
			
			'allow_theme_layouts'	=> true			// Include theme's default headers and footers to the list after custom layouts
													// or leave in the list only custom layouts
		));


		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		
		alex_stone_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Roboto Slab',
				'family' => 'serif',
				'styles' => '100,300,400,700'		// Parameter 'style' used only for the Google fonts
				),
			// Font-face packed with theme
			array(
				'name'   => 'Titillium Web',
				'family' => 'sans-serif',
				'styles' => '200,200i,300,300i,400,400i,600,600i,700,700i,900'		// Parameter 'style' used only for the Google fonts
				)
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		alex_stone_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		alex_stone_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'alex-stone'),
				'description'		=> esc_html__('Font settings of the main text of the site', 'alex-stone'),
				'font-family'		=> '"Roboto Slab",serif',
				'font-size' 		=> '1rem',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.65em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0.14px',
				'margin-top'		=> '0px',
				'margin-bottom'		=> '1.55em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '4.8em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.12em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-1.1px',
				'margin-top'		=> '1.37em',
				'margin-bottom'		=> '0.7em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '3.667em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.19em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-0.8px',
				'margin-top'		=> '1.75em',
				'margin-bottom'		=> '0.75em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '3em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.35em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-0.65px',
				'margin-top'		=> '2.13em',
				'margin-bottom'		=> '0.77em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '2.4em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.35em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-0.2px',
				'margin-top'		=> '2.82em',
				'margin-bottom'		=> '0.88em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '1.867em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.3em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-0.27px',
				'margin-top'		=> '3.8em',
				'margin-bottom'		=> '0.85em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '1.533em',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.4em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '-0.23px',
				'margin-top'		=> '4.6em',
				'margin-bottom'		=> '0.9412em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'alex-stone'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '1.8em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1px'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '1.067em',
				'font-weight'		=> '700',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0.4px'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'alex-stone'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'alex-stone'),
				'font-family'		=> '"Roboto Slab",serif',
				'font-size' 		=> '1em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.2em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'alex-stone'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'alex-stone'),
				'font-family'		=> '"Roboto Slab",serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.4em',
				'margin-bottom'		=> ''
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'alex-stone'),
				'description'		=> esc_html__('Font settings of the main menu items', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '20px',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'alex-stone'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'alex-stone'),
				'font-family'		=> '"Titillium Web",sans-serif',
				'font-size' 		=> '20px',
				'font-weight'		=> '600',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		alex_stone_storage_set('scheme_color_groups', array(
			'main'	=> array(
							'title'			=> esc_html__('Main', 'alex-stone'),
							'description'	=> esc_html__('Colors of the main content area', 'alex-stone')
							),
			'alter'	=> array(
							'title'			=> esc_html__('Alter', 'alex-stone'),
							'description'	=> esc_html__('Colors of the alternative blocks (sidebars, etc.)', 'alex-stone')
							),
			'extra'	=> array(
							'title'			=> esc_html__('Extra', 'alex-stone'),
							'description'	=> esc_html__('Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'alex-stone')
							),
			'inverse' => array(
							'title'			=> esc_html__('Inverse', 'alex-stone'),
							'description'	=> esc_html__('Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'alex-stone')
							),
			'input'	=> array(
							'title'			=> esc_html__('Input', 'alex-stone'),
							'description'	=> esc_html__('Colors of the form fields (text field, textarea, select, etc.)', 'alex-stone')
							),
			)
		);
		alex_stone_storage_set('scheme_color_names', array(
			'bg_color'	=> array(
							'title'			=> esc_html__('Background color', 'alex-stone'),
							'description'	=> esc_html__('Background color of this block in the normal state', 'alex-stone')
							),
			'bg_hover'	=> array(
							'title'			=> esc_html__('Background hover', 'alex-stone'),
							'description'	=> esc_html__('Background color of this block in the hovered state', 'alex-stone')
							),
			'bd_color'	=> array(
							'title'			=> esc_html__('Border color', 'alex-stone'),
							'description'	=> esc_html__('Border color of this block in the normal state', 'alex-stone')
							),
			'bd_hover'	=>  array(
							'title'			=> esc_html__('Border hover', 'alex-stone'),
							'description'	=> esc_html__('Border color of this block in the hovered state', 'alex-stone')
							),
			'text'		=> array(
							'title'			=> esc_html__('Text', 'alex-stone'),
							'description'	=> esc_html__('Color of the plain text inside this block', 'alex-stone')
							),
			'text_dark'	=> array(
							'title'			=> esc_html__('Text dark', 'alex-stone'),
							'description'	=> esc_html__('Color of the dark text (bold, header, etc.) inside this block', 'alex-stone')
							),
			'text_light'=> array(
							'title'			=> esc_html__('Text light', 'alex-stone'),
							'description'	=> esc_html__('Color of the light text (post meta, etc.) inside this block', 'alex-stone')
							),
			'text_link'	=> array(
							'title'			=> esc_html__('Link', 'alex-stone'),
							'description'	=> esc_html__('Color of the links inside this block', 'alex-stone')
							),
			'text_hover'=> array(
							'title'			=> esc_html__('Link hover', 'alex-stone'),
							'description'	=> esc_html__('Color of the hovered state of links inside this block', 'alex-stone')
							),
			'text_link2'=> array(
							'title'			=> esc_html__('Link 2', 'alex-stone'),
							'description'	=> esc_html__('Color of the accented texts (areas) inside this block', 'alex-stone')
							),
			'text_hover2'=> array(
							'title'			=> esc_html__('Link 2 hover', 'alex-stone'),
							'description'	=> esc_html__('Color of the hovered state of accented texts (areas) inside this block', 'alex-stone')
							),
			'text_link3'=> array(
							'title'			=> esc_html__('Link 3', 'alex-stone'),
							'description'	=> esc_html__('Color of the other accented texts (buttons) inside this block', 'alex-stone')
							),
			'text_hover3'=> array(
							'title'			=> esc_html__('Link 3 hover', 'alex-stone'),
							'description'	=> esc_html__('Color of the hovered state of other accented texts (buttons) inside this block', 'alex-stone')
							)
			)
		);
		alex_stone_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'alex-stone'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#ffffff', //
					'bd_color'			=> '#e6e6e6', // 
		
					// Text and links colors
					'text'				=> '#888888', //
					'text_light'		=> '#a1a1a1', //
					'text_dark'			=> '#383838', //
					'text_link'			=> '#c1d445', //
					'text_hover'		=> '#9aaa35', //
					'text_link2'		=> '#383838', //
					'text_hover2'		=> '#c1d445', //
					'text_link3'		=> '#1e1f20', //
					'text_hover3'		=> '#bfd169',
		
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#f4f3f1', //
					'alter_bg_hover'	=> '#ffffff', //
					'alter_bd_color'	=> '#c1d445', //
					'alter_bd_hover'	=> '#dadada',
					'alter_text'		=> '#888888', //
					'alter_light'		=> '#a1a1a1', //
					'alter_dark'		=> '#595959', //
					'alter_link'		=> '#c1d445', //
					'alter_hover'		=> '#9aaa35',
					'alter_link2'		=> '#ffffff', //
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',
		
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#333331', //
					'extra_bg_hover'	=> '#c1d445', //
					'extra_bd_color'	=> '#ffffff', //
					'extra_bd_hover'	=> '#a0a0a0', //
					'extra_text'		=> '#c9c6c6', //
					'extra_light'		=> '#a0a0a0', //
					'extra_dark'		=> '#ffffff', //
					'extra_link'		=> '#c1d445', //
					'extra_hover'		=> '#a4b34a', // 
					'extra_link2'		=> '#f4f3f1', //
					'extra_hover2'		=> '#bfd169', //
					'extra_link3'		=> '#a0a0a0', //
					'extra_hover3'		=> '#ffffff', //
		
					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#f0f0f0', //
					'input_bg_hover'	=> '#f0f0f0', //
					'input_bd_color'	=> '#f0f0f0', //
					'input_bd_hover'	=> '#c1d445', //
					'input_text'		=> '#afadad', //
					'input_light'		=> '#afadad', //
					'input_dark'		=> '#888888', //
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#67bcc1',
					'inverse_bd_hover'	=> '#5aa4a9',
					'inverse_text'		=> '#ffffff', //
					'inverse_light'		=> '#333333',
					'inverse_dark'		=> '#000000',
					'inverse_link'		=> '#ffffff', //
					'inverse_hover'		=> '#1d1d1d'
				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'alex-stone'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#242422', //
					'bd_color'			=> '#20201f', //?
		
					// Text and links colors
					'text'				=> '#c9c6c6', //
					'text_light'		=> '#757575', //
					'text_dark'			=> '#ffffff', // 
					'text_link'			=> '#c1d445', //
					'text_hover'		=> '#9aaa35', //
					'text_link2'		=> '#ffffff', //
					'text_hover2'		=> '#c1d445', //
					'text_link3'		=> '#f5f5f6',
					'text_hover3'		=> '#c1d445',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#333331', //
					'alter_bg_hover'	=> '#242422', //
					'alter_bd_color'	=> '#c1d445', // 
					'alter_bd_hover'	=> '#3d3d3d',
					'alter_text'		=> '#c9c6c6', //
					'alter_light'		=> '#757575', //
					'alter_dark'		=> '#ffffff', //
					'alter_link'		=> '#c1d445', //
					'alter_hover'		=> '#fe7259',
					'alter_link2'		=> '#afadad', //
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#ffffff', //
					'extra_bg_hover'	=> '#c1d445', //
					'extra_bd_color'	=> '#ffffff', //
					'extra_bd_hover'	=> '#a0a0a0', //
					'extra_text'		=> '#5e5e5e', //
					'extra_light'		=> '#a0a0a0', //
					'extra_dark'		=> '#383838', //
					'extra_link'		=> '#c1d445', //
					'extra_hover'		=> '#a4b34a', // 
					'extra_link2'		=> '#f5f5f6', //
					'extra_hover2'		=> '#c1d445', //
					'extra_link3'		=> '#ffffff', //
					'extra_hover3'		=> '#ffffff', //

					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#333331', //
					'input_bg_hover'	=> '#333331', //
					'input_bd_color'	=> '#333331', //
					'input_bd_hover'	=> '#c1d445', //
					'input_text'		=> '#afadad', //
					'input_light'		=> '#afadad', //
					'input_dark'		=> '#c9c6c6', //
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#e36650',
					'inverse_bd_hover'	=> '#cb5b47',
					'inverse_text'		=> '#1d1d1d',
					'inverse_light'		=> '#5f5f5f',
					'inverse_dark'		=> '#000000',
					'inverse_link'		=> '#ffffff', //
					'inverse_hover'		=> '#1d1d1d'
				)
			)
		
		));
		
		// Simple schemes substitution
		alex_stone_storage_set('schemes_simple', array(
			// Main color	// Slave elements and it's darkness koef.
			'text_link'		=> array('alter_hover' => 1,	'extra_link' => 1, 'inverse_bd_color' => 0.85, 'inverse_bd_hover' => 0.7),
			'text_hover'	=> array('alter_link' => 1,		'extra_hover' => 1),
			'text_link2'	=> array('alter_hover2' => 1,	'extra_link2' => 1),
			'text_hover2'	=> array('alter_link2' => 1,	'extra_hover2' => 1),
			'text_link3'	=> array('alter_hover3' => 1,	'extra_link3' => 1),
			'text_hover3'	=> array('alter_link3' => 1,	'extra_hover3' => 1)
		));
	}
}

			
// Additional (calculated) theme-specific colors
// Attention! Don't forget setup custom colors also in the theme.customizer.color-scheme.js
if (!function_exists('alex_stone_customizer_add_theme_colors')) {
	function alex_stone_customizer_add_theme_colors($colors) {
		if (substr($colors['text'], 0, 1) == '#') {
			$colors['bg_color_0']  = alex_stone_hex2rgba( $colors['bg_color'], 0 );
			$colors['bg_color_02']  = alex_stone_hex2rgba( $colors['bg_color'], 0.2 );
			$colors['bg_color_07']  = alex_stone_hex2rgba( $colors['bg_color'], 0.7 );
			$colors['bg_color_08']  = alex_stone_hex2rgba( $colors['bg_color'], 0.8 );
			$colors['bg_color_09']  = alex_stone_hex2rgba( $colors['bg_color'], 0.9 );
			$colors['alter_bg_color_02']  = alex_stone_hex2rgba( $colors['alter_bg_color'], 0.2 );
			$colors['alter_bg_color_04']  = alex_stone_hex2rgba( $colors['alter_bg_color'], 0.4 );
			$colors['alter_bg_color_05']  = alex_stone_hex2rgba( $colors['alter_bg_color'], 0.5 );
			$colors['alter_bg_color_07']  = alex_stone_hex2rgba( $colors['alter_bg_color'], 0.7 );
			$colors['alter_bd_color_02']  = alex_stone_hex2rgba( $colors['alter_bd_color'], 0.2 );
			$colors['extra_bg_color_07']  = alex_stone_hex2rgba( $colors['extra_bg_color'], 0.7 );
			$colors['text_dark_013']  = alex_stone_hex2rgba( $colors['text_dark'], 0.13 );
			$colors['text_dark_05']  = alex_stone_hex2rgba( $colors['text_dark'], 0.5 );
			$colors['text_dark_065']  = alex_stone_hex2rgba( $colors['text_dark'], 0.65 );
			$colors['text_dark_07']  = alex_stone_hex2rgba( $colors['text_dark'], 0.7 );
			$colors['text_link_02']  = alex_stone_hex2rgba( $colors['text_link'], 0.2 );
			$colors['text_link_07']  = alex_stone_hex2rgba( $colors['text_link'], 0.7 );
			$colors['extra_link2_007']  = alex_stone_hex2rgba( $colors['extra_link2'], 0.07 );
			$colors['extra_bg_color_016']  = alex_stone_hex2rgba( $colors['extra_bg_color'], 0.16 );
			$colors['extra_bd_color_03']  = alex_stone_hex2rgba( $colors['extra_bd_color'], 0.3 );
			$colors['extra_bd_hover_03']  = alex_stone_hex2rgba( $colors['extra_bd_hover'], 0.3 );
			$colors['inverse_link_08']  = alex_stone_hex2rgba( $colors['inverse_link'], 0.8 );
			$colors['text_link_blend'] = alex_stone_hsb2hex(alex_stone_hex2hsb( $colors['text_link'], 2, -5, 5 ));
			$colors['alter_link_blend'] = alex_stone_hsb2hex(alex_stone_hex2hsb( $colors['alter_link'], 2, -5, 5 ));
		} else {
			$colors['bg_color_0'] = '{{ data.bg_color_0 }}';
			$colors['bg_color_02'] = '{{ data.bg_color_02 }}';
			$colors['bg_color_07'] = '{{ data.bg_color_07 }}';
			$colors['bg_color_08'] = '{{ data.bg_color_08 }}';
			$colors['bg_color_09'] = '{{ data.bg_color_09 }}';
			$colors['alter_bg_color_02'] = '{{ data.alter_bg_color_02 }}';
			$colors['alter_bg_color_04'] = '{{ data.alter_bg_color_04 }}';
			$colors['alter_bg_color_05'] = '{{ data.alter_bg_color_05 }}';
			$colors['alter_bg_color_07'] = '{{ data.alter_bg_color_07 }}';
			$colors['alter_bd_color_02'] = '{{ data.alter_bd_color_02 }}';
			$colors['extra_bg_color_07'] = '{{ data.extra_bg_color_07 }}';
			$colors['text_dark_013'] = '{{ data.text_dark_013 }}';
			$colors['text_dark_05'] = '{{ data.text_dark_05 }}';
			$colors['text_dark_065'] = '{{ data.text_dark_065 }}';
			$colors['text_dark_07'] = '{{ data.text_dark_07 }}';
			$colors['text_link_02'] = '{{ data.text_link_02 }}';
			$colors['text_link_07'] = '{{ data.text_link_07 }}';
			$colors['extra_link2_007'] = '{{ data.extra_link2_007 }}';
			$colors['extra_bg_color_016'] = '{{ data.extra_bg_color_016 }}';
			$colors['extra_bd_color_03'] = '{{ data.extra_bd_color_03 }}';
			$colors['extra_bd_hover_03'] = '{{ data.extra_bd_hover_03 }}';
			$colors['inverse_link_08'] = '{{ data.inverse_link_08 }}';
			$colors['text_link_blend'] = '{{ data.text_link_blend }}';
			$colors['alter_link_blend'] = '{{ data.alter_link_blend }}';
		}
		return $colors;
	}
}


			
// Additional theme-specific fonts rules
// Attention! Don't forget setup fonts rules also in the theme.customizer.color-scheme.js
if (!function_exists('alex_stone_customizer_add_theme_fonts')) {
	function alex_stone_customizer_add_theme_fonts($fonts) {
		$rez = array();	
		foreach ($fonts as $tag => $font) {
			if (substr($font['font-family'], 0, 2) != '{{') {
				$rez[$tag.'_font-family'] 		= !empty($font['font-family']) && !alex_stone_is_inherit($font['font-family'])
														? 'font-family:' . trim($font['font-family']) . ';' 
														: '';
				$rez[$tag.'_font-size'] 		= !empty($font['font-size']) && !alex_stone_is_inherit($font['font-size'])
														? 'font-size:' . alex_stone_prepare_css_value($font['font-size']) . ";"
														: '';
				$rez[$tag.'_line-height'] 		= !empty($font['line-height']) && !alex_stone_is_inherit($font['line-height'])
														? 'line-height:' . trim($font['line-height']) . ";"
														: '';
				$rez[$tag.'_font-weight'] 		= !empty($font['font-weight']) && !alex_stone_is_inherit($font['font-weight'])
														? 'font-weight:' . trim($font['font-weight']) . ";"
														: '';
				$rez[$tag.'_font-style'] 		= !empty($font['font-style']) && !alex_stone_is_inherit($font['font-style'])
														? 'font-style:' . trim($font['font-style']) . ";"
														: '';
				$rez[$tag.'_text-decoration'] 	= !empty($font['text-decoration']) && !alex_stone_is_inherit($font['text-decoration'])
														? 'text-decoration:' . trim($font['text-decoration']) . ";"
														: '';
				$rez[$tag.'_text-transform'] 	= !empty($font['text-transform']) && !alex_stone_is_inherit($font['text-transform'])
														? 'text-transform:' . trim($font['text-transform']) . ";"
														: '';
				$rez[$tag.'_letter-spacing'] 	= !empty($font['letter-spacing']) && !alex_stone_is_inherit($font['letter-spacing'])
														? 'letter-spacing:' . trim($font['letter-spacing']) . ";"
														: '';
				$rez[$tag.'_margin-top'] 		= !empty($font['margin-top']) && !alex_stone_is_inherit($font['margin-top'])
														? 'margin-top:' . alex_stone_prepare_css_value($font['margin-top']) . ";"
														: '';
				$rez[$tag.'_margin-bottom'] 	= !empty($font['margin-bottom']) && !alex_stone_is_inherit($font['margin-bottom'])
														? 'margin-bottom:' . alex_stone_prepare_css_value($font['margin-bottom']) . ";"
														: '';
			} else {
				$rez[$tag.'_font-family']		= '{{ data["'.$tag.'_font-family"] }}';
				$rez[$tag.'_font-size']			= '{{ data["'.$tag.'_font-size"] }}';
				$rez[$tag.'_line-height']		= '{{ data["'.$tag.'_line-height"] }}';
				$rez[$tag.'_font-weight']		= '{{ data["'.$tag.'_font-weight"] }}';
				$rez[$tag.'_font-style']		= '{{ data["'.$tag.'_font-style"] }}';
				$rez[$tag.'_text-decoration']	= '{{ data["'.$tag.'_text-decoration"] }}';
				$rez[$tag.'_text-transform']	= '{{ data["'.$tag.'_text-transform"] }}';
				$rez[$tag.'_letter-spacing']	= '{{ data["'.$tag.'_letter-spacing"] }}';
				$rez[$tag.'_margin-top']		= '{{ data["'.$tag.'_margin-top"] }}';
				$rez[$tag.'_margin-bottom']		= '{{ data["'.$tag.'_margin-bottom"] }}';
			}
		}
		return $rez;
	}
}




//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------

if ( !function_exists('alex_stone_customizer_theme_setup') ) {
	add_action( 'after_setup_theme', 'alex_stone_customizer_theme_setup' );
	function alex_stone_customizer_theme_setup() {

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size(370, 0, false);
		
		// Add thumb sizes
		// ATTENTION! If you change list below - check filter's names in the 'trx_addons_filter_get_thumb_size' hook
		$thumb_sizes = apply_filters('alex_stone_filter_add_thumb_sizes', array(
			'alex_stone-thumb-courses' 	=> array( 480, 480, true), //
			'alex_stone-thumb-huge'		=> array(1170, 813, true), //
			'alex_stone-thumb-big' 		=> array( 767, 533, true), //
			'alex_stone-thumb-med' 		=> array( 481, 374, true), //
			'alex_stone-thumb-med-alt' 	=> array( 481, 309, true), //
			'alex_stone-thumb-tiny' 		=> array(  90,  90, true),
			'alex_stone-thumb-masonry-big' => array( 760,   0, false),		// Only downscale, not crop
			'alex_stone-thumb-masonry'		=> array( 370,   0, false),		// Only downscale, not crop
			)
		);
		$mult = alex_stone_get_theme_option('retina_ready', 1);
		if ($mult > 1) $GLOBALS['content_width'] = apply_filters( 'alex_stone_filter_content_width', 1170*$mult);
		foreach ($thumb_sizes as $k=>$v) {
			// Add Original dimensions
			add_image_size( $k, $v[0], $v[1], $v[2]);
			// Add Retina dimensions
			if ($mult > 1) add_image_size( $k.'-@retina', $v[0]*$mult, $v[1]*$mult, $v[2]);
		}

	}
}

if ( !function_exists('alex_stone_customizer_image_sizes') ) {
	add_filter( 'image_size_names_choose', 'alex_stone_customizer_image_sizes' );
	function alex_stone_customizer_image_sizes( $sizes ) {
		$thumb_sizes = apply_filters('alex_stone_filter_add_thumb_sizes', array(
			'alex_stone-thumb-courses'		=> esc_html__( 'Courses image', 'alex-stone' ),
			'alex_stone-thumb-huge'		=> esc_html__( 'Huge image', 'alex-stone' ),
			'alex_stone-thumb-big'			=> esc_html__( 'Large image', 'alex-stone' ),
			'alex_stone-thumb-med'			=> esc_html__( 'Medium image', 'alex-stone' ),
			'alex_stone-thumb-med-alt'		=> esc_html__( 'Medium Alt image', 'alex-stone' ),
			'alex_stone-thumb-tiny'		=> esc_html__( 'Small square avatar', 'alex-stone' ),
			'alex_stone-thumb-masonry-big'	=> esc_html__( 'Masonry Large (scaled)', 'alex-stone' ),
			'alex_stone-thumb-masonry'		=> esc_html__( 'Masonry (scaled)', 'alex-stone' ),
			)
		);
		$mult = alex_stone_get_theme_option('retina_ready', 1);
		foreach($thumb_sizes as $k=>$v) {
			$sizes[$k] = $v;
			if ($mult > 1) $sizes[$k.'-@retina'] = $v.' '.esc_html__('@2x', 'alex-stone' );
		}
		return $sizes;
	}
}

// Remove some thumb-sizes from the ThemeREX Addons list
if ( !function_exists( 'alex_stone_customizer_trx_addons_add_thumb_sizes' ) ) {
	add_filter( 'trx_addons_filter_add_thumb_sizes', 'alex_stone_customizer_trx_addons_add_thumb_sizes');
	function alex_stone_customizer_trx_addons_add_thumb_sizes($list=array()) {
		if (is_array($list)) {
			foreach ($list as $k=>$v) {
				if (in_array($k, array(
								'trx_addons-thumb-huge',
								'trx_addons-thumb-big',
								'trx_addons-thumb-medium',
								'trx_addons-thumb-tiny',
								'trx_addons-thumb-masonry-big',
								'trx_addons-thumb-masonry',
								)
							)
						) unset($list[$k]);
			}
		}
		return $list;
	}
}

// and replace removed styles with theme-specific thumb size
if ( !function_exists( 'alex_stone_customizer_trx_addons_get_thumb_size' ) ) {
	add_filter( 'trx_addons_filter_get_thumb_size', 'alex_stone_customizer_trx_addons_get_thumb_size');
	function alex_stone_customizer_trx_addons_get_thumb_size($thumb_size='') {
		return str_replace(array(
							'trx_addons-thumb-huge',
							'trx_addons-thumb-huge-@retina',
							'trx_addons-thumb-big',
							'trx_addons-thumb-big-@retina',
							'trx_addons-thumb-medium',
							'trx_addons-thumb-medium-@retina',
							'trx_addons-thumb-tiny',
							'trx_addons-thumb-tiny-@retina',
							'trx_addons-thumb-masonry-big',
							'trx_addons-thumb-masonry-big-@retina',
							'trx_addons-thumb-masonry',
							'trx_addons-thumb-masonry-@retina',
							),
							array(
							'alex_stone-thumb-huge',
							'alex_stone-thumb-huge-@retina',
							'alex_stone-thumb-big',
							'alex_stone-thumb-big-@retina',
							'alex_stone-thumb-med',
							'alex_stone-thumb-med-@retina',
							'alex_stone-thumb-tiny',
							'alex_stone-thumb-tiny-@retina',
							'alex_stone-thumb-masonry-big',
							'alex_stone-thumb-masonry-big-@retina',
							'alex_stone-thumb-masonry',
							'alex_stone-thumb-masonry-@retina',
							),
							$thumb_size);
	}
}




//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'alex_stone_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'alex_stone_importer_set_options', 9 );
	function alex_stone_importer_set_options($options=array()) {
		if (is_array($options)) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			$options['demo_url'] = esc_url(alex_stone_get_protocol() . '://demofiles.themerex.net/alex-stone/');
			// Required plugins
			$options['required_plugins'] = array_keys(alex_stone_storage_get('required_plugins'));
			// Default demo
			$options['files']['default']['title'] = esc_html__('Alex Stone Demo', 'alex-stone');
			$options['files']['default']['domain_dev'] = esc_url('http://alex-stone.themerex.net');		// Developers domain
			$options['files']['default']['domain_demo']= esc_url('http://alex-stone.themerex.net');		// Demo-site domain
			// If theme need more demo - just copy 'default' and change required parameter

		}
		return $options;
	}
}




// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('alex_stone_create_theme_options')) {

	function alex_stone_create_theme_options() {

		// Message about options override. 
		// Attention! Not need esc_html() here, because this message put in wp_kses_data() below
		$msg_override =sprintf(esc_html__('%sAttention!%s Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages', 'alex-stone'),'<b>','</b>');

		alex_stone_storage_set('options', array(
		
			// 'Logo & Site Identity'
			'title_tagline' => array(
				"title" => esc_html__('Logo & Site Identity', 'alex-stone'),
				"desc" => '',
				"priority" => 10,
				"type" => "section"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo in the header', 'alex-stone'),
				"desc" => '',
				"priority" => 20,
				"type" => "info",
				),
			'logo_text' => array(
				"title" => esc_html__('Use Site Name as Logo', 'alex-stone'),
				"desc" => wp_kses_data( __('Use the site title and tagline as a text logo if no image is selected', 'alex-stone') ),
				"class" => "alex_stone_column-1_2 alex_stone_new_row",
				"priority" => 30,
				"std" => 1,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_retina_enabled' => array(
				"title" => esc_html__('Allow retina display logo', 'alex-stone'),
				"desc" => wp_kses_data( __('Show fields to select logo images for Retina display', 'alex-stone') ),
				"class" => "alex_stone_column-1_2",
				"refresh" => false,
				"std" => 0,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo' => array(
				"title" => esc_html__('Logo', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload site logo', 'alex-stone') ),
				"class" => "alex_stone_column-1_2 alex_stone_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'alex-stone') ),
				"class" => "alex_stone_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile' => array(
				"title" => esc_html__('Logo mobile', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile menu', 'alex-stone') ),
				"class" => "alex_stone_column-1_2 alex_stone_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_retina' => array(
				"title" => esc_html__('Logo mobile for Retina', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'alex-stone') ),
				"class" => "alex_stone_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "image"
				),
		
		
			// 'General settings'
			'general' => array(
				"title" => esc_html__('General Settings', 'alex-stone'),
				"desc" => wp_kses_data( __('Settings for the entire site', 'alex-stone') )
							. '<br>'
							. wp_kses_data( $msg_override ),
				"priority" => 20,
				"type" => "section",
				),

			'general_layout_info' => array(
				"title" => esc_html__('Layout', 'alex-stone'),
				"desc" => '',
				"type" => "info",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'alex-stone'),
				"desc" => wp_kses_data( __('Select width of the body content', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'alex-stone')
				),
				"refresh" => false,
				"std" => 'wide',
				"options" => array(
					'boxed'		=> esc_html__('Boxed',		'alex-stone'),
					'wide'		=> esc_html__('Wide',		'alex-stone'),
					'fullscreen'=> esc_html__('Fullscreen',	'alex-stone')
				),
				"type" => "select"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'alex-stone') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'alex-stone')
				),
				"std" => '',
				"hidden" => true,
				"type" => "image"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'alex-stone'),
				"desc" => wp_kses_data( __('Remove margins above and below the content area', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'alex-stone')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),

			'general_sidebar_info' => array(
				"title" => esc_html__('Sidebar', 'alex-stone'),
				"desc" => '',
				"type" => "info",
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'alex-stone'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'alex-stone')
				),
				"std" => 'right',
				"options" => array(),
				"type" => "switch"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'alex-stone'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'alex-stone')
				),
				"dependency" => array(
					'sidebar_position' => array('left', 'right')
				),
				"std" => 'sidebar_widgets',
				"options" => array(),
				"type" => "select"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'alex-stone'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'alex-stone') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),


			'general_widgets_info' => array(
				"title" => esc_html__('Additional widgets', 'alex-stone'),
				"desc" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "info",
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets at the top of the page', 'alex-stone'),
				"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'alex-stone')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'alex-stone'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'alex-stone')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'alex-stone'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'alex-stone')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets at the bottom of the page', 'alex-stone'),
				"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'alex-stone')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
				),

			'general_effects_info' => array(
				"title" => esc_html__('Design & Effects', 'alex-stone'),
				"hidden" => true,
				"desc" => '',
				"type" => "info",
				),

			'general_misc_info' => array(
				"title" => esc_html__('Miscellaneous', 'alex-stone'),
				"desc" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "info",
				),
			'seo_snippets' => array(
				"title" => esc_html__('SEO snippets', 'alex-stone'),
				"desc" => wp_kses_data( __('Add structured data markup to the single posts and pages', 'alex-stone') ),
				"std" => 0,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),
            'privacy_text' => array(
                "title" => esc_html__("Text with Privacy Policy link", 'alex-stone'),
                "desc"  => wp_kses_data( __("Specify text with Privacy Policy link for the checkbox 'I agree ...'", 'alex-stone') ),
                "std"   => wp_kses_data( __( 'I agree that my submitted data is being collected and stored.', 'alex-stone') ),
                "type"  => "text"
            ),
		
		
			// 'Header'
			'header' => array(
				"title" => esc_html__('Header', 'alex-stone'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 30,
				"type" => "section"
				),

			'header_style_info' => array(
				"title" => esc_html__('Header style', 'alex-stone'),
				"desc" => '',
				"type" => "info"
				),
			'header_style' => array(
				"title" => esc_html__('Header style', 'alex-stone'),
				"desc" => wp_kses_data( __('Select style to display the site header', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"std" => ALEX_STONE_THEME_FREE ? 'header-custom-sow-header-default' : 'header-custom-header-default',
				"options" => array(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'alex-stone'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"std" => 'default',
				"options" => array(),
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "switch"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'alex-stone'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"std" => 0,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwide', 'alex-stone'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"dependency" => array(
					'header_style' => array('header-default')
				),
				"std" => 1,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_phone' => array(
				"title" => esc_html__('Phone', 'alex-stone'),
				"desc" => wp_kses_data( __('Phone in default header', 'alex-stone') ),
				"dependency" => array(
					'header_style' => array('header-default'),
				),
				"std" => "",
				"type" => "text"
				),
			'title_image' => array(
				"title" => esc_html__('Image for title', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload image for title', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"dependency" => array(
					'header_style' => array('header-default')
				),
				"std" => '',
				"type" => "image"
				),

			'header_widgets_info' => array(
				"title" => esc_html__('Header widgets', 'alex-stone'),
				"desc" => wp_kses_data( __('Here you can place a widget slider, advertising banners, etc.', 'alex-stone') ),
				"type" => "info"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'alex-stone'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'alex-stone') ),
				),
				"std" => 'hide',
				"options" => array(),
				"type" => "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'alex-stone'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"dependency" => array(
					'header_style' => array('header-default'),
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => alex_stone_get_list_range(0,6),
				"type" => "select"
				),


			'menu_info' => array(
				"title" => esc_html__('Main menu', 'alex-stone'),
				"desc" => wp_kses_data( __('Select main menu style, position, color scheme and other parameters', 'alex-stone') ),
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'alex-stone'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'alex-stone')
				),
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "switch"
				),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'alex-stone'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 0,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'alex-stone'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'alex-stone'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'alex-stone') ),
				"std" => 1,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_image_info' => array(
				"title" => esc_html__('Header image', 'alex-stone'),
				"desc" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "info"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'alex-stone'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'alex-stone') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"std" => 0,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
				),



		
			// 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'alex-stone'),
				"desc" => wp_kses_data( __('Select set of widgets and columns number in the site footer', 'alex-stone') )
							. '<br>'
							. wp_kses_data( $msg_override ),
				"priority" => 50,
				"type" => "section"
				),
			'footer_style' => array(
				"title" => esc_html__('Footer style', 'alex-stone'),
				"desc" => wp_kses_data( __('Select style to display the site footer', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'alex-stone')
				),
				"std" => ALEX_STONE_THEME_FREE ? 'footer-custom-sow-footer-default' : 'footer-custom-footer-default',
				"options" => array(),
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'alex-stone'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'alex-stone')
				),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 'footer_widgets',
				"options" => array(),
				"type" => "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'alex-stone'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'alex-stone')
				),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'footer_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => alex_stone_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwide', 'alex-stone'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'alex-stone') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'alex-stone')
				),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'alex-stone'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'alex-stone') ),
				'refresh' => false,
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'alex-stone') ),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'logo_in_footer' => array(1)
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'alex-stone'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'alex-stone') ),
				"dependency" => array(
					'footer_style' => array('footer-default'),
					'logo_in_footer' => array(1),
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "image"
				),
			'socials_in_footer' => array(
				"title" => esc_html__('Show social icons', 'alex-stone'),
				"desc" => wp_kses_data( __('Show social icons in the footer (under logo or footer widgets)', 'alex-stone') ),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"std" => 1,
				"type" => "checkbox"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'alex-stone'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'alex-stone') ),
				"std" => esc_html__('ThemeREX &copy; {Y}. All rights reserved.', 'alex-stone'),
				"dependency" => array(
					'footer_style' => array('footer-default')
				),
				"refresh" => false,
				"type" => "textarea"
				),
			
		
		
			// 'Blog'
			'blog' => array(
				"title" => esc_html__('Blog', 'alex-stone'),
				"desc" => wp_kses_data( __('Options of the the blog archive', 'alex-stone') ),
				"priority" => 70,
				"type" => "panel",
				),
		
				// Blog - Posts page
				'blog_general' => array(
					"title" => esc_html__('Posts page', 'alex-stone'),
					"desc" => wp_kses_data( __('Style and components of the blog archive', 'alex-stone') ),
					"type" => "section",
					),
				'blog_general_info' => array(
					"title" => esc_html__('General settings', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'blog_style' => array(
					"title" => esc_html__('Blog style', 'alex-stone'),
					"desc" => '',
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' )
					),
					"std" => 'excerpt',
					"options" => array(),
					"type" => "select"
					),
				'first_post_large' => array(
					"title" => esc_html__('First post large', 'alex-stone'),
					"desc" => wp_kses_data( __('Make your first post stand out by making it bigger', 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('classic', 'masonry')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				"blog_content" => array( 
					"title" => esc_html__('Posts content', 'alex-stone'),
					"desc" => wp_kses_data( __("Display either post excerpts or the full post content", 'alex-stone') ),
					"std" => "excerpt",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"options" => array(
						'excerpt'	=> esc_html__('Excerpt',	'alex-stone'),
						'fullpost'	=> esc_html__('Full post',	'alex-stone')
					),
					"type" => "switch"
					),
				'excerpt_length' => array(
					"title" => esc_html__('Excerpt length', 'alex-stone'),
					"desc" => wp_kses_data( __("Length (in words) to generate excerpt from the post content. Attention! If the post excerpt is explicitly specified - it appears unchanged", 'alex-stone') ),
					"dependency" => array(
						'blog_style' => array('excerpt'),
						'blog_content' => array('excerpt')
					),
					"std" => 60,
					"type" => "text"
					),
				'blog_columns' => array(
					"title" => esc_html__('Blog columns', 'alex-stone'),
					"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'alex-stone') ),
					"std" => 2,
					"options" => alex_stone_get_list_range(2,4),
					"type" => "hidden"
					),
				'post_type' => array(
					"title" => esc_html__('Post type', 'alex-stone'),
					"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"linked" => 'parent_cat',
					"refresh" => false,
					"hidden" => true,
					"std" => 'post',
					"options" => array(),
					"type" => "select"
					),
				'parent_cat' => array(
					"title" => esc_html__('Category to show', 'alex-stone'),
					"desc" => wp_kses_data( __('Select category to show in the blog archive', 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"refresh" => false,
					"hidden" => true,
					"std" => '0',
					"options" => array(),
					"type" => "select"
					),
				'posts_per_page' => array(
					"title" => esc_html__('Posts per page', 'alex-stone'),
					"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"hidden" => true,
					"std" => '',
					"type" => "text"
					),
				"blog_pagination" => array( 
					"title" => esc_html__('Pagination style', 'alex-stone'),
					"desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"std" => "pages",
					"options" => array(
						'pages'	=> esc_html__("Page numbers", 'alex-stone'),
						'links'	=> esc_html__("Older/Newest", 'alex-stone'),
						'more'	=> esc_html__("Load more", 'alex-stone'),
						'infinite' => esc_html__("Infinite scroll", 'alex-stone')
					),
					"type" => "select"
					),
				'show_filters' => array(
					"title" => esc_html__('Show filters', 'alex-stone'),
					"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('portfolio', 'gallery')
					),
					"hidden" => true,
					"std" => 0,
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
					),
	
				'blog_sidebar_info' => array(
					"title" => esc_html__('Sidebar', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'sidebar_position_blog' => array(
					"title" => esc_html__('Sidebar position', 'alex-stone'),
					"desc" => wp_kses_data( __('Select position to show sidebar', 'alex-stone') ),
					"std" => 'right',
					"options" => array(),
					"type" => "switch"
					),
				'sidebar_widgets_blog' => array(
					"title" => esc_html__('Sidebar widgets', 'alex-stone'),
					"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'alex-stone') ),
					"dependency" => array(
						'sidebar_position_blog' => array('left', 'right')
					),
					"std" => 'sidebar_widgets',
					"options" => array(),
					"type" => "select"
					),
				'expand_content_blog' => array(
					"title" => esc_html__('Expand content', 'alex-stone'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'alex-stone') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
	
	
				'blog_widgets_info' => array(
					"title" => esc_html__('Additional widgets', 'alex-stone'),
					"desc" => '',
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "info",
					),
				'widgets_above_page_blog' => array(
					"title" => esc_html__('Widgets at the top of the page', 'alex-stone'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'alex-stone') ),
					"std" => 'hide',
					"options" => array(),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
					),
				'widgets_above_content_blog' => array(
					"title" => esc_html__('Widgets above the content', 'alex-stone'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'alex-stone') ),
					"std" => 'hide',
					"options" => array(),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_content_blog' => array(
					"title" => esc_html__('Widgets below the content', 'alex-stone'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'alex-stone') ),
					"std" => 'hide',
					"options" => array(),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_page_blog' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'alex-stone'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'alex-stone') ),
					"std" => 'hide',
					"options" => array(),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
					),

				'blog_advanced_info' => array(
					"title" => esc_html__('Advanced settings', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'no_image' => array(
					"title" => esc_html__('Image placeholder', 'alex-stone'),
					"desc" => wp_kses_data( __('Select or upload an image used as placeholder for posts without a featured image', 'alex-stone') ),
					"std" => '',
					"type" => "image"
					),
				'time_diff_before' => array(
					"title" => esc_html__('Easy Readable Date Format', 'alex-stone'),
					"desc" => wp_kses_data( __("For how many days to show the easy-readable date format (e.g. '3 days ago') instead of the standard publication date", 'alex-stone') ),
					"std" => 5,
					"type" => "text"
					),
				'sticky_style' => array(
					"title" => esc_html__('Sticky posts style', 'alex-stone'),
					"desc" => wp_kses_data( __('Select style of the sticky posts output', 'alex-stone') ),
					"std" => 'inherit',
					"options" => array(
						'inherit' => esc_html__('Decorated posts', 'alex-stone'),
						'columns' => esc_html__('Mini-cards',	'alex-stone')
					),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
					),
				"blog_animation" => array( 
					"title" => esc_html__('Animation for the posts', 'alex-stone'),
					"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' )
					),
					"std" => "none",
					"options" => array(),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
					),
				'meta_parts' => array(
					"title" => esc_html__('Post meta', 'alex-stone'),
					"desc" => wp_kses_data( __("If your blog page is created using the 'Blog archive' page template, set up the 'Post Meta' settings in the 'Theme Options' section of that page.", 'alex-stone') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' )
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=1|date=1|counters=1|author=0|share=0|edit=1',
					"options" => array(
						'categories' => esc_html__('Categories', 'alex-stone'),
						'date'		 => esc_html__('Post date', 'alex-stone'),
						'author'	 => esc_html__('Post author', 'alex-stone'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'alex-stone'),
						'share'		 => esc_html__('Share links', 'alex-stone'),
						'edit'		 => esc_html__('Edit link', 'alex-stone')
					),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checklist"
				),
				'counters' => array(
					"title" => esc_html__('Views, Likes and Comments', 'alex-stone'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'alex-stone') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"dependency" => array(
                        '#page_template' => array( 'blog.php' ),
                        '.editor-page-attributes__template select' => array( 'blog.php' )
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=1|likes=1|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'alex-stone'),
						'likes' => esc_html__('Likes', 'alex-stone'),
						'comments' => esc_html__('Comments', 'alex-stone')
					),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checklist"
				),

				
				// Blog - Single posts
				'blog_single' => array(
					"title" => esc_html__('Single posts', 'alex-stone'),
					"desc" => wp_kses_data( __('Settings of the single post', 'alex-stone') ),
					"type" => "section",
					),
				'hide_featured_on_single' => array(
					"title" => esc_html__('Hide featured image on the single post', 'alex-stone'),
					"desc" => wp_kses_data( __("Hide featured image on the single post's pages", 'alex-stone') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				'hide_sidebar_on_single' => array(
					"title" => esc_html__('Hide sidebar on the single post', 'alex-stone'),
					"desc" => wp_kses_data( __("Hide sidebar on the single post's pages", 'alex-stone') ),
					"std" => 0,
					"type" => "checkbox"
					),
				'show_post_meta' => array(
					"title" => esc_html__('Show post meta', 'alex-stone'),
					"desc" => wp_kses_data( __("Display block with post's meta: date, categories, counters, etc.", 'alex-stone') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'show_share_links' => array(
					"title" => esc_html__('Show share links', 'alex-stone'),
					"desc" => wp_kses_data( __("Display share links on the single post", 'alex-stone') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'show_author_info' => array(
					"title" => esc_html__('Show author info', 'alex-stone'),
					"desc" => wp_kses_data( __("Display block with information about post's author", 'alex-stone') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'blog_single_related_info' => array(
					"title" => esc_html__('Related posts', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'show_related_posts' => array(
					"title" => esc_html__('Show related posts', 'alex-stone'),
					"desc" => wp_kses_data( __("Show section 'Related posts' on the single post's pages", 'alex-stone') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'alex-stone')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				'related_posts' => array(
					"title" => esc_html__('Related posts', 'alex-stone'),
					"desc" => wp_kses_data( __('How many related posts should be displayed in the single post? If 0 - no related posts showed.', 'alex-stone') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => alex_stone_get_list_range(1,9),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
					),
				'related_columns' => array(
					"title" => esc_html__('Related columns', 'alex-stone'),
					"desc" => wp_kses_data( __('How many columns should be used to output related posts in the single page (from 2 to 4)?', 'alex-stone') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => alex_stone_get_list_range(2,2),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "switch"
					),
				'related_style' => array(
					"title" => esc_html__('Related posts style', 'alex-stone'),
					"desc" => wp_kses_data( __('Select style of the related posts output', 'alex-stone') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => alex_stone_get_list_styles(2,2),
					"type" => ALEX_STONE_THEME_FREE ? "hidden" : "switch"
					),
			'blog_end' => array(
				"type" => "panel_end",
				),
			
		
		
			// 'Colors'
			'panel_colors' => array(
				"title" => esc_html__('Colors', 'alex-stone'),
				"desc" => '',
				"priority" => 300,
				"type" => "section"
				),

			'color_schemes_info' => array(
				"title" => esc_html__('Color schemes', 'alex-stone'),
				"desc" => wp_kses_data( __('Color schemes for various parts of the site. "Inherit" means that this block is used the Site color scheme (the first parameter)', 'alex-stone') ),
				"type" => "info",
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'alex-stone'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'alex-stone')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'alex-stone'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'alex-stone')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'alex-stone'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'alex-stone')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Menu Color Scheme', 'alex-stone'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'alex-stone')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "switch"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'alex-stone'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'alex-stone')
				),
				"std" => 'dark',
				"options" => array(),
				"refresh" => false,
				"type" => "switch"
				),

			'color_scheme_editor_info' => array(
				"title" => esc_html__('Color scheme editor', 'alex-stone'),
				"desc" => wp_kses_data(__('Select color scheme to modify. Attention! Only those sections in the site will be changed which this scheme was assigned to', 'alex-stone') ),
				"type" => "info",
				),
			'scheme_storage' => array(
				"title" => esc_html__('Color scheme editor', 'alex-stone'),
				"desc" => '',
				"std" => '$alex_stone_get_scheme_storage',
				"refresh" => false,
				"colorpicker" => "tiny",
				"type" => "scheme_editor"
				),


			// 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'alex-stone'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'alex-stone') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'alex-stone')
				),
				"hidden" => true,
				"std" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'alex-stone'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'alex-stone') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'alex-stone')
				),
				"hidden" => true,
				"std" => '',
				"type" => ALEX_STONE_THEME_FREE ? "hidden" : "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"type" => "hidden",
				),

			'last_option' => array(
				"title" => '',
				"desc" => '',
				"std" => 1,
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		$fonts = array(
		
			// 'Fonts'
			'fonts' => array(
				"title" => esc_html__('Typography', 'alex-stone'),
				"desc" => '',
				"priority" => 200,
				"type" => "panel"
				),

			// Fonts - Load_fonts
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'alex-stone'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'alex-stone') )
						. '<br>'
						. wp_kses_data( __('<b>Attention!</b> Press "Refresh" button to reload preview area after the all fonts are changed', 'alex-stone') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'alex-stone'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'alex-stone') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'alex-stone') ),
				"class" => "alex_stone_column-1_3 alex_stone_new_row",
				"refresh" => false,
				"std" => '$alex_stone_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=alex_stone_get_theme_setting('max_load_fonts'); $i++) {
			if (alex_stone_get_value_gp('page') != 'theme_options') {
				$fonts["load_fonts-{$i}-info"] = array(
					"title" => esc_html(sprintf(esc_html__('Font %s', 'alex-stone'), $i)),
					"desc" => '',
					"type" => "info",
					);
			}
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'alex-stone'),
				"desc" => '',
				"class" => "alex_stone_column-1_3 alex_stone_new_row",
				"refresh" => false,
				"std" => '$alex_stone_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'alex-stone'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'alex-stone') )
							: '',
				"class" => "alex_stone_column-1_3",
				"refresh" => false,
				"std" => '$alex_stone_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'alex-stone'),
					'serif' => esc_html__('serif', 'alex-stone'),
					'sans-serif' => esc_html__('sans-serif', 'alex-stone'),
					'monospace' => esc_html__('monospace', 'alex-stone'),
					'cursive' => esc_html__('cursive', 'alex-stone'),
					'fantasy' => esc_html__('fantasy', 'alex-stone')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'alex-stone'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'alex-stone') )
								. '<br>'
								. wp_kses_data( __('<b>Attention!</b> Each weight and style increase download size! Specify only used weights and styles.', 'alex-stone') )
							: '',
				"class" => "alex_stone_column-1_3",
				"refresh" => false,
				"std" => '$alex_stone_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Fonts - H1..6, P, Info, Menu, etc.
		$theme_fonts = alex_stone_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								: esc_html(sprintf(esc_html__('%s settings', 'alex-stone'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								: wp_kses( sprintf(__('Font settings of the "%s" tag.', 'alex-stone'), $tag), 'alex_stone_kses_content' ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = array();
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'alex-stone'),
						'100' => esc_html__('100 (Light)', 'alex-stone'), 
						'200' => esc_html__('200 (Light)', 'alex-stone'), 
						'300' => esc_html__('300 (Thin)',  'alex-stone'),
						'400' => esc_html__('400 (Normal)', 'alex-stone'),
						'500' => esc_html__('500 (Semibold)', 'alex-stone'),
						'600' => esc_html__('600 (Semibold)', 'alex-stone'),
						'700' => esc_html__('700 (Bold)', 'alex-stone'),
						'800' => esc_html__('800 (Black)', 'alex-stone'),
						'900' => esc_html__('900 (Black)', 'alex-stone')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'alex-stone'),
						'normal' => esc_html__('Normal', 'alex-stone'), 
						'italic' => esc_html__('Italic', 'alex-stone')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'alex-stone'),
						'none' => esc_html__('None', 'alex-stone'), 
						'underline' => esc_html__('Underline', 'alex-stone'),
						'overline' => esc_html__('Overline', 'alex-stone'),
						'line-through' => esc_html__('Line-through', 'alex-stone')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'alex-stone'),
						'none' => esc_html__('None', 'alex-stone'), 
						'uppercase' => esc_html__('Uppercase', 'alex-stone'),
						'lowercase' => esc_html__('Lowercase', 'alex-stone'),
						'capitalize' => esc_html__('Capitalize', 'alex-stone')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"class" => "alex_stone_column-1_5",
					"refresh" => false,
					"std" => '$alex_stone_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters to Theme Options
		alex_stone_storage_set_array_before('options', 'panel_colors', $fonts);

		// Add Header Video if WP version < 4.7
		if (!function_exists('get_header_video_url')) {
			alex_stone_storage_set_array_after('options', 'header_image_override', 'header_video', array(
				"title" => esc_html__('Header video', 'alex-stone'),
				"desc" => wp_kses_data( __("Select video to use it as background for the header", 'alex-stone') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'alex-stone')
				),
				"std" => '',
				"type" => "video"
				)
			);
		}
	}
}


// Returns a list of options that can be overridden for CPT
if (!function_exists('alex_stone_options_get_list_cpt_options')) {
	function alex_stone_options_get_list_cpt_options($cpt, $title='') {
		if (empty($title)) $title = ucfirst($cpt);
		return array(
					"header_info_{$cpt}" => array(
						"title" => esc_html__('Header', 'alex-stone'),
						"desc" => '',
						"type" => "info",
						),
					"header_style_{$cpt}" => array(
						"title" => esc_html__('Header style', 'alex-stone'),
						"desc" => wp_kses_data( sprintf(__('Select style to display the site header on the %s pages', 'alex-stone'), $title) ),
						"std" => 'inherit',
						"options" => array(),
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
						),
					"header_position_{$cpt}" => array(
						"title" => esc_html__('Header position', 'alex-stone'),
						"desc" => wp_kses_data( sprintf(__('Select position to display the site header on the %s pages', 'alex-stone'), $title) ),
						"std" => 'inherit',
						"options" => array(),
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "switch"
						),
					"header_image_override_{$cpt}" => array(
						"title" => esc_html__('Header image override', 'alex-stone'),
						"desc" => wp_kses_data( __("Allow override the header image with the post's featured image", 'alex-stone') ),
						"std" => 0,
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "checkbox"
						),
					"header_widgets_{$cpt}" => array(
						"title" => esc_html__('Header widgets', 'alex-stone'),
						"desc" => wp_kses_data( sprintf(__('Select set of widgets to show in the header on the %s pages', 'alex-stone'), $title) ),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
						
					"sidebar_info_{$cpt}" => array(
						"title" => esc_html__('Sidebar', 'alex-stone'),
						"desc" => '',
						"type" => "info",
						),
					"sidebar_position_{$cpt}" => array(
						"title" => esc_html__('Sidebar position', 'alex-stone'),
						"desc" => wp_kses_data( sprintf(__('Select position to show sidebar on the %s pages', 'alex-stone'), $title) ),
						"refresh" => false,
						"std" => 'left',
						"options" => array(),
						"type" => "switch"
						),
					"sidebar_widgets_{$cpt}" => array(
						"title" => esc_html__('Sidebar widgets', 'alex-stone'),
						"desc" => wp_kses_data( sprintf(__('Select sidebar to show on the %s pages', 'alex-stone'), $title) ),
						"dependency" => array(
							"sidebar_position_{$cpt}" => array('left', 'right')
						),
						"std" => 'hide',
						"options" => array(),
						"type" => "select"
						),
					"hide_sidebar_on_single_{$cpt}" => array(
						"title" => esc_html__('Hide sidebar on the single pages', 'alex-stone'),
						"desc" => wp_kses_data( __("Hide sidebar on the single page", 'alex-stone') ),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"footer_info_{$cpt}" => array(
						"title" => esc_html__('Footer', 'alex-stone'),
						"desc" => '',
						"type" => "info",
						),
					'footer_style_{$cpt}' => array(
						"title" => esc_html__('Footer style', 'alex-stone'),
						"desc" => wp_kses_data( __('Select style to display the site footer', 'alex-stone') ),
						"std" => 'inherit',
						"options" => array(),
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
						),
					"footer_widgets_{$cpt}" => array(
						"title" => esc_html__('Footer widgets', 'alex-stone'),
						"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'alex-stone') ),
						"std" => 'footer_widgets',
						"options" => array(),
						"type" => "select"
						),
					"footer_columns_{$cpt}" => array(
						"title" => esc_html__('Footer columns', 'alex-stone'),
						"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'alex-stone') ),
						"dependency" => array(
							"footer_widgets_{$cpt}" => array('^hide')
						),
						"std" => 0,
						"options" => alex_stone_get_list_range(0,6),
						"type" => "select"
						),
					"footer_wide_{$cpt}" => array(
						"title" => esc_html__('Footer fullwide', 'alex-stone'),
						"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'alex-stone') ),
						"std" => 0,
						"type" => "checkbox"
						),
						
					"widgets_info_{$cpt}" => array(
						"title" => esc_html__('Additional panels', 'alex-stone'),
						"desc" => '',
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "info",
						),
					"widgets_above_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the top of the page', 'alex-stone'),
						"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'alex-stone') ),
						"std" => 'hide',
						"options" => array(),
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
						),
					"widgets_above_content_{$cpt}" => array(
						"title" => esc_html__('Widgets above the content', 'alex-stone'),
						"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'alex-stone') ),
						"std" => 'hide',
						"options" => array(),
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_content_{$cpt}" => array(
						"title" => esc_html__('Widgets below the content', 'alex-stone'),
						"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'alex-stone') ),
						"std" => 'hide',
						"options" => array(),
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
						),
					"widgets_below_page_{$cpt}" => array(
						"title" => esc_html__('Widgets at the bottom of the page', 'alex-stone'),
						"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'alex-stone') ),
						"std" => 'hide',
						"options" => array(),
						"type" => ALEX_STONE_THEME_FREE ? "hidden" : "select"
						)
					);
	}
}


// Return lists with choises when its need in the admin mode
if (!function_exists('alex_stone_options_get_list_choises')) {
	add_filter('alex_stone_filter_options_get_list_choises', 'alex_stone_options_get_list_choises', 10, 2);
	function alex_stone_options_get_list_choises($list, $id) {
		if (is_array($list) && count($list)==0) {
			if (strpos($id, 'header_style')===0)
				$list = alex_stone_get_list_header_styles(strpos($id, 'header_style_')===0);
			else if (strpos($id, 'header_position')===0)
				$list = alex_stone_get_list_header_positions(strpos($id, 'header_position_')===0);
			else if (strpos($id, 'header_widgets')===0)
				$list = alex_stone_get_list_sidebars(strpos($id, 'header_widgets_')===0, true);
			else if (substr($id, -7) == '_scheme')
				$list = alex_stone_get_list_schemes($id!='color_scheme');
			else if (strpos($id, 'sidebar_widgets')===0)
				$list = alex_stone_get_list_sidebars(strpos($id, 'sidebar_widgets_')===0, true);
			else if (strpos($id, 'sidebar_position')===0)
				$list = alex_stone_get_list_sidebars_positions(strpos($id, 'sidebar_position_')===0);
			else if (strpos($id, 'widgets_above_page')===0)
				$list = alex_stone_get_list_sidebars(strpos($id, 'widgets_above_page_')===0, true);
			else if (strpos($id, 'widgets_above_content')===0)
				$list = alex_stone_get_list_sidebars(strpos($id, 'widgets_above_content_')===0, true);
			else if (strpos($id, 'widgets_below_page')===0)
				$list = alex_stone_get_list_sidebars(strpos($id, 'widgets_below_page_')===0, true);
			else if (strpos($id, 'widgets_below_content')===0)
				$list = alex_stone_get_list_sidebars(strpos($id, 'widgets_below_content_')===0, true);
			else if (strpos($id, 'footer_style')===0)
				$list = alex_stone_get_list_footer_styles(strpos($id, 'footer_style_')===0);
			else if (strpos($id, 'footer_widgets')===0)
				$list = alex_stone_get_list_sidebars(strpos($id, 'footer_widgets_')===0, true);
			else if (strpos($id, 'blog_style')===0)
				$list = alex_stone_get_list_blog_styles(strpos($id, 'blog_style_')===0);
			else if (strpos($id, 'post_type')===0)
				$list = alex_stone_get_list_posts_types();
			else if (strpos($id, 'parent_cat')===0)
				$list = alex_stone_array_merge(array(0 => esc_html__('- Select category -', 'alex-stone')), alex_stone_get_list_categories());
			else if (strpos($id, 'blog_animation')===0)
				$list = alex_stone_get_list_animations_in();
			else if ($id == 'color_scheme_editor')
				$list = alex_stone_get_list_schemes();
			else if (strpos($id, '_font-family') > 0)
				$list = alex_stone_get_list_load_fonts(true);
		}
		return $list;
	}
}
?>