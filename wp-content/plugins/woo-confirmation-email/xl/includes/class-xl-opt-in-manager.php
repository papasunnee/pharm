<?php

/**
 * OptIn manager class is to handle all scenerious occurs for opting the user
 * @author: XLPlugins
 * @since 0.0.1
 * @package XLCore
 *
 */
class XL_optIn_Manager {

	public static $optIn_state;
	public static $optIn_detail_page_url = "https://shop.xlplugins.com/optin";
	public static $should_show_optin = false;

	/**
	 * Initialization to execute several hooks
	 */
	public static function init() {


		//push notification for optin
		add_action( 'admin_init', array( __CLASS__, 'maybe_push_optin_notice' ), 15 );

		// track usage user callback
		add_action( 'xl_maybe_track_usage_scheduled', array( __CLASS__, 'maybe_track_usage' ) );


		//initialiting schedules
		add_action( 'wp', array( __CLASS__, 'initiate_schedules' ) );


		add_action( 'admin_init', array( __CLASS__, 'maybe_clear_optin' ) );
		// For testing license notices, uncomment this line to force checks on every page load
		//  add_action( 'admin_init', array( __CLASS__, 'maybe_track_usage' ) );
	}

	/**
	 * Set function to allow
	 */
	public static function Allow_optin() {
		update_option( "xl_is_opted", "yes" );

		//try to push data for once
		$data = self::collect_data();

		//posting data to api
		XL_API::post_tracking_data( $data );
	}

	/**
	 * Set function to block
	 */
	public static function block_optin() {
		update_option( "xl_is_opted", "no" );


		//posting data to api
		XL_API::post_optin_denied_data( apply_filters( 'xl_optin_denied_data', array( 'optin_result' => 'no' ) ) );
	}

	public static function maybe_clear_optin() {

		if ( filter_input( INPUT_GET, 'xl_optin_refresh' ) == 'yes' && 'yes' !== get_option( 'xl_is_opted', 'no' ) ) {

			self::reset_optin();
		}
	}

	/**
	 * Reset optin
	 */
	public static function reset_optin() {
		delete_option( "xl_is_opted" );
	}

	public static function update_optIn_referer( $referer ) {
		update_option( "xl_optin_ref", $referer, false );
	}

	/**
	 * Checking the opt-in state and if we have scope for notification then push it
	 */
	public static function maybe_push_optin_notice() {

		if ( self::get_optIn_state() == false && apply_filters( "xl_optin_notif_show", self::$should_show_optin ) ) {
			do_action( 'maybe_push_optin_notice_state_action' );
		}
	}

	/**
	 * Get current optin status from database
	 * @return type
	 */
	public static function get_optIn_state() {
		if ( self::$optIn_state !== null ) {
			return self::$optIn_state;
		}

		return self::$optIn_state = get_option( "xl_is_opted" );
	}

	/**
	 * Callback function to run on schedule hook
	 */
	public static function maybe_track_usage() {


		//checking optin state
		if ( self::get_optIn_state() == "yes" ) {
			$data = self::collect_data();

			//posting data to api
			XL_API::post_tracking_data( $data );
		}
	}

	/**
	 * Collect some data and let the hook left for our other plugins to add some more info that can be tracked down
	 * <br/>
	 * @return array data to track
	 */
	public static function collect_data() {
		global $wpdb;


		$installed_plugs = XL_addons::get_installed_plugins();

		$active_plugins = get_option( 'active_plugins' );

		$licenses = XL_licenses::get_instance()->get_data();
		$theme    = array();


		$get_theme_info      = wp_get_theme();
		$theme['name']       = $get_theme_info->get( 'Name' );
		$theme['uri']        = $get_theme_info->get( 'ThemeURI' );
		$theme['version']    = $get_theme_info->get( 'Version' );
		$theme['author']     = $get_theme_info->get( 'Author' );
		$theme['author_uri'] = $get_theme_info->get( 'AuthorURI' );
		$ref                 = get_option( 'xl_optin_ref', "" );
		$sections            = array();
		if ( class_exists( 'WooCommerce' ) ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();

			foreach ( $payment_gateways as $gateway ) {
				if ( 'yes' === $gateway->enabled ) {
					$sections[] = esc_html( $gateway->get_title() );

				}
			}
			/* WordPress information. */


		}

		return apply_filters( 'xl_global_tracking_data', array(
			'url'              => home_url(),
			'email'            => get_option( 'admin_email' ),
			'installed'        => $installed_plugs,
			'active_plugins'   => $active_plugins,
			'license_info'     => $licenses,
			'theme_info'       => $theme,
			'users_count'      => self::get_user_counts(),
			'locale'           => get_locale(),
			'is_mu'            => is_multisite(),
			'wp'               => get_bloginfo( 'version' ),
			'php'              => phpversion(),
			'mysql'            => $wpdb->db_version(),
			'notification_ref' => $ref,
			'wc_gateways'      => $sections,
			'date'             => date( 'd.m.Y H:i:s' ),
		) );
	}

	/**
	 * Get user totals based on user role.
	 * @return array
	 */
	private static function get_user_counts() {
		$user_count          = array();
		$user_count_data     = count_users();
		$user_count['total'] = $user_count_data['total_users'];

		// Get user count based on user role
		foreach ( $user_count_data['avail_roles'] as $role => $count ) {
			$user_count[ $role ] = $count;
		}

		return $user_count;
	}

	/**
	 * Initiate schedules in order to start tracking data regularly
	 */
	public static function initiate_schedules() {

		if ( ! wp_next_scheduled( 'xl_maybe_track_usage_scheduled' ) ) {
			wp_schedule_event( current_time( 'timestamp' ), 'daily', 'xl_maybe_track_usage_scheduled' );
		}
	}

}

// Initialization
XL_optIn_Manager::init();
