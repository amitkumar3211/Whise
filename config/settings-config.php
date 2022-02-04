<?php

namespace wp_whise\config;

use wp_whise\controller\log\Database_Log_Controller;
use wp_whise\controller\lists\Log_List;

class Settings_Config {

	CONST LOG_PAGE_SLUG = 'whise-log';

	/**
	 * Add options page for whise log
	 *
	 * Log_Config constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$page = add_options_page(
			'whise_log',
			'Whise Log',
			'manage_options',
			static::LOG_PAGE_SLUG,
			array( $this, 'display_log_page' )
		);

		$page = add_options_page(
			'whise_settings',
			'Whise settings',
			'manage_options',
			'whise-settings',
			array( $this, 'display_settings_page' )
		);

		add_action( 'load-' . $page, array( $this, 'enqueue_log_page_styles' ) );
	}

	/**
	 * Displays the logging admin page
	 *
	 * @since 1.0.0
	 */
	public function display_log_page() {
		if ( isset( $_POST ) && isset( $_POST['truncate'] ) ) {
			global $wpdb;
			$table_name = $wpdb->prefix . Database_Log_Controller::TABLE_NAME;
			$query      = "TRUNCATE TABLE $table_name";
			$wpdb->query( $query );
		}

		//Create an instance of our package class...
		$log_list_table = new Log_List();
		//Fetch, prepare, sort, and filter our data...
		$log_list_table->prepare_items();

		include WP_WHISE_DIR . 'view/admin/settings/log-view.php';
	}

	/**
	 * Displays the logging admin page
	 *
	 * @since 1.0.0
	 */
	public function display_settings_page() {
		if ( isset( $_POST ) && isset( $_POST['client_id'] ) ) {
			update_option( 'whise_client_id', sanitize_text_field( $_POST['client_id'] ) );

			echo '<div class="updated">Settings updated.</div>';
		}

		include WP_WHISE_DIR . 'view/admin/settings/settings-view.php';
	}

	/**
	 * Enqueues the style(s) for the log page
	 *
	 * @since 1.0.0
	 */
	public function enqueue_log_page_styles() {
		wp_enqueue_style( 'navision_log_style', WP_WHISE_URL . 'css/admin/log.css' );
	}
}