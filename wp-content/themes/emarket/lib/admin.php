<?php

/**
 * Add Theme Options page.
 */
function emarket_theme_admin_page(){
	add_theme_page(
		esc_html__('Theme Options', 'emarket'),
		esc_html__('Theme Options', 'emarket'),
		'manage_options',
		'emarket_theme_options',
		'emarket_theme_admin_page_content'
	);
}
add_action('admin_menu', 'emarket_theme_admin_page', 49);

function emarket_theme_admin_page_content(){ ?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Emarket Advanced Options Page', 'emarket' ); ?></h2>
		<?php do_action( 'emarket_theme_admin_content' ); ?>
	</div>
<?php
}