<?php
/* ThemeREX Updater support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if ( ! function_exists( 'alex_stone_trx_updater_theme_setup9' ) ) {
    add_action( 'after_setup_theme', 'alex_stone_trx_updater_theme_setup9', 9 );
    function alex_stone_trx_updater_theme_setup9() {
        if ( is_admin() ) {
            add_filter( 'alex_stone_filter_tgmpa_required_plugins', 'alex_stone_trx_updater_tgmpa_required_plugins', 8 );
        }
    }
}


// Filter to add in the required plugins list
if ( ! function_exists( 'alex_stone_trx_updater_tgmpa_required_plugins' ) ) {

    function alex_stone_trx_updater_tgmpa_required_plugins( $list = array() ) {
        if ( alex_stone_storage_isset( 'required_plugins', 'trx_updater' ) ) {
            $path = alex_stone_get_file_dir( 'plugins/trx_updater/trx_updater.zip' );
            if ( ! empty( $path ) || alex_stone_get_theme_setting( 'tgmpa_upload' ) ) {
                $list[] = array(
                    'name'     => alex_stone_storage_get_array( 'required_plugins', 'trx_updater' ),
                    'slug'     => 'trx_updater',
                    'version'  => '1.5.2.1',
                    'source'   => ! empty( $path ) ? $path : 'upload://trx_updater.zip',
                    'required' => false,
                );
            }
        }
        return $list;
    }
}