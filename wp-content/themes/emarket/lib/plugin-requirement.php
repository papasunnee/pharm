<?php
/***** Active Plugin ********/
require_once( get_template_directory().'/lib/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'emarket_register_required_plugins' );
function emarket_register_required_plugins() {
  $plugins = array(
    array(
      'name'               => esc_html__( 'WooCommerce', 'emarket' ), 
      'slug'               => 'woocommerce', 
      'required'           => true, 
      'version'			   => '3.3.5'
      ),
    
    array(
     'name'               => esc_html__( 'Revslider', 'emarket' ), 
     'slug'               => 'revslider', 
     'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/revslider.zip' ), 
     'required'           => true, 
     'version'            => '5.4.7.2'
     ),

    array(
     'name'               => esc_html__( 'Visual Composer', 'emarket' ), 
     'slug'               => 'js_composer', 
     'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/js_composer.zip' ), 
     'required'           => true, 
     'version'            => '5.4.7'
     ), 

    array(
      'name'     		 => esc_html__( 'SW Core', 'emarket' ),
      'slug'      		 => 'sw_core',
      'source'        	 => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/sw_core.zip' ), 
      'required'  		 => true,   
      'version'			 => '1.0.2'
      ),

    array(
      'name'     		 => esc_html__( 'SW WooCommerce', 'emarket' ),
      'slug'      		 => 'sw_woocommerce',
      'source'         	 => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/sw_woocommerce.zip' ), 
      'required'  		 => true,
      'version'			 => '1.1.0'
      ),

    array(
      'name'     		 => esc_html__( 'SW Woocommerce Swatches', 'emarket' ),
      'slug'      		 => 'sw_wooswatches',
      'source'         	 => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/sw_wooswatches.zip' ), 
      'required'  		 => true,
      'version'			 => '1.0.3'
      ),

    array(
      'name'     		 => esc_html__( 'SW Ajax Woocommerce Search', 'emarket' ),
      'slug'      		 => 'sw_ajax_woocommerce_search',
      'source'         	 => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/sw_ajax_woocommerce_search.zip' ), 
      'required'  		 => true,
      'version'			 => '1.1.3'
      ),

    array(
      'name'               => esc_html__( 'One Click Demo Import', 'emarket' ), 
      'slug'               => 'one-click-demo-import', 
      'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/one-click-demo-import.zip' ), 
      'required'           => true, 
      ),

    array(
      'name'     			 => esc_html__( 'WordPress Importer', 'emarket' ),
      'slug'      		 => 'wordpress-importer',
      'required' 			 => true,
      ), 
    array(
      'name'      		 => esc_html__( 'MailChimp for WordPress Lite', 'emarket' ),
      'slug'     			 => 'mailchimp-for-wp',
      'required' 			 => false,
      ),
    array(
      'name'      		 => esc_html__( 'Contact Form 7', 'emarket' ),
      'slug'     			 => 'contact-form-7',
      'required' 			 => false,
      ),
    array(
      'name'      		 => esc_html__( 'YITH Woocommerce Compare', 'emarket' ),
      'slug'      		 => 'yith-woocommerce-compare',
      'required'			 => false
      ),
    array(
      'name'     			 => esc_html__( 'YITH Woocommerce Wishlist', 'emarket' ),
      'slug'      		 => 'yith-woocommerce-wishlist',
      'required' 			 => false
      ), 
    array(
      'name'     			 => esc_html__( 'WordPress Seo', 'emarket' ),
      'slug'      		 => 'wordpress-seo',
      'required'  		 => false,
      ),

    );
if( emarket_options()->getCpanelValue('developer_mode') ): 
 $plugins[] = array(
  'name'               => esc_html__( 'Less Compile', 'emarket' ), 
  'slug'               => 'lessphp', 
  'source'             => esc_url( 'http://demo.wpthemego.com/themes/sw_emarket/plugins/lessphp.zip' ), 
  'required'           => true, 
  );
endif;
$config = array();

tgmpa( $plugins, $config );

}
add_action( 'vc_before_init', 'emarket_vcSetAsTheme' );
function emarket_vcSetAsTheme() {
  vc_set_as_theme();
}