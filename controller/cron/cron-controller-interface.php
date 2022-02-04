<?php

namespace wp_whise\controller\cron;

use wp_whise\controller\log\Database_Log_Controller;
use wp_whise\controller\Whise_Controller;
use wp_whise\controller\Whise_Controller_Interface;
use wp_whise\lib\Container;
use wp_whise\lib\helper;

Interface Cron_Controller_Interface {


	public function __construct( Whise_Controller_Interface $whise_controller, Database_Log_Controller $log );

	/**
	 * GET categories from the webservice Whise
	 *
	 * @since 1.0.0
	 */
	public function get_categories();

	/**
	 * GET estates from the webservice Whise
	 *
	 * @since 1.0.0
	 */
	public function get_estates();

}