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
	/***
	 * Build the Mobile Booster Customizer settings.
	 */
	public function mobile_booster_customizer_settings( $wp_customize ) {

		// Adding Mobile Booster section in WordPress customizer.
		$wp_customize->add_section('mobile_booster_section', array(
			'title' => __( 'Mobile Booster', 'mobile-booster' ),
		));

		// Adding setting for the mobile quiclink.
		$wp_customize->add_setting('mb_quicklink', array(
			'default' => __( 'Enable Quicklink', 'mobile-booster' ),
			'type'    => 'option',
			'default' => 'enable',
		));

		// Adding control for the mobile buy now text.
		$wp_customize->add_control('mb_quicklink', array(
			'label'   => 'Enable Quicklink',
			'section' => 'mobile_booster_section',
			'type'    => 'radio',
			'choices'  => array(
				'enable'  => __( 'Enable', 'mobile-booster' ),
				'disable' => __( 'Disable', 'mobile-booster' ),
			),
		));

		// Adding setting for the mobile buy now text.
		$wp_customize->add_setting('mb_buy_now_text', array(
			'default' => __( 'Buy Now', 'mobile-booster' ),
			'type'    => 'option',
		));

		// Adding control for the mobile buy now text.
		$wp_customize->add_control('mb_buy_now_text', array(
			'label'   => 'Mobile Footer Buy Now Text',
			'section' => 'mobile_booster_section',
			'type'    => 'text',
		));

		// Adding setting for the mobile footer height.
		$wp_customize->add_setting('mb_footer_height', array(
			'default' => 40,
			'type'    => 'option',
		));

		// Adding control for the mobile footer height.
		$wp_customize->add_control( 'mb_footer_height', array(
			'label'   => 'Mobile Footer Height (pixels)',
			'section' => 'mobile_booster_section',
			'type'    => 'number',
		));


		// Adding setting for the mobile footer background color.
		$wp_customize->add_setting('mb_footer_bg_color', array(
			'default'           => '#222222',
			'sanitize_callback' => 'sanitize_hex_color',
			'type'              => 'option',
		));

		// Adding control for the mobile footer background color.
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mb_footer_bg_color',
				array(
					'label'    => __( 'Mobile Footer Background Color', 'mobile-booster' ),
					'section'  => 'mobile_booster_section',
					'priority' => 10,
				)
			)
		);

		// Adding setting for the mobile footer text color.
		$wp_customize->add_setting('mb_footer_text_color', array(
			'default'           => '#fffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'type'              => 'option',
		));

		// Adding control for the mobile footer text color.
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'mb_footer_text_color',
				array(
					'label'    => __( 'Mobile Footer Text Color', 'mobile-booster' ),
					'section'  => 'mobile_booster_section',
					'priority' => 10,
				)
			)
		);
	}
}
