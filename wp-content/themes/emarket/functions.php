<?php 
if ( !defined( 'ICL_LANGUAGE_CODE' ) && !defined('EMARKET_THEME') ){
        define( 'EMARKET_THEME', 'emarket_theme' );
}else{
        define( 'EMARKET_THEME', 'emarket_theme'.ICL_LANGUAGE_CODE );
}

/**
 * Variables
 */
require_once ( get_template_directory().'/lib/plugin-requirement.php' ); // Custom functions
require_once ( get_template_directory().'/lib/defines.php' );
require_once ( get_template_directory().'/lib/mobile-layout.php' );
require_once ( get_template_directory().'/lib/classes.php' );		// Utility functions
require_once ( get_template_directory().'/lib/utils.php' );			// Utility functions
require_once ( get_template_directory().'/lib/init.php' );			// Initial theme setup and constants
require_once ( get_template_directory().'/lib/cleanup.php' );		// Cleanup
require_once ( get_template_directory().'/lib/nav.php' );			// Custom nav modifications
require_once ( get_template_directory().'/lib/widgets.php' );		// Sidebars and widgets
require_once ( get_template_directory().'/lib/scripts.php' );		// Scripts and stylesheets
require_once ( get_template_directory().'/lib/metabox.php' );	// Custom functions
require_once (get_template_directory().'/lib/import/sw-import.php' );
if( class_exists( 'WooCommerce' ) ){
	require_once ( get_template_directory().'/lib/plugins/currency-converter/currency-converter.php' ); // currency converter
	require_once ( get_template_directory().'/lib/woocommerce-hook.php' );	// Utility functions
	
	if( class_exists( 'WC_Vendors' ) ) :
		require_once ( get_template_directory().'/lib/wc-vendor-hook.php' );			/** WC Vendor **/
	endif;
	
	if( class_exists( 'WeDevs_Dokan' ) ) :
		require_once ( get_template_directory().'/lib/dokan-vendor-hook.php' );			/** Dokan Vendor **/
	endif;
	
	if( class_exists( 'WCMp' ) ) :
		require_once ( get_template_directory().'/lib/wc-marketplace-hook.php' );			/** WC MarketPlace Vendor **/
	endif;
}

function emarket_template_load( $template ){ 
	if( !is_user_logged_in() && emarket_options()->getCpanelValue('maintaince_enable') ){
		$template = get_template_part( 'maintaince' );
	}
	return $template;
}
add_filter( 'template_include', 'emarket_template_load' );


add_filter( 'emarket_widget_register', 'emarket_add_custom_widgets' );
function emarket_add_custom_widgets( $emarket_widget_areas ){
	if( class_exists( 'sw_woo_search_widget' ) ){
		$emarket_widget_areas[] = array(
			'name' => esc_html__('Widget Search', 'emarket'),
			'id'   => 'search',
			'before_widget' => '<div class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
			);
	}
	return $emarket_widget_areas;
}