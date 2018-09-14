<?php
/**
 * Theme activation
 */
if (is_admin() && isset($_GET['activated']) && 'themes.php' == $GLOBALS['pagenow']) {
	wp_redirect(admin_url('themes.php?page=sw_activation_options'));
	exit;
}

function sw_activation_options_init() {
	if (sw_get_activation_options() === false) {
		add_option('sw_activation_options', sw_get_default_activation_options());
	}

	register_setting(
	'sw_activation_options',
	'sw_activation_options',
	'sw_activation_options_validate'
			);
}
add_action('admin_init', 'sw_activation_options_init');

function sw_activation_options_page_capability($capability) {
	return 'edit_theme_options';
}
add_filter('option_page_capability_sw_activation_options', 'sw_activation_options_page_capability');

function sw_activation_options_add_page() {
	$sw_activation_options = sw_get_activation_options();
	if ($sw_activation_options['first_run']) {
		$theme_page = add_theme_page(
				esc_html__('Theme Activation', 'emarket'),
				esc_html__('Theme Activation', 'emarket'),
				'edit_theme_options',
				'sw_activation_options',
				'sw_activation_options_render_page'
		);		
	} else {
		if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'sw_activation_options'  ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			if ( ! get_option( 'sw_plugins' ) ) {
				wp_redirect( esc_url( admin_url('themes.php?page=radium_demo_installer') ) );
			}else{
				wp_redirect( esc_url( admin_url('themes.php?page=tgmpa-install-plugins') ) );
			}
			exit;
		}
	}
}
add_action('admin_menu', 'sw_activation_options_add_page', 50);

function sw_get_default_activation_options() {
	$default_activation_options = array(
			'first_run'                       => true,
			'upgrade_theme'               => false,
	);

	return apply_filters('sw_default_activation_options', $default_activation_options);
}

function sw_get_activation_options() {
	return get_option('sw_activation_options', sw_get_default_activation_options());
}

function sw_activation_options_render_page() { ?>
<div class="sw-activation-form">
	<div class="activation-form-inner">
		<h2>
			<?php printf( esc_html__( '%s Theme Activation', 'emarket' ), wp_get_theme() ); ?>
		</h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">

			<?php
				settings_fields('sw_activation_options');
				$sw_activation_options = sw_get_activation_options();
				$sw_default_activation_options = sw_get_default_activation_options();
			?>

			<input type="hidden" value="false"
				name="sw_activation_options[first_run]">
			
			<div class="sw-activation">
				<ul>
					<li>
						<label for="update_theme" class="clearfix">
							<input type="radio" id="update_theme" name="sw_activation_options[upgrade_theme]" value="false" checked>
							<div class="activation-content">
								<h3><?php esc_html_e( 'New Installation (Install theme with demo data)', 'emarket' ); ?></h3>
								<div class="activation-desc"><?php esc_html_e( 'Install your theme with our sample data.
	Use our sample data for all files, products, images, configurations... that you will have a complete website that is exactly same as our Demo.', 'emarket' ); ?></div>
							</div>
						</label>
					</li>
					<li>
						<label for="upgrade_theme" class="clearfix">
							<input type="radio" id="upgrade_theme" name="sw_activation_options[upgrade_theme]" value="true">
							<div class="activation-content">
								<h3><?php esc_html_e( 'Upgrade Theme (Install theme only)', 'emarket' ); ?></h3>
								<div class="activation-desc"><?php esc_html_e( "Install your theme with your data.
	Your files, products, images, configurations... aren't change. You will get the Emarket theme layouts and pages.", 'emarket' ); ?></div>
							</div>
						</label>
					</li>
				</ul>
			</div>
			
			<?php submit_button(); ?>
		</form>
	</div>
</div>

<?php }

function sw_activation_options_validate($input) {
  $output = $defaults = sw_get_default_activation_options();

  $options = array(
    'first_run',
    'upgrade_theme'
  );

  foreach($options as $option_name) {
    if (isset($input[$option_name])) {
      $input[$option_name] = ($input[$option_name] === 'true') ? true : false;
      $output[$option_name] = $input[$option_name];
    }
  }

  return apply_filters('sw_activation_options_validate', $output, $input, $defaults);
}

function sw_activation_action() {
	$sw_activation_options = sw_get_activation_options();
	if( $sw_activation_options['upgrade_theme'] ){
		update_option( 'sw_activation', true );
	}
  update_option('sw_activation_options', $sw_activation_options);
}
add_action('admin_init','sw_activation_action');

function sw_deactivation() {
 	delete_option('sw_activation_options');
}
add_action('switch_theme', 'sw_deactivation');
