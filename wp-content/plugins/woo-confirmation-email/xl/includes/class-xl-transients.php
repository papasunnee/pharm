<?php

/**
 * @author XLPlugins
 * @package XLCore
 */
if ( ! class_exists( 'XL_Transient' ) ) {
	class XL_Transient {

		protected static $instance;

		/**
		 * XL_Transient constructor.
		 */
		public function __construct() {

		}

		/**
		 * Creates an instance of the class
		 * @return type
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Set the transient contents by key and group within page scope
		 *
		 * @param $key
		 * @param $value
		 * @param int $expirtaion | deafult 1 hour
		 * @param string $plugin_shortname
		 */
		public function set_transient( $key, $value, $expirtaion = 3600, $plugin_shortname = 'finale' ) {
			$array = array( 'time' => time() + (int) $expirtaion, 'value' => $value );
			update_option( '_xlcore_transient_' . $plugin_shortname . '_' . $key, $array, false );
		}

		/**
		 * Get the transient contents by the transient key or group.
		 *
		 * @param $key
		 * @param string $plugin_shortname
		 *
		 * @return bool|mixed
		 */
		public function get_transient( $key, $plugin_shortname = 'finale' ) {
			$data = get_option( '_xlcore_transient_' . $plugin_shortname . '_' . $key, false );
			if ( false === $data ) {
				return false;
			}
			$current_time = time();
			if ( is_array( $data ) && isset( $data['time'] ) ) {
				if ( $current_time > (int) $data['time'] ) {
					delete_option( '_xlcore_transient_' . $plugin_shortname . '_' . $key );

					return false;
				} else {
					return $data['value'];
				}
			}

			return false;
		}

		/**
		 * Delete the transient by key
		 *
		 * @param $key
		 * @param string $plugin_shortname
		 */
		function delete_transient( $key, $plugin_shortname = 'finale' ) {
			delete_option( '_xlcore_transient_' . $plugin_shortname . '_' . $key );
		}

		/**
		 * Delete all the transients
		 *
		 * @param string $plugin_shortname
		 */
		function delete_all_transients( $plugin_shortname = '' ) {
			global $wpdb;
			$query = "DELETE FROM `$wpdb->options` WHERE `option_name` LIKE '%_xlcore_transient_{$plugin_shortname}%'";
			$wpdb->query( $query );
		}

	}
}
