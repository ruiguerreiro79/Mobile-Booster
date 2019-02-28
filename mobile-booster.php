<?php

/**
 * Plugin Name: Mobile Booster
 * Plugin URI: https://www.wpmobilemenu.com/mobile-booster/
 * Description: Boost the user experience and ecommerce sales on mobile devices. Keep your mobile visitors engaged.
 * Author: Takanakui
 * Version: 1.0
 * Author URI: https://www.wpmobilemenu.com/
 * Tested up to: 4.9
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
			$this->mob_booster_options = new WP_Mobile_Booster_options();

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

		private static function flatten_kses_hair_data( $attributes ) {
			$flattened_attributes = array();
			foreach ( $attributes as $name => $attribute ) {
				$flattened_attributes[ $name ] = $attribute['value'];
			}
			return $flattened_attributes;
		}

		static function get_url( $path = '' ) {
			return plugins_url( ltrim( $path, '/' ), __FILE__ );
		}

		function process_image( $matches ) {
			$old_attributes_str = $matches[2];
			$old_attributes_kses_hair = wp_kses_hair( $old_attributes_str, wp_allowed_protocols() );
			if ( empty( $old_attributes_kses_hair['src'] ) ) {
				return $matches[0];
			}
			$old_attributes = self::flatten_kses_hair_data( $old_attributes_kses_hair );
			$new_attributes = $old_attributes;
			// Set placeholder and lazy-src
			$new_attributes['src'] = apply_filters( 'lazyload_images_placeholder_image', plugin_dir_url( __FILE__ ) . 'includes/assets/placeholder.gif' );
			$new_attributes['data-src'] = $old_attributes['src'];
			// Handle `srcset`
			if ( ! empty( $new_attributes['srcset'] ) ) {
				$new_attributes['data-srcset'] = $old_attributes['srcset'];
				unset( $new_attributes['srcset'] );
			}
			// Handle `sizes`
			if ( ! empty( $new_attributes['sizes'] ) ) {
				$new_attributes['data-sizes'] = $old_attributes['sizes'];
				unset( $new_attributes['sizes'] );
			}

			$string = array();
			foreach ( $new_attributes as $name => $value ) {
				// Check if the lozad class exists. Add it if doesn't exist.
				if ( 'class' == $name && strpos( $value, 'lozad' ) == false ) {
					$value .= ' lozad';
				}

				if ( '' === $value ) {
					$string[] = sprintf( '%s', $name );
				} else {
					$string[] = sprintf( '%s="%s"', $name, esc_attr( $value ) );
				}
			}
			$new_attributes_str =  implode( ' ', $string );

			return sprintf( '<img %1$s><noscript>%2$s</noscript>', $new_attributes_str, $matches[0] );
		}

		/**
		 * Load Frontend Assets
		 *
		 * @since 1.0
		 */
		private function load_frontend_assets() {
			if ( wp_is_mobile() ) {
				// Enqueue Html to the Footer.
				add_action( 'wp_footer', array( $this->mob_booster_core, 'load_mobile_booster_html_markup' ) );
				// Frontend Scripts.
				add_action( 'wp_enqueue_scripts', array( $this->mob_booster_core, 'frontend_enqueue_scripts' ) );

				// Lazy Load Filter.
				add_filter('the_content', function ($content) {

					$content = preg_replace_callback( '#<(img)([^>]+?)(>(.*?)</\\1>|[\/]?>)#si', array( __CLASS__, 'process_image' ), $content );

					return $content;
				});
			}
		}
	}
}

// Instanciate the WP_Mobile_Booster.
new WP_Mobile_Booster();
