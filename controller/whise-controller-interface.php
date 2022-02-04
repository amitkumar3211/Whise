<?php

namespace wp_whise\controller;

use wp_whise\controller\adapter\Whise_Adapter_Interface;
use wp_whise\controller\log\Log_Controller_Interface;

Interface Whise_Controller_Interface {

	/**
	 * Whise_Controller constructor.
	 *
	 * @param Whise_Adapter_Interface $whise_adapter
	 * @param Log_Controller_Interface $log
	 * @param string $client_id
	 */
	public function __construct( Whise_Adapter_Interface $whise_adapter, Log_Controller_Interface $log, $client_id );

	/**
	 * Returns estate categories or false if something went wrong
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_estate_categories();

	/**
	 * Returns estates from whise or returns false if there is an error
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_estates();

	/**
	 * Returns projects from whise or returns false if there is an error
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_projects();

	/**
	 * Returns estates that were last updated the past day
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_estates_updated_last_day();
}