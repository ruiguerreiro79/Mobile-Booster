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

			wp_enqueue_script( 'lazyload', 'https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js', __FILE__, null, true );
			wp_enqueue_style( 'mobileboostercss', plugins_url( 'css/mobile-booster.css', __FILE__ ) );
			wp_enqueue_script( 'mobileboosterjs', plugins_url( 'js/mobile-booster.js', __FILE__ ), array( 'jquery' ), true );
			wp_enqueue_script( 'mobileboosterlazyload', plugins_url( 'js/lazyestload.js', __FILE__ ), null , true );

			/* Enqueue Quicklink from Google Chromelabs */
			if ( 'enable' === get_option( 'mb_quicklink', 'enable' ) ) {
				wp_enqueue_script( 'mobilequicklinkjs', plugins_url( 'js/quicklink.umd.js', __FILE__ ), array( 'jquery' ), true );
			}

			// Filters.
			add_filter( 'wp_head', array( $this, 'load_dynamic_css_style' ) );
			add_filter( 'wp_head', array( $this, 'load_quicklink' ) );
			add_filter('wp_footer', array( $this, 'footer_lazyload' ), 10 );

	}

	/***
	 * Footer Lazy Load.
	 */	
	public function footer_lazyload() {
		echo '<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js"></script>';
		echo '
	<script type="text/javascript">
	
	const observer = lozad(".lozad", {
		rootMargin: "500px 0px",
		threshold: 0.1,
		load: function(el) {
		  el.src = el.dataset.src;
		},
	  });
	  observer.observe();
	</script>
	';
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
	 * Load Lazyload
	 */
	public function load_lazyload() { 
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
