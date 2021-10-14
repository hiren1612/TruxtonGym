<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('alex_stone_woocommerce_theme_setup1')) {
	add_action( 'after_setup_theme', 'alex_stone_woocommerce_theme_setup1', 1 );
	function alex_stone_woocommerce_theme_setup1() {

		add_theme_support( 'woocommerce' );

		// Next setting from the WooCommerce 3.0+ enable built-in image zoom on the single product page
		add_theme_support( 'wc-product-gallery-zoom' );

		// Next setting from the WooCommerce 3.0+ enable built-in image slider on the single product page
		add_theme_support( 'wc-product-gallery-slider' ); 

		// Next setting from the WooCommerce 3.0+ enable built-in image lightbox on the single product page
		add_theme_support( 'wc-product-gallery-lightbox' );

		add_filter( 'alex_stone_filter_list_sidebars', 	'alex_stone_woocommerce_list_sidebars' );
		add_filter( 'alex_stone_filter_list_posts_types',	'alex_stone_woocommerce_list_post_types');

        // Detect if WooCommerce support 'Product Grid' feature
        $product_grid = alex_stone_exists_woocommerce() && function_exists( 'wc_get_theme_support' ) ? wc_get_theme_support( 'product_grid' ) : false;
        add_theme_support( 'wc-product-grid-enable', isset( $product_grid['min_columns'] ) && isset( $product_grid['max_columns'] ) );
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('alex_stone_woocommerce_theme_setup3')) {
	add_action( 'after_setup_theme', 'alex_stone_woocommerce_theme_setup3', 3 );
	function alex_stone_woocommerce_theme_setup3() {
		if (alex_stone_exists_woocommerce()) {
		
			// Section 'WooCommerce'
			alex_stone_storage_set_array_before('options', 'fonts', array_merge(
				array(
					'shop' => array(
						"title" => esc_html__('Shop', 'alex-stone'),
						"desc" => wp_kses_data( __('Select parameters to display the shop pages', 'alex-stone') ),
						"priority" => 80,
						"type" => "section"
						),

					'products_info_shop' => array(
						"title" => esc_html__('Products list', 'alex-stone'),
						"desc" => '',
						"type" => "info",
						),
					'posts_per_page_shop' => array(
						"title" => esc_html__('Products per page', 'alex-stone'),
						"desc" => wp_kses_data( __('How many products should be displayed on the shop page. If empty - use global value from the menu Settings - Reading', 'alex-stone') ),
						"std" => '',
						"type" => "text"
						),
					'blog_columns_shop' => array(
						"title" => esc_html__('Shop loop columns', 'alex-stone'),
						"desc" => wp_kses_data( __('How many columns should be used in the shop loop (from 2 to 4)?', 'alex-stone') ),
						"std" => 2,
						"options" => alex_stone_get_list_range(2,4),
						"type" => "hidden"
						),
					'shop_mode' => array(
						"title" => esc_html__('Shop mode', 'alex-stone'),
						"desc" => wp_kses_data( __('Select style for the products list', 'alex-stone') ),
						"std" => 'thumbs',
						"options" => array(
							'thumbs'=> esc_html__('Thumbnails', 'alex-stone'),
							'list'	=> esc_html__('List', 'alex-stone'),
						),
						"type" => "select"
						),
					'shop_hover' => array(
						"title" => esc_html__('Hover style', 'alex-stone'),
						"desc" => wp_kses_data( __('Hover style on the products in the shop archive', 'alex-stone') ),
						"std" => 'shop',
						"options" => apply_filters('alex_stone_filter_shop_hover', array(
							'none' => esc_html__('None', 'alex-stone'),
							'shop' => esc_html__('Icons', 'alex-stone')
						)),
						"type" => "select"
						),

					'single_info_shop' => array(
						"title" => esc_html__('Single product', 'alex-stone'),
						"desc" => '',
						"type" => "info",
						),
					'stretch_tabs_area' => array(
						"title" => esc_html__('Stretch tabs area', 'alex-stone'),
						"desc" => wp_kses_data( __('Stretch area with tabs on the single product to the screen width if the sidebar is hidden', 'alex-stone') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'show_related_posts_shop' => array(
						"title" => esc_html__('Show related products', 'alex-stone'),
						"desc" => wp_kses_data( __("Show section 'Related products' on the single product page", 'alex-stone') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_shop' => array(
						"title" => esc_html__('Related products', 'alex-stone'),
						"desc" => wp_kses_data( __('How many related products should be displayed on the single product page?', 'alex-stone') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => alex_stone_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_shop' => array(
						"title" => esc_html__('Related columns', 'alex-stone'),
						"desc" => wp_kses_data( __('How many columns should be used to output related products on the single product page?', 'alex-stone') ),
						"dependency" => array(
							'show_related_posts_shop' => array(1)
						),
						"std" => 3,
						"options" => alex_stone_get_list_range(1,4),
						"type" => "select"
						)
				),
				alex_stone_options_get_list_cpt_options('shop')
			));
		}
	}
}


// Add section 'Products' to the Front Page option
if (!function_exists('alex_stone_woocommerce_front_page_options')) {
	if (!ALEX_STONE_THEME_FREE) add_filter( 'alex_stone_filter_front_page_options', 'alex_stone_woocommerce_front_page_options' );
	function alex_stone_woocommerce_front_page_options($options) {
		if (alex_stone_exists_woocommerce()) {

			$options['front_page_sections']['std'] .= (!empty($options['front_page_sections']['std']) ? '|' : '') . 'woocommerce=1';
			$options['front_page_sections']['options'] = array_merge($options['front_page_sections']['options'], 
																	array(
																		'woocommerce' => esc_html__('Products', 'alex-stone')
																		)
																	);
			$options = array_merge($options, array(
			
				// Front Page Sections - WooCommerce
				'front_page_woocommerce' => array(
					"title" => esc_html__('Products', 'alex-stone'),
					"desc" => '',
					"priority" => 200,
					"type" => "section",
					),
				'front_page_woocommerce_layout_info' => array(
					"title" => esc_html__('Layout', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_fullheight' => array(
					"title" => esc_html__('Full height', 'alex-stone'),
					"desc" => wp_kses_data( __('Stretch this section to the window height', 'alex-stone') ),
					"std" => 0,
					"refresh" => false,
					"type" => "checkbox"
					),
				'front_page_woocommerce_paddings' => array(
					"title" => esc_html__('Paddings', 'alex-stone'),
					"desc" => wp_kses_data( __('Select paddings inside this section', 'alex-stone') ),
					"std" => 'medium',
					"options" => alex_stone_get_list_paddings(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_heading_info' => array(
					"title" => esc_html__('Title', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_caption' => array(
					"title" => esc_html__('Section title', 'alex-stone'),
					"desc" => '',
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'alex-stone')),
					"type" => "text"
					),
				'front_page_woocommerce_description' => array(
					"title" => esc_html__('Description', 'alex-stone'),
					"desc" => wp_kses_data( __("Short description after the section's title", 'alex-stone') ),
					"refresh" => false,
					"std" => wp_kses_data(__('This text can be changed in the section "Products"', 'alex-stone')),
					"type" => "textarea"
					),
				'front_page_woocommerce_products_info' => array(
					"title" => esc_html__('Products parameters', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_products' => array(
					"title" => esc_html__('Type of the products', 'alex-stone'),
					"desc" => '',
					"std" => 'products',
					"options" => array(
									'recent_products' => esc_html__('Recent products', 'alex-stone'),
									'featured_products' => esc_html__('Featured products', 'alex-stone'),
									'top_rated_products' => esc_html__('Top rated products', 'alex-stone'),
									'sale_products' => esc_html__('Sale products', 'alex-stone'),
									'best_selling_products' => esc_html__('Best selling products', 'alex-stone'),
									'product_category' => esc_html__('Products from categories', 'alex-stone'),
									'products' => esc_html__('Products by IDs', 'alex-stone')
									),
					"type" => "select"
					),
				'front_page_woocommerce_products_categories' => array(
					"title" => esc_html__('Categories', 'alex-stone'),
					"desc" => esc_html__('Comma separated category slugs. Used only with "Products from categories"', 'alex-stone'),
					"dependency" => array(
						'front_page_woocommerce_products' => array('product_category')
					),
					"std" => '',
					"type" => "text"
					),
				'front_page_woocommerce_products_per_page' => array(
					"title" => esc_html__('Per page', 'alex-stone'),
					"desc" => wp_kses_data( __('How many products will be displayed on the page. Attention! For "Products by IDs" specify comma separated list of the IDs', 'alex-stone') ),
					"std" => 3,
					"type" => "text"
					),
				'front_page_woocommerce_products_columns' => array(
					"title" => esc_html__('Columns', 'alex-stone'),
					"desc" => wp_kses_data( __("How many columns will be used", 'alex-stone') ),
					"std" => 3,
					"type" => "text"
					),
				'front_page_woocommerce_products_orderby' => array(
					"title" => esc_html__('Order by', 'alex-stone'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'alex-stone') ),
					"std" => 'date',
					"options" => array(
									'date' => esc_html__('Date', 'alex-stone'),
									'title' => esc_html__('Title', 'alex-stone')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_products_order' => array(
					"title" => esc_html__('Order', 'alex-stone'),
					"desc" => wp_kses_data( __("Not used with Best selling products", 'alex-stone') ),
					"std" => 'desc',
					"options" => array(
									'asc' => esc_html__('Ascending', 'alex-stone'),
									'desc' => esc_html__('Descending', 'alex-stone')
									),
					"type" => "switch"
					),
				'front_page_woocommerce_color_info' => array(
					"title" => esc_html__('Colors and images', 'alex-stone'),
					"desc" => '',
					"type" => "info",
					),
				'front_page_woocommerce_scheme' => array(
					"title" => esc_html__('Color scheme', 'alex-stone'),
					"desc" => wp_kses_data( __('Color scheme for this section', 'alex-stone') ),
					"std" => 'inherit',
					"options" => array(),
					"refresh" => false,
					"type" => "switch"
					),
				'front_page_woocommerce_bg_image' => array(
					"title" => esc_html__('Background image', 'alex-stone'),
					"desc" => wp_kses_data( __('Select or upload background image for this section', 'alex-stone') ),
					"refresh" => '.front_page_section_woocommerce',
					"refresh_wrapper" => true,
					"std" => '',
					"type" => "image"
					),
				'front_page_woocommerce_bg_color' => array(
					"title" => esc_html__('Background color', 'alex-stone'),
					"desc" => wp_kses_data( __('Background color for this section', 'alex-stone') ),
					"std" => '',
					"refresh" => false,
					"type" => "color"
					),
				'front_page_woocommerce_bg_mask' => array(
					"title" => esc_html__('Background mask', 'alex-stone'),
					"desc" => wp_kses_data( __('Use Background color as section mask with specified opacity. If 0 - mask is not used', 'alex-stone') ),
					"std" => 1,
					"max" => 1,
					"step" => 0.1,
					"refresh" => false,
					"type" => "slider"
					),
				'front_page_woocommerce_anchor_info' => array(
					"title" => esc_html__('Anchor', 'alex-stone'),
					"desc" => wp_kses_data( __('You can select icon and/or specify a text to create anchor for this section and show it in the side menu (if selected in the section "Header - Menu".', 'alex-stone'))
								. '<br>'
								. wp_kses_data(__('Attention! Anchors available only if plugin "ThemeREX Addons is installed and activated!', 'alex-stone')),
					"type" => "info",
					),
				'front_page_woocommerce_anchor_icon' => array(
					"title" => esc_html__('Anchor icon', 'alex-stone'),
					"desc" => '',
					"std" => '',
					"type" => "icon"
					),
				'front_page_woocommerce_anchor_text' => array(
					"title" => esc_html__('Anchor text', 'alex-stone'),
					"desc" => '',
					"std" => '',
					"type" => "text"
					)
			));
		}
		return $options;
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_woocommerce_theme_setup9')) {
	add_action( 'after_setup_theme', 'alex_stone_woocommerce_theme_setup9', 9 );
	function alex_stone_woocommerce_theme_setup9() {
		
		if (alex_stone_exists_woocommerce()) {
			add_action( 'wp_enqueue_scripts', 								'alex_stone_woocommerce_frontend_scripts', 1100 );
			add_filter( 'alex_stone_filter_merge_styles',						'alex_stone_woocommerce_merge_styles' );
			add_filter( 'alex_stone_filter_merge_scripts',						'alex_stone_woocommerce_merge_scripts');
			add_filter( 'alex_stone_filter_get_post_info',		 				'alex_stone_woocommerce_get_post_info');
			add_filter( 'alex_stone_filter_post_type_taxonomy',				'alex_stone_woocommerce_post_type_taxonomy', 10, 2 );
			if (!is_admin()) {
				add_filter( 'alex_stone_filter_detect_blog_mode',				'alex_stone_woocommerce_detect_blog_mode');
				add_filter( 'alex_stone_filter_get_post_categories', 			'alex_stone_woocommerce_get_post_categories');
				add_filter( 'alex_stone_filter_allow_override_header_image',	'alex_stone_woocommerce_allow_override_header_image');
				add_filter( 'alex_stone_filter_get_blog_title',				'alex_stone_woocommerce_get_blog_title');
				add_action( 'alex_stone_action_before_post_meta',				'alex_stone_woocommerce_action_before_post_meta');
				add_action( 'pre_get_posts',								'alex_stone_woocommerce_pre_get_posts');
				add_filter( 'alex_stone_filter_localize_script',				'alex_stone_woocommerce_localize_script');
			}
		}
		if (is_admin()) {
			add_filter( 'alex_stone_filter_tgmpa_required_plugins',			'alex_stone_woocommerce_tgmpa_required_plugins' );
		}

		// Add wrappers and classes to the standard WooCommerce output
		if (alex_stone_exists_woocommerce()) {

			// Remove WOOC sidebar
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);

			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);
			
			// Open main content wrapper - <article>
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'alex_stone_woocommerce_wrapper_start', 10);
			// Close main content wrapper - </article>
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'alex_stone_woocommerce_wrapper_end', 10);

			// Close header section
			add_action(    'woocommerce_archive_description',			'alex_stone_woocommerce_archive_description', 15 );

			// Add theme specific search form
			add_filter(    'get_product_search_form',					'alex_stone_woocommerce_get_product_search_form' );

			// Change text on 'Add to cart' button
			add_filter(    'woocommerce_product_add_to_cart_text',		'alex_stone_woocommerce_add_to_cart_text' );
			add_filter(    'woocommerce_product_single_add_to_cart_text','alex_stone_woocommerce_add_to_cart_text' );

			// Add list mode buttons
			add_action(    'woocommerce_before_shop_loop', 				'alex_stone_woocommerce_before_shop_loop', 10 );

			// Set columns number for the products loop
            if ( ! get_theme_support( 'wc-product-grid-enable' ) ) {
                add_filter('loop_shop_columns', 'alex_stone_woocommerce_loop_shop_columns');
                add_filter('post_class', 'alex_stone_woocommerce_loop_shop_columns_class');
                add_filter('product_cat_class', 'alex_stone_woocommerce_loop_shop_columns_class', 10, 3);
            }
			// Open product/category item wrapper
			add_action(    'woocommerce_before_subcategory_title',		'alex_stone_woocommerce_item_wrapper_start', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'alex_stone_woocommerce_item_wrapper_start', 9 );
			// Close featured image wrapper and open title wrapper
			add_action(    'woocommerce_before_subcategory_title',		'alex_stone_woocommerce_title_wrapper_start', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'alex_stone_woocommerce_title_wrapper_start', 20 );

			// Wrap product title into link
			add_action(    'the_title',									'alex_stone_woocommerce_the_title');
			// Wrap category title into link
			add_action(		'woocommerce_shop_loop_subcategory_title',  'alex_stone_woocommerce_shop_loop_subcategory_title', 9, 1);

			// Close title wrapper and add description in the list mode
			add_action(    'woocommerce_after_shop_loop_item_title',	'alex_stone_woocommerce_title_wrapper_end', 7);
			add_action(    'woocommerce_after_subcategory_title',		'alex_stone_woocommerce_title_wrapper_end2', 10 );
			// Close product/category item wrapper
			add_action(    'woocommerce_after_subcategory',				'alex_stone_woocommerce_item_wrapper_end', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'alex_stone_woocommerce_item_wrapper_end', 20 );

			// Add product ID into product meta section (after categories and tags)
			add_action(    'woocommerce_product_meta_end',				'alex_stone_woocommerce_show_product_id', 10);
			
			// Set columns number for the product's thumbnails
			add_filter(    'woocommerce_product_thumbnails_columns',	'alex_stone_woocommerce_product_thumbnails_columns' );

			// Decorate price
			add_filter(    'woocommerce_get_price_html',				'alex_stone_woocommerce_get_price_html' );

			remove_action('woocommerce_single_product_summary',			'woocommerce_template_single_price', 10);
			add_action('woocommerce_single_product_summary',			'woocommerce_template_single_price', 25);
	
			// Detect current shop mode
			if (!is_admin()) {
				$shop_mode = alex_stone_get_value_gpc('alex_stone_shop_mode');
				if (empty($shop_mode) && alex_stone_check_theme_option('shop_mode'))
					$shop_mode = alex_stone_get_theme_option('shop_mode');
				if (empty($shop_mode))
					$shop_mode = 'thumbs';
				alex_stone_storage_set('shop_mode', $shop_mode);
			}
		}
	}
}

// Theme init priorities:
// Action 'wp'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)
if (!function_exists('alex_stone_woocommerce_theme_setup_wp')) {
	add_action( 'wp', 'alex_stone_woocommerce_theme_setup_wp' );
	function alex_stone_woocommerce_theme_setup_wp() {
		if (alex_stone_exists_woocommerce()) {
			// Set columns number for the related products
			if ((int) alex_stone_get_theme_option('show_related_posts') == 0 || (int) alex_stone_get_theme_option('related_posts') == 0) {
				remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
			} else {
				add_filter(    'woocommerce_output_related_products_args',	'alex_stone_woocommerce_output_related_products_args' );
				add_filter(    'woocommerce_related_products_columns',		'alex_stone_woocommerce_related_products_columns' );
			}
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_woocommerce_tgmpa_required_plugins' ) ) {
	
	function alex_stone_woocommerce_tgmpa_required_plugins($list=array()) {
		if (alex_stone_storage_isset('required_plugins', 'woocommerce')) {
			$list[] = array(
					'name' 		=> alex_stone_storage_get_array('required_plugins', 'woocommerce'),
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);
		}
		return $list;
	}
}


// Check if WooCommerce installed and activated
if ( !function_exists( 'alex_stone_exists_woocommerce' ) ) {
	function alex_stone_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'alex_stone_is_woocommerce_page' ) ) {
	function alex_stone_is_woocommerce_page() {
		$rez = false;
		if (alex_stone_exists_woocommerce())
			$rez = is_woocommerce() || is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		return $rez;
	}
}

// Detect current blog mode
if ( !function_exists( 'alex_stone_woocommerce_detect_blog_mode' ) ) {
	
	function alex_stone_woocommerce_detect_blog_mode($mode='') {
		if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy())
			$mode = 'shop';
		else if (is_product() || is_cart() || is_checkout() || is_account_page())
			$mode = 'shop';
		return $mode;
	}
}

// Return current page title
if ( !function_exists( 'alex_stone_woocommerce_get_blog_title' ) ) {
	
	function alex_stone_woocommerce_get_blog_title($title='') {
		if (!alex_stone_exists_trx_addons() && alex_stone_exists_woocommerce() && alex_stone_is_woocommerce_page() && is_shop()) {
			$id = alex_stone_woocommerce_get_shop_page_id();
			$title = $id ? get_the_title($id) : esc_html__('Shop', 'alex-stone');
		}
		return $title;
	}
}


// Return taxonomy for current post type
if ( !function_exists( 'alex_stone_woocommerce_post_type_taxonomy' ) ) {
	
	function alex_stone_woocommerce_post_type_taxonomy($tax='', $post_type='') {
		if ($post_type == 'product')
			$tax = 'product_cat';
		return $tax;
	}
}

// Return true if page title section is allowed
if ( !function_exists( 'alex_stone_woocommerce_allow_override_header_image' ) ) {
	
	function alex_stone_woocommerce_allow_override_header_image($allow=true) {
		return is_product() ? false : $allow;
	}
}

// Return shop page ID
if ( !function_exists( 'alex_stone_woocommerce_get_shop_page_id' ) ) {
	function alex_stone_woocommerce_get_shop_page_id() {
		return get_option('woocommerce_shop_page_id');
	}
}

// Return shop page link
if ( !function_exists( 'alex_stone_woocommerce_get_shop_page_link' ) ) {
	function alex_stone_woocommerce_get_shop_page_link() {
		$url = '';
		$id = alex_stone_woocommerce_get_shop_page_id();
		if ($id) $url = get_permalink($id);
		return $url;
	}
}

// Show categories of the current product
if ( !function_exists( 'alex_stone_woocommerce_get_post_categories' ) ) {
	
	function alex_stone_woocommerce_get_post_categories($cats='') {
		if (get_post_type()=='product') {
			$cats = alex_stone_get_post_terms(', ', get_the_ID(), 'product_cat');
		}
		return $cats;
	}
}

// Add 'product' to the list of the supported post-types
if ( !function_exists( 'alex_stone_woocommerce_list_post_types' ) ) {
	
	function alex_stone_woocommerce_list_post_types($list=array()) {
		$list['product'] = esc_html__('Products', 'alex-stone');
		return $list;
	}
}

// Show price of the current product in the widgets and search results
if ( !function_exists( 'alex_stone_woocommerce_get_post_info' ) ) {
	
	function alex_stone_woocommerce_get_post_info($post_info='') {
		if (get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				$post_info = '<div class="post_price product_price price">' . trim($price_html) . '</div>' . $post_info;
			}
		}
		return $post_info;
	}
}

// Show price of the current product in the search results streampage
if ( !function_exists( 'alex_stone_woocommerce_action_before_post_meta' ) ) {
	
	function alex_stone_woocommerce_action_before_post_meta() {
		if (!is_single() && get_post_type()=='product') {
			global $product;
			if ( $price_html = $product->get_price_html() ) {
				?><div class="post_price product_price price"><?php alex_stone_show_layout($price_html); ?></div><?php
			}
		}
	}
}
	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'alex_stone_woocommerce_frontend_scripts' ) ) {
	
	function alex_stone_woocommerce_frontend_scripts() {
			if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/woocommerce/woocommerce.css')!='')
				wp_enqueue_style( 'alex-stone-woocommerce',  alex_stone_get_file_url('plugins/woocommerce/woocommerce.css'), array(), null );
			if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/woocommerce/woocommerce.js')!='')
				wp_enqueue_script( 'alex-stone-woocommerce', alex_stone_get_file_url('plugins/woocommerce/woocommerce.js'), array('jquery'), null, true );
	}
}
	
// Merge custom styles
if ( !function_exists( 'alex_stone_woocommerce_merge_styles' ) ) {
	
	function alex_stone_woocommerce_merge_styles($list) {
		$list[] = 'plugins/woocommerce/woocommerce.css';
		return $list;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'alex_stone_woocommerce_merge_scripts' ) ) {
	
	function alex_stone_woocommerce_merge_scripts($list) {
		$list[] = 'plugins/woocommerce/woocommerce.js';
		return $list;
	}
}



// Add WooCommerce specific items into lists
//------------------------------------------------------------------------

// Add sidebar
if ( !function_exists( 'alex_stone_woocommerce_list_sidebars' ) ) {
	
	function alex_stone_woocommerce_list_sidebars($list=array()) {
		$list['woocommerce_widgets'] = array(
											'name' => esc_html__('WooCommerce Widgets', 'alex-stone'),
											'description' => esc_html__('Widgets to be shown on the WooCommerce pages', 'alex-stone')
											);
		return $list;
	}
}




// Decorate WooCommerce output: Loop
//------------------------------------------------------------------------

// Add query vars to set products per page
if (!function_exists('alex_stone_woocommerce_pre_get_posts')) {
	
	function alex_stone_woocommerce_pre_get_posts($query) {
		if (!$query->is_main_query()) return;
		if ($query->get('post_type') == 'product') {
			$ppp = get_theme_mod('posts_per_page_shop', 0);
			if ($ppp > 0)
				$query->set('posts_per_page', $ppp);
		}
	}
}


// Before main content
if ( !function_exists( 'alex_stone_woocommerce_wrapper_start' ) ) {
	
	function alex_stone_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item_single post_type_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !alex_stone_storage_empty('shop_mode') ? alex_stone_storage_get('shop_mode') : 'thumbs'; ?>">
				<div class="list_products_header">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'alex_stone_woocommerce_wrapper_end' ) ) {
	
	function alex_stone_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article><!-- /.post_item_single -->
			<?php
		} else {
			?>
			</div><!-- /.list_products -->
			<?php
		}
	}
}

// Close header section
if ( !function_exists( 'alex_stone_woocommerce_archive_description' ) ) {
	
	function alex_stone_woocommerce_archive_description() {
		?>
		</div><!-- /.list_products_header -->
		<?php
	}
}

// Add list mode buttons
if ( !function_exists( 'alex_stone_woocommerce_before_shop_loop' ) ) {
	
	function alex_stone_woocommerce_before_shop_loop() {
		?>
		<div class="alex_stone_shop_mode_buttons"><form action="<?php echo esc_url(alex_stone_get_current_url()); ?>" method="post"><input type="hidden" name="alex_stone_shop_mode" value="<?php echo esc_attr(alex_stone_storage_get('shop_mode')); ?>" /><a href="#" class="woocommerce_thumbs icon-shop-th" title="<?php esc_attr_e('Show products as thumbs', 'alex-stone'); ?>"></a><a href="#" class="woocommerce_list icon-shop-list" title="<?php esc_attr_e('Show products as list', 'alex-stone'); ?>"></a></form></div><!-- /.alex_stone_shop_mode_buttons -->
		<?php
	}
}

// Number of columns for the shop streampage
if ( !function_exists( 'alex_stone_woocommerce_loop_shop_columns' ) ) {
	
	function alex_stone_woocommerce_loop_shop_columns($cols) {
		return max(2, min(4, alex_stone_get_theme_option('blog_columns')));
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'alex_stone_woocommerce_loop_shop_columns_class' ) ) {
	
	
	function alex_stone_woocommerce_loop_shop_columns_class($classes, $class='', $cat='') {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns'])) {
				$classes[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
			}
		} else if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
			$classes[] = ' column-1_'.esc_attr(max(2, min(4, alex_stone_get_theme_option('blog_columns'))));
		}
		return $classes;
	}
}


// Open item wrapper for categories and products
if ( !function_exists( 'alex_stone_woocommerce_item_wrapper_start' ) ) {
	
	
	function alex_stone_woocommerce_item_wrapper_start($cat='') {
		alex_stone_storage_set('in_product_item', true);
		$hover = alex_stone_get_theme_option('shop_hover');
		?>
		<div class="post_item post_layout_<?php echo esc_attr(alex_stone_storage_get('shop_mode')); ?>">
			<div class="post_featured hover_<?php echo esc_attr($hover); ?>">
				<?php do_action('alex_stone_action_woocommerce_item_featured_start'); ?>
				<a href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
				<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'alex_stone_woocommerce_open_item_wrapper' ) ) {
	
	
	function alex_stone_woocommerce_title_wrapper_start($cat='') {
				?></a><?php
				if (($hover = alex_stone_get_theme_option('shop_hover')) != 'none') {
					?><div class="mask"></div><?php
					alex_stone_hovers_add_icons($hover, array('cat'=>$cat));
				}
				do_action('alex_stone_action_woocommerce_item_featured_end');
				?>
			</div><!-- /.post_featured -->
			<div class="post_data">
				<div class="post_data_inner">
					<div class="post_header entry-header">
					<?php
	}
}


// Display product's tags before the title
if ( !function_exists( 'alex_stone_woocommerce_title_tags' ) ) {
	
	function alex_stone_woocommerce_title_tags() {
		global $product;
		alex_stone_show_layout(wc_get_product_tag_list( $product->get_id(), ', ', '<div class="post_tags product_tags">', '</div>' ));
	}
}

// Wrap product title into link
if ( !function_exists( 'alex_stone_woocommerce_the_title' ) ) {
	
	function alex_stone_woocommerce_the_title($title) {
		if (alex_stone_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.esc_url(get_permalink()).'">'.esc_html($title).'</a>';
		}
		return $title;
	}
}

// Wrap category title into link
if ( !function_exists( 'alex_stone_woocommerce_shop_loop_subcategory_title' ) ) {
	
	function alex_stone_woocommerce_shop_loop_subcategory_title($cat) {
		if (alex_stone_storage_get('in_product_item') && is_object($cat)) {
			$cat->name = sprintf('<a href="%s">%s</a>', esc_url(get_term_link($cat->slug, 'product_cat')), $cat->name);
		}
		return $cat;
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'alex_stone_woocommerce_title_wrapper_end' ) ) {
	
	function alex_stone_woocommerce_title_wrapper_end() {
			?>
			</div><!-- /.post_header -->
		<?php
		if (alex_stone_storage_get('shop_mode') == 'list' && (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) && !is_product()) {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			?>
			<div class="post_content entry-content"><?php alex_stone_show_layout($excerpt); ?></div>
			<?php
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'alex_stone_woocommerce_title_wrapper_end2' ) ) {
	
	function alex_stone_woocommerce_title_wrapper_end2($category) {
			?>
			</div><!-- /.post_header -->
		<?php
		if (alex_stone_storage_get('shop_mode') == 'list' && is_shop() && !is_product()) {
			?>
			<div class="post_content entry-content"><?php alex_stone_show_layout($category->description); ?></div><!-- /.post_content -->
			<?php
		}
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'alex_stone_woocommerce_close_item_wrapper' ) ) {
	
	
	function alex_stone_woocommerce_item_wrapper_end($cat='') {
				?>
				</div><!-- /.post_data_inner -->
			</div><!-- /.post_data -->
		</div><!-- /.post_item -->
		<?php
		alex_stone_storage_set('in_product_item', false);
	}
}

// Change text on 'Add to cart' button
if ( ! function_exists( 'alex_stone_woocommerce_add_to_cart_text' ) ) {
    function alex_stone_woocommerce_add_to_cart_text( $text = '' ) {
        global $product;
        return is_object( $product ) && $product->is_in_stock()
        && 'grouped' !== $product->get_type()
        && ( 'external' !== $product->get_type() || $product->get_button_text() == '' )
            ? esc_html__( 'add to cart', 'alex-stone' )
            : $text;
    }
}

// Decorate price
if ( !function_exists( 'alex_stone_woocommerce_get_price_html' ) ) {
	
	function alex_stone_woocommerce_get_price_html($price='') {
		return $price;
	}
}



// Decorate WooCommerce output: Single product
//------------------------------------------------------------------------

// Add WooCommerce specific vars into localize array
if (!function_exists('alex_stone_woocommerce_localize_script')) {
	
	function alex_stone_woocommerce_localize_script($arr) {
		$arr['stretch_tabs_area'] = !alex_stone_sidebar_present() ? alex_stone_get_theme_option('stretch_tabs_area') : 0;
		return $arr;
	}
}

// Add Product ID for the single product
if ( !function_exists( 'alex_stone_woocommerce_show_product_id' ) ) {
	
	function alex_stone_woocommerce_show_product_id() {
		$authors = wp_get_post_terms(get_the_ID(), 'pa_product_author');
		if (is_array($authors) && count($authors)>0) {
			echo '<span class="product_author">'.esc_html__('Author: ', 'alex-stone');
			$delim = '';
			foreach ($authors as $author) {
				echo  esc_html($delim) . '<span>' . esc_html($author->name) . '</span>';
				$delim = ', ';
			}
			echo '</span>';
		}
		echo '<span class="product_id">'.esc_html__('product ID: ', 'alex-stone') . '<span>' . get_the_ID() . '</span></span>';
	}
}

// Number columns for the product's thumbnails
if ( !function_exists( 'alex_stone_woocommerce_product_thumbnails_columns' ) ) {
	
	function alex_stone_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Set products number for the related products
if ( !function_exists( 'alex_stone_woocommerce_output_related_products_args' ) ) {
	
	function alex_stone_woocommerce_output_related_products_args($args) {
		$args['posts_per_page'] = (int) alex_stone_get_theme_option('show_related_posts') 
										? max(0, min(9, alex_stone_get_theme_option('related_posts'))) 
										: 0;
		$args['columns'] = max(1, min(4, alex_stone_get_theme_option('related_columns')));
		return $args;
	}
}

// Set columns number for the related products
if ( !function_exists( 'alex_stone_woocommerce_related_products_columns' ) ) {
	
	function alex_stone_woocommerce_related_products_columns($columns) {
		$columns = max(1, min(4, alex_stone_get_theme_option('related_columns')));
		return $columns;
	}
}

if ( ! function_exists( 'alex_stone_woocommerce_price_filter_widget_step' ) ) {
    add_filter('woocommerce_price_filter_widget_step', 'alex_stone_woocommerce_price_filter_widget_step');
    function alex_stone_woocommerce_price_filter_widget_step( $step = '' ) {
        $step = 1;
        return $step;
    }
}



// Decorate WooCommerce output: Widgets
//------------------------------------------------------------------------

// Search form
if ( !function_exists( 'alex_stone_woocommerce_get_product_search_form' ) ) {
	
	function alex_stone_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'alex-stone') . '" value="' . get_search_query() . '" name="s" /><button class="search_button" type="submit">' . esc_html__('Search', 'alex-stone') . '</button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (alex_stone_exists_woocommerce()) { require_once ALEX_STONE_THEME_DIR . 'plugins/woocommerce/woocommerce.styles.php'; }
?>