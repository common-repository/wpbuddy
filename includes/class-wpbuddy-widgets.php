<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPBuddy_Widgets class.
 */

class WPBuddy_Widgets {
		/**
	 * Creates the admin widget.
	 * @since 1.0
	 */
	public function create_admin_widget() {
		wp_add_dashboard_widget(
			'wpbuddy_dashboard_widget',
			'WPBuddy',
			array( self::class, 'render_admin_widget' )
		);
	}

	/**
	 * Renders the admin widget.
	 * @since 1.0
	 */
	public function render_admin_widget() {
		wpbuddy_get_view( 'html-admin-widget' );
	}
}