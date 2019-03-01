<?php

if ( ! class_exists( 'WP_Mobile_Booster' ) ) {
	die;
}
/**
 * Mobile Booster core
 */
class WP_Mobile_Booster_Core {
	public function __construct() {
	}

	/**
	 * Frontend Scripts.
	 */
	public function frontend_enqueue_scripts() {

		wp_enqueue_style( 'mobileboostercss', plugins_url( 'css/mobile-booster.css', __FILE__ ) );
		wp_enqueue_script( 'mobileboosterjs', plugins_url( 'js/mobile-booster.js', __FILE__ ), array( 'jquery' ), true );

		/* Enqueue Quicklink from Google Chromelabs */
		if ( 'enable' === get_option( 'mb_quicklink', 'enable' ) ) {
			wp_enqueue_script( 'mobilequicklinkjs', plugins_url( 'js/quicklink.umd.js', __FILE__ ), array( 'jquery' ), true );
			add_filter( 'wp_head', array( $this, 'load_quicklink' ) );
		}
		/* Enqueue Lazyload scripts */
		if ( 'enable' === get_option( 'mb_lazyload', 'enable' ) ) {

			// Enqueue Lozad scripts.
			wp_enqueue_script( 'lozad', 'https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js', array(), '1.0.0', false );
			add_filter( 'wp_footer', array( $this, 'footer_lazyload' ), 10 );

			// Lazy Load Content Filter.
			add_filter('the_content', function ( $content ) {

				$content = preg_replace_callback( '#<(img)([^>]+?)(>(.*?)</\\1>|[\/]?>)#si', array( $this, 'manipulate_image_html' ), $content );

				return $content;
			});
		}

		// Filters.
		add_filter( 'wp_head', array( $this, 'load_dynamic_css_style' ) );

	}

	/**
	 * Manipulate images HTML to avoid initial loading.
	 *
	 * @param array $matches array match of the regex.
	 */
	private function manipulate_image_html( $matches ) {

		$old_attributes_str       = $matches[2];
		$old_attributes_kses_hair = wp_kses_hair( $old_attributes_str, wp_allowed_protocols() );

		if ( empty( $old_attributes_kses_hair['src'] ) ) {
			return $matches[0];
		}

		$flattened_attributes = array();

		foreach ( $old_attributes_kses_hair as $name => $attribute ) {
			$flattened_attributes[ $name ] = $attribute['value'];
		}

		$old_attributes             = $flattened_attributes;
		$new_attributes             = $old_attributes;

		// Manipulate `placeholder` and data-src.
		$new_attributes['src']      = apply_filters( 'lazyload_images_placeholder_image', plugin_dir_url( __FILE__ ) . 'assets/placeholder.gif' );
		$new_attributes['data-src'] = $old_attributes['src'];

		// Manipulate `srcset` param.
		if ( ! empty( $new_attributes['srcset'] ) ) {
			$new_attributes['data-srcset'] = $old_attributes['srcset'];
			unset( $new_attributes['srcset'] );
		}
		// Manipulate `sizes` param.
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
		$new_attributes_str = implode( ' ', $string );

		return sprintf( '<img %1$s><noscript>%2$s</noscript>', $new_attributes_str, $matches[0] );
	}

	/**
	 * Footer Lazy Load.
	 */
	public function footer_lazyload() {
		?>

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
	<?php
	}

	/**
	 * Load dynamic css
	 */
	public function load_dynamic_css_style() {
		echo '<style type="text/css" id="mobile-boster-css">';
		echo '.mob-booster-footer {background-color: ' . get_option( 'mb_footer_bg_color', '#222' ) . '; height: ' . get_option( 'mb_footer_height', '40' ) . 'px; }';
		echo '.mob-booster-footer a {color: ' . get_option( 'mb_footer_text_color', '#FFF' ) . '; line-height: ' . get_option( 'mb_footer_height', '40' ) . 'px;}';
		echo '.woocommerce-message { bottom: ' . get_option( 'mb_footer_height', '40' ) . 'px;}';
		echo '</style>';
	}

	/**
	 * Load quicklink on load
	 */
	public function load_quicklink() {
		?>
			<script>
				window.addEventListener('load', () => {
					quicklink();
				});
			</script>
		<?php
	}

	/**
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
