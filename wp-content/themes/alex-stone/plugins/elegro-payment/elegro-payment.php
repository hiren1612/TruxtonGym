<?php
/* elegro Crypto Payment support functions
------------------------------------------------------------------------------- */

// Check if this plugin installed and activated
if ( ! function_exists( 'alex_stone_exists_elegro_payment' ) ) {
    function alex_stone_exists_elegro_payment() {
        return class_exists( 'WC_Elegro_Payment' );
    }
}


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('alex_stone_elegro_payment_theme_setup9')) {
    add_action( 'after_setup_theme', 'alex_stone_elegro_payment_theme_setup9', 9 );
    function alex_stone_elegro_payment_theme_setup9() {
        if (alex_stone_exists_elegro_payment()) {
            add_action( 'wp_enqueue_scripts', 							'alex_stone_elegro_payment_frontend_scripts', 1100 );
            add_filter( 'alex_stone_filter_merge_styles',					'alex_stone_elegro_payment_merge_styles');
        }
        if (is_admin()) {
            add_filter( 'alex_stone_filter_tgmpa_required_plugins',		'alex_stone_elegro_payment_tgmpa_required_plugins' );
        }
    }
}


// Filter to add in the required plugins list
if ( ! function_exists( 'alex_stone_elegro_payment_tgmpa_required_plugins' ) ) {
    function alex_stone_elegro_payment_tgmpa_required_plugins( $list = array() ) {
        if ( alex_stone_storage_isset( 'required_plugins', 'woocommerce' ) && alex_stone_storage_isset( 'required_plugins', 'elegro-payment' ) && alex_stone_storage_get_array( 'required_plugins', 'elegro-payment', 'install' ) !== false ) {
            $list[] = array(
                'name'     => alex_stone_storage_get_array( 'required_plugins', 'elegro-payment' ),
                'slug'     => 'elegro-payment',
                'required' => false,
            );
        }
        return $list;
    }
}

// Enqueue plugin's custom styles
if ( !function_exists( 'alex_stone_elegro_payment_frontend_scripts' ) ) {

    function alex_stone_elegro_payment_frontend_scripts() {
        if (alex_stone_is_on(alex_stone_get_theme_option('debug_mode')) && alex_stone_get_file_dir('plugins/elegro-payment/elegro-payment.css')!='')
            wp_enqueue_style( 'alex-stone-elegro-payment',  alex_stone_get_file_url('plugins/elegro-payment/elegro-payment.css'), array(), null );
    }
}



// Merge custom styles
if ( ! function_exists( 'alex_stone_elegro_payment_merge_styles' ) ) {
    function alex_stone_elegro_payment_merge_styles( $list ) {
        $list[] = 'plugins/elegro-payment/elegro-payment.css';
        return $list;
    }
}
