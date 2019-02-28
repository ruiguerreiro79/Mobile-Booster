<?php

if ( ! class_exists( 'WP_Mobile_Booster' ) ) {
	die;
}
class WP_Mobile_Booster_options {
	public function __construct() {
		$this->init_options();
	}

	/**
	 * Init Options
	 */
	public function init_options() {
		add_action( 'admin_menu', array( $this, 'mob_booster_admin_menu' ) );
	}

	/**
	 * Mobile Booster Admin Menu.
	 */
	public function mob_booster_admin_menu() {
		add_menu_page('Page title', 'Mobile Booster', 'manage_options', 'mobile-booster', array( $this, 'mob_booster_options_page' ), 'dashicons-smartphone');
	}

	/**
	 * Mobile Booster Admin Panel Content.
	 */
	public function mob_booster_options_page() {
		// Generate the redirect url.
		$url = add_query_arg( array( 'autofocus[section]' => 'mobile_booster_section' ), admin_url( 'customize.php' ) );
		?>
		<h1>WP Mobile Booster</h1><p class="admin-tagline"><?php printf( __( 'Welcome to WP Mobile Booster. You\'re moments away to  start improving the mobile user engagment of your website! If this is your first time using the plugin, simply go to the WordPress Customizer (or click the button below) and adjust the settings in the <b>Mobile Booster</b> section.', 'mobile-booster' ), esc_url( admin_url( 'customize.php' ) ) ); ?></p>
		<p>It's required to have the WooCommerce plugin installed so you can benefit from the Product Page mobile features.  <a class="button button-primary" href="<?php echo $url; ?>" target="_self">Customize Mobile Booster</a></p>
		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/mobile-booster-screenshot.gif'; ?>">
		<?php
	}

	/**
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
