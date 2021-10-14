<?php
/* YITH WooCommerce Wishlist support functions
------------------------------------------------------------------------------- */

// Check if plugin installed and activated
if ( ! function_exists( 'alex_stone_exists_yith_wcwl_wishlist' ) ) {
    function alex_stone_exists_yith_wcwl_wishlist() {
        return class_exists( 'YITH_WCWL' );
    }
}

if (!function_exists('alex_stone_yith_woocommerce_wishlist_theme_setup9')) {
    add_action('after_setup_theme', 'alex_stone_yith_woocommerce_wishlist_theme_setup9', 9);
    function alex_stone_yith_woocommerce_wishlist_theme_setup9() {
        if (is_admin()) {
            add_filter( 'alex_stone_filter_tgmpa_required_plugins',		'alex_stone_yith_woocommerce_wishlist_tgmpa_required_plugins' );
        }
    }
}


// Filter to add in the required plugins list
if ( !function_exists( 'alex_stone_yith_woocommerce_wishlist_tgmpa_required_plugins' ) ) {
    function alex_stone_yith_woocommerce_wishlist_tgmpa_required_plugins($list=array()) {
        if (alex_stone_storage_isset('required_plugins', 'yith-woocommerce-wishlist')) {
            $list[] = array(
                'name' 		=> esc_html__('YITH WooCommerce Wishlist', 'alex-stone'),
                'slug' 		=> 'yith-woocommerce-wishlist',
                'required' 	=> false
            );

        }
        return $list;
    }
}


// Set plugin's specific importer options
if ( !function_exists( 'alex_stone_yith_wcwl_wishlist_importer_set_options' ) ) {
    if (is_admin()) add_filter( 'trx_addons_filter_importer_options',    'alex_stone_yith_wcwl_wishlist_importer_set_options' );
    function alex_stone_yith_wcwl_wishlist_importer_set_options($options=array()) {
        if ( alex_stone_exists_yith_wcwl_wishlist() && in_array('yith-woocommerce-wishlist', $options['required_plugins']) ) {
            $options['additional_options'][]    = 'yith_wcwl_%';
        }
        return $options;
    }
}
