<?php

namespace wp_whise\controller\cron;

use wp_whise\controller\log\Database_Log_Controller;
use wp_whise\controller\Whise_Controller_Interface;
use wp_whise\lib\Container;

class Cron_Controller implements Cron_Controller_Interface {

	public $log;

	protected $whise_controller;

	/**
	 * @var \wp_whise\controller\Estate_Controller
	 */
	public $estate_controller;

	public $container;

	public function __construct( Whise_Controller_Interface $whise_controller, Database_Log_Controller $log ) {
		$this->whise_controller = $whise_controller;

		$this->log = $log;

		$this->container = Container::getInstance();
	}

	public function get_categories() {
		/**
		 * @var \wp_whise\controller\Category_Controller
		 */
		$category_controller = $this->container->container->get( 'Category_Controller_Interface' );

		$category_controller->get();

		$category_controller->process();
	}

	/**
	 * GET estates from the webservice Whise
	 *
	 * @since 1.0.0
	 */
	public function get_estates() {
		/**
		 * @var \wp_whise\controller\Estate_Controller
		 */
		$estate_controller = $this->container->container->get( 'Estate_Controller_Interface' );

		$estate_controller->get();

		$estate_controller->process();

		return $estate_controller->estates;
	}

	/**
	 * GET projects from the webservice Whise
	 *
	 * @since 1.0.0
	 */
	public function get_projects() {
		/**
		 * @var \wp_whise\controller\Estate_Controller
		 */
		$project_controller = $this->container->container->get( 'Project_Controller_Interface' );

		$project_controller->get();

		$project_controller->process();

		return $project_controller->estates;
	}

}