<?php

namespace wp_whise\config;

class Plugin_Deactivate {

	/**
	 * Runs when the plugin is disabled.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		register_deactivation_hook( WP_WHISE_FILE, array( $this, 'deactive' ) );
	}

	/**
	 * Remove the cron job event to get navision products
	 *
	 * @since 1.0.0
	 */
	public function deactive() {
		wp_clear_scheduled_hook( 'whise_integration' );

		wp_clear_scheduled_hook( 'clear_whise_log' );
	}
}