<?php

/**
 * Plugin Name: Mobile Booster
 * Plugin URI: https://www.wpmobilemenu.com/mobile-booster/
 * Description: Boost the user experience and ecommerce sales on mobile devices. Keep your mobile visitors engaged.
 * Author: Takanakui
 * Version: 1.1
 * Author URI: https://www.wpmobilemenu.com/
 * Tested up to: 5.1
 * Text Domain: mobile-booster
 * License: GPLv3
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}
if ( ! class_exists( 'WP_Mobile_Booster' ) ) {
	/**
	 * Main Mobile Booster class
	 */
	class WP_Mobile_Booster {
		public $mb_fs;
		public $mob_booster_core;
		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->init_mobile_booster();
		}

		/**
		 * Init Mobile Booster
		 *
		 * @since 1.0
		 */
		public function init_mobile_booster() {

			// Init Freemius.
			$this->mb_fs = $this->mb_fs();
			// Uninstall Action.
			$this->mb_fs->add_action( 'after_uninstall', array( $this, 'mb_fs_uninstall_cleanup' ) );
			// Include Required files.
			$this->include_required_files();
			// Instanciate the Mobile Booster Core Functions.
			$this->mob_booster_core = new WP_Mobile_Booster_Core();
			// Instanciate the Mobile Booster Options.
			$this->mob_booster_options = new WP_Mobile_Booster_Options();

			// Add the Mobile Booster customizer settings.
			add_action( 'customize_register', array( $this->mob_booster_options, 'mobile_booster_customizer_settings' ) );

			// Load frontend assets.
			if ( ! is_admin() ) {
				$this->load_frontend_assets();
			}
		}

		/**
		 * Init Freemius Settings
		 *
		 * @since 1.0
		 */
		public function mb_fs() {
			global  $mb_fs ;

			if ( ! isset( $this->mb_fs ) ) {
				// Include Freemius SDK.
				require_once dirname( __FILE__ ) . '/freemius/start.php';
				$mb_fs = fs_dynamic_init( array(
					'id'                  => '2834',
					'slug'                => 'mobile-booster',
					'type'                => 'plugin',
					'public_key'          => 'pk_cfee87a28181a81cb767f63c8aeb2',
					'is_premium'          => false,
					'has_addons'          => false,
					'has_paid_plans'      => false,
					'menu'                => array(
						'slug'           => 'mobile-booster',
					),
				) );
			}

			return $mb_fs;
		}

		/**
		 * Include required files
		 *
		 * @since 1.0
		 */
		private function include_required_files() {
			require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-booster-core.php';
			require_once dirname( __FILE__ ) . '/includes/class-wp-mobile-booster-options.php';
		}

		/**
		 * Load Frontend Assets
		 *
		 * @since 1.0
		 */
		private function load_frontend_assets() {
			if ( wp_is_mobile() ) {
				if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					// Enqueue Html to the Footer.
					add_action( 'wp_footer', array( $this->mob_booster_core, 'load_mobile_booster_html_markup' ) );
				}
				// Frontend Scripts.
				add_action( 'wp_enqueue_scripts', array( $this->mob_booster_core, 'frontend_enqueue_scripts' ) );
			}
		}
	}
}

// Instanciate the WP_Mobile_Booster.
new WP_Mobile_Booster();
