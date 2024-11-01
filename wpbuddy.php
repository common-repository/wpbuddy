<?php 

/*
Plugin Name: WPBuddy
Plugin URI: https://wpproblemsolvers.com/wpbuddy/
Description: Streamline your customer support process with WP Problem Solvers Support Ticket. This plugin allows admin users on your WordPress site to easily create support tickets from the admin dashboard and integrates seamlessly with the WP Problem Solvers ticket system.
Version: 1.0.4
Requires at least: 5.6
Requires PHP: 7.4
Author: WP Problem Solvers
Author URI: https://wpproblemsolvers.com
License: GPL-2.0+
Text Domain: wpbuddy

------------------------------------------------------------------------
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The filesystem path of the directory that contains
 * the plugin, includes trailing slash.
 *
 * @since 1.0
 *
 * @var string
 */
define( 'WPBUDDY_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPBUDDY_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

/**
 * Class WPBuddy
 * Handles the plugin's initialization.
 * @since 1.0
 */
class WPBuddy {

	private static $instance;
	private $version = '1.0.4';

	/**
	 * Initializes the plugin.
	 * @since 1.0
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * WPBuddy constructor.
	 * @since 1.0
	 */
	private function __construct() {
		$this->include_files();
		$this->admin_hooks();
	}

	/**
	 * Get assets per environment.
	 * @since 1.0.3
	 */
	public function get_assets( $file_name ) {
		if ( defined( 'WPBUDDY_ENV' ) && WPBUDDY_ENV === 'local' ) {
			return add_query_arg( 'time', time(), WPBUDDY_PLUGIN_DIR_URL . 'assets/src/' . $file_name );
		}
	
		$file_name = preg_replace( '/(\.js|\.css)$/', '.min$1', $file_name );
		return WPBUDDY_PLUGIN_DIR_URL . 'assets/dist/' . $file_name;
	}
	
	/**
	 * Includes the files we need.
	 * @since 1.0.3
	 */
	private function include_files() {
    	require_once WPBUDDY_PLUGIN_DIR_PATH . 'includes/wpbuddy-utility-functions.php';
		require_once WPBUDDY_PLUGIN_DIR_PATH . 'includes/class-wpbuddy-checker.php';
		require_once WPBUDDY_PLUGIN_DIR_PATH . 'includes/class-wpbuddy-rest-api.php';
		require_once WPBUDDY_PLUGIN_DIR_PATH . 'includes/class-wpbuddy-widgets.php';
	}

	/**
	 * Run admin hooks.
	 */
	private function admin_hooks() {
		add_action( 'admin_init', array( $this, 'register_admin_settings' ) );
		add_action( 'admin_menu', array( $this, 'create_admin_menu' ), 999 );
		add_action( 'admin_footer', array( $this, 'render_admin_footer' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		// Widgets.
		//add_action( 'wp_dashboard_setup', array( 'WPBuddy_Widgets', 'create_admin_widget' ) );
	}


	/**
	 * Enqueues the admin scripts.
	 * @since 1.0
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_style(
			'wpbuddy-admin',
			$this->get_assets( 'wpbuddy-admin.css' ),
			array(),
			$this->version,
		);

		wp_enqueue_script(
			'wpbuddy-admin',
			$this->get_assets( 'wpbuddy-admin.js' ),
			array( 'jquery' ),
			$this->version,
			true
		);

		wp_localize_script(
			'wpbuddy-admin',
			'WPBuddy',
			array(
				'root' => esc_url_raw( rest_url() ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			)
		);
	}

	/**
	 * Renders the admin footer.
	 * @since 1.0
	 */
	public function render_admin_footer() {
		global $pagenow;
		?>
		<?php if ( '1' === get_option( 'wpbuddy_global' ) || $pagenow === 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] === 'wpbuddy' ) : ?>
			<?php wpbuddy_get_view( 'html-admin-chat' ); ?>
		<?php endif; ?>
		<?php
	}

	/**
	 * Creates admin menu.
	 */
	public function create_admin_menu() {
		$wpbuddy_icon = 'data:image/svg+xml;base64,PHN2ZyBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2OSA1OCI+PHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik01OC41IDUuNzY0aC00OGE1IDUgMCAwIDAtNSA1djM2Ljk3MmE1IDUgMCAwIDAgNSA1aDQ4YTUgNSAwIDAgMCA1LTVWMTAuNzY0YTUgNSAwIDAgMC01LTVabS00OC01Yy01LjUyMyAwLTEwIDQuNDc3LTEwIDEwdjM2Ljk3MmMwIDUuNTIzIDQuNDc3IDEwIDEwIDEwaDQ4YzUuNTIzIDAgMTAtNC40NzcgMTAtMTBWMTAuNzY0YzAtNS41MjMtNC40NzctMTAtMTAtMTBoLTQ4WiIgZmlsbD0iI2ZmZiIvPjxwYXRoIGQ9Ik0yNi41IDM2LjVjLTEuMTA1IDAtMi4wMi45MDQtMS44MDIgMS45ODcuOTIxIDQuNTcxIDQuOTYgOC4wMTMgOS44MDIgOC4wMTNzOC44ODEtMy40NDIgOS44MDMtOC4wMTNjLjIxOC0xLjA4My0uNjk4LTEuOTg3LTEuODAzLTEuOTg3aC0xNlptLTEzLTE5Ljk1MmE0LjU0OCA0LjU0OCAwIDAgMSA5LjA5NiAwdjEwLjkwNGE0LjU0OCA0LjU0OCAwIDEgMS05LjA5NiAwVjE2LjU0OFptMzIuOTA0IDBhNC41NDggNC41NDggMCAxIDEgOS4wOTYgMHYxMC45MDRhNC41NDggNC41NDggMCAxIDEtOS4wOTYgMFYxNi41NDhaIiBmaWxsPSIjZmZmIi8+PC9zdmc+';
		add_menu_page(
			'WPBuddy',
			'WPBuddy',
			'manage_options',
			'wpbuddy',
			array( $this, 'render_admin_page' ),
			$wpbuddy_icon,
			3
		);
	}

	/**
	 * Renders the admin page.
	 * @since 1.0
	 */
	public function render_admin_page() {

		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'news';
		wpbuddy_get_view( 'html-admin-navigation', array( 'tab' => $tab ) );
		?>
		<div class="wrap wpbuddy">
			<?php
				switch ( $tab ) {
					case 'news':
						wpbuddy_get_view( 'html-admin-tab-news' );
						break;
					case 'settings':
						wpbuddy_get_view( 'html-admin-tab-settings' );
						break;
					case 'support':
						wpbuddy_get_view( 'html-admin-tab-support' );
						break;
					default:
						wpbuddy_get_view( 'html-admin-tab-home' );
						break;
				}
			?>
		</div>
		<?php
	}

	/**
	 * Registers the settings.
	 * @since 1.0
	 */
	public function register_admin_settings() {

		// Add the settings section.
		add_settings_section(
			'wpbuddy_settings_section',
			'',
			array( $this, 'render_settings_section' ),
			'wpbuddy'
		);

		// Add the settings field.
		add_settings_field(
			'wpbuddy_license_key',
			'License Key',
			array( $this, 'render_license_key_field' ),
			'wpbuddy',
			'wpbuddy_settings_section'
		);

		// Register the setting.
		register_setting(
			'wpbuddy',
			'wpbuddy_license_key',
			array( $this, 'sanitize_license_key' )
		);

		// Checkboxes.
		add_settings_field(
			'wpbuddy_global',
			'Enable WPBuddy Chat Globally',
			array( $this, 'render_checkbox_field' ),
			'wpbuddy',
			'wpbuddy_settings_section'
		);

		// Register the setting.
		register_setting(
			'wpbuddy',
			'wpbuddy_global',
		);

	}

	/**
	 * Renders the settings section.
	 * @since 1.0
	 */
	public function render_settings_section() {
		// Do nothing.
	}

	/**
	 * Renders the API key field.
	 * @since 1.0
	 */
	public function render_license_key_field() {
		// Get the API key.
		$license_key = get_option( 'wpbuddy_license_key' );
		?>
		<input type="text" name="wpbuddy_license_key" size="50" value="<?php echo esc_attr( $license_key ); ?>" />
		<?php
	}

	/**
	 * Renders the checkbox field.
	 * @since 1.0
	 */
	public function render_checkbox_field() {
		// Get the checkbox value.
		$checkbox = get_option( 'wpbuddy_global' );
		?>
		<input type="checkbox" name="wpbuddy_global" value="1" <?php checked( $checkbox, 1 ); ?> />
		<?php
	}
		
}

WPBuddy::get_instance();
