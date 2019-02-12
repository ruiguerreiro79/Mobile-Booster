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
}
