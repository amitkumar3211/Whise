<?php

namespace wp_whise\config;

use wp_whise\lib\Container;

class Plugin_Activate {

	/**
	 * Runs when the plugin is activated
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		register_activation_hook( WP_WHISE_FILE, array( $this, 'activation' ) );
	}

	/**
	 * Schedules cronjob event to get products from navision ftp
	 *
	 * @since 1.0.0
	 */
	public function activation() {
		if ( ! wp_next_scheduled( 'whise_integration' ) ) {
			wp_schedule_event( strtotime( '02:00:00' ), 'hourly', 'whise_integration' );
		}

		if ( ! wp_next_scheduled( 'clear_whise_log' ) ) {
			wp_schedule_event( strtotime( '02:00:00' ), 'weekly', 'clear_whise_log' );
		}

		$this->create_log_table();
	}

	/**
	 * Creates log table
	 *
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.0.0
	 */
	public function create_log_table() {
		$container = Container::getInstance();

		/**
		 * @var \wp_whise\controller\log\Database_Log_Controller
		 */
		$log = $container->container->get( 'Log_Controller_Interface' );

		$log->create_log_table();
	}
}