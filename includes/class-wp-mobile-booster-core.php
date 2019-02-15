<?php

if ( ! class_exists( 'WP_Mobile_Booster' ) ) {
	die;
}
class WP_Mobile_Booster_Core {
	public function __construct() {
	}

	/***
	 * Frontend Scripts.
	 */
	public function frontend_enqueue_scripts() {
			wp_enqueue_style( 'mobileboostercss', plugins_url( 'css/mobile-booster.css', __FILE__ ) );
			wp_register_script( 'mobileboosterjs', plugins_url( 'js/mobile-booster.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'mobileboosterjs' );

			/* Enqueue Quicklink from Google Chromelabs */ 
			if ( 'enable' === get_option( 'mb_quicklink', 'enable' ) ) {
				wp_register_script( 'mobilequicklinkjs', plugins_url( 'js/quicklink.umd.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'mobilequicklinkjs' );
			}

			// Filters.
			add_filter( 'wp_head', array( $this, 'load_dynamic_css_style' ) );
			add_filter( 'wp_footer', array( $this, 'load_quicklink' ) );

	}

	/***
	 * Load dynamic css
	 */
	public function load_dynamic_css_style() {
		echo '<style type="text/css" id="mobile-boster-css">';
		echo '.mob-booster-footer {background-color: ' . get_option( 'mb_footer_bg_color', '#222' ) . '; height: ' . get_option( 'mb_footer_height', '40' ) . 'px; }';
		echo '.mob-booster-footer a {color: ' . get_option( 'mb_footer_text_color', '#FFF' ) . '; line-height: ' . get_option( 'mb_footer_height', '40' ) . 'px;}';
		echo '.woocommerce-message { bottom: ' . get_option( 'mb_footer_height', '40' ) . 'px;}';
		echo '</style>';
	}

	/***
	 * Load quicklink on load
	 */
	public function load_quicklink() { ?>
			<script>
				window.addEventListener('load', () => {
					quicklink();
				});
			</script>
		<?php
	}

	/***
	 * Build the WP Mobile Menu Html Markup.
	 */
	public function load_mobile_booster_html_markup() {

		if ( is_product() ) {
			global $product;

			if ( $product->is_type( 'simple' ) ) {
				$output = '<div class="mob-booster-footer"> <a href="' . $product->add_to_cart_url() . '">' . __( '' . get_option( 'mb_buy_now_text', 'Buy Now' ) . '' , 'mobile-booster' ) . '</a></div>';
				echo $output;
			}
		}
	}

}
