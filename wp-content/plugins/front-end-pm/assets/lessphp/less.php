<?php
/**
 * Plugin Name: Wordpress Less
 * Plugin URI: http://smartaddons.com
 * Description: Compile css from admin
 * Version: 3.0.0
 * Author: smartaddons.com
 * Author URI: http://smartaddons.com
 *
*/
add_action( 'wp', 'sw_less_construct', 20 );
function sw_less_construct(){
	if( function_exists( 'emarket_options' ) ) :
	require_once ( plugin_dir_path( __FILE__ ).'/3rdparty/lessc.inc.php' );
	
	$color =  emarket_options()->getCpanelValue('scheme_color');
	$bd_color =  emarket_options()->getCpanelValue('scheme_body');
	$bdr_color =  emarket_options()->getCpanelValue('scheme_border');
	
	if ( emarket_options()->getCpanelValue('developer_mode') ){
		define('LESS_PATH', get_template_directory().'/assets/less');
		define('CSS__PATH', get_template_directory().'/css');
		
		$scheme_meta = get_post_meta( get_the_ID(), 'scheme', true );
		$scheme = ( $scheme_meta != '' && $scheme_meta != 'none' ) ? $scheme_meta : emarket_options()->getCpanelValue('scheme');
		$ya_direction = emarket_options()->getCpanelValue( 'direction' );
		$scheme_vars = get_template_directory().'/templates/presets/default.php';
		$output_cssf = CSS__PATH.'/app-default.css';
		if ( $scheme && file_exists(get_template_directory().'/templates/presets/'.$scheme.'.php') ){
			$scheme_vars = get_template_directory().'/templates/presets/'.$scheme.'.php';
			$output_cssf = CSS__PATH."/app-{$scheme}.css";
		}
		if ( file_exists($scheme_vars) ){
			include $scheme_vars;
			if( $color != '' ){
				$less_variables['color'] = $color;
			}
			if(  $bd_color != '' ) {
				$less_variables['body-color'] = $bd_color;
			}
			if(  $bdr_color != '' ){
				$less_variables['border-color'] = $bdr_color;
			}
			
			try {
				// less variables by theme_mod
				// $less_variables['sidebar-width'] = emarket_options()->sidebar_collapse_width.'px';
				
				$less = new lessc();
				
				
				$less->setImportDir( array(LESS_PATH.'/app/', LESS_PATH.'/bootstrap/') );
				
				$less->setVariables($less_variables);
				
				$cache = $less->cachedCompile(LESS_PATH.'/app.less');
				file_put_contents($output_cssf, $cache["compiled"]);
				/* RTL Language */
					$rtl_cache = $less->cachedCompile(LESS_PATH.'/app/rtl.less');
					file_put_contents(CSS__PATH.'/rtl.css', $rtl_cache["compiled"]);
				
				$responsive_cache = $less->cachedCompile(LESS_PATH.'/app-responsive.less');
				file_put_contents(CSS__PATH.'/app-responsive.css', $responsive_cache["compiled"]);
			} catch (Exception $e){
				var_dump($e); exit;
			}
		}
	}
	endif;
}
