<?php

namespace wp_whise\lib;

use \DI;
use \DI\ContainerBuilder;

Final class Container implements Container_Interface {

	/**
	 * @var \DI\ContainerBuilder
	 */
	protected $builder;

	/**
	 * @var \DI\Container
	 */
	public $container;

	/**
	 * @var Container
	 */
	protected static $instance;

	/**
	 * Build Container.
	 */
	public function __construct() {
		$this->builder = new ContainerBuilder();

		$this->build_container();

		$this->set_classes();
	}

	/**
	 * Instance of this class
	 *
	 * @return Container
	 */
	public static function getInstance() {
		if ( null == static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Build Container
	 */
	public function build_container() {
		$this->builder->addDefinitions( array(
			'plugin_activate'          => DI\object( 'wp_whise\config\Plugin_Activate' ),
			'plugin_deactivate'        => DI\object( 'wp_whise\config\Plugin_Deactivate' ),
			'Log_Controller_Interface' => DI\object( 'wp_whise\controller\log\Database_Log_Controller' )
		) );

		$this->container = $this->builder->build();
	}

	/**
	 * Set classes that needs to be used
	 *
	 * TODO extend this with model, controllers and config
	 * TODO load Whise API key through settings
	 */
	public function set_classes() {
		$this->container->set( 'init_config', DI\object( 'wp_whise\config\Init_Config' )->constructor( $this ) );

		$this->container->set( 'Whise_Adapter_Interface', DI\object( 'wp_whise\controller\adapter\Whise_Adapter' ) );

		$this->container->set( 'Whise_Controller_Interface',
			DI\object( 'wp_whise\controller\Whise_Controller' )
				->constructor(
					$this->container->get( 'Whise_Adapter_Interface' ),
					$this->container->get( 'Log_Controller_Interface' ),
					get_option('whise_client_id')
				)
		);

		$this->container->set( 'Category_Controller_Interface',
			DI\object( 'wp_whise\controller\Category_Controller' )
				->constructor(
					$this->container->get( 'Whise_Controller_Interface' ),
					$this->container->get( 'Log_Controller_Interface' )
				)
		);


		$this->container->set( 'Project_Controller_Interface',
			DI\object( 'wp_whise\controller\Project_Controller' )
				->constructor(
					$this->container->get( 'Whise_Controller_Interface' ),
					$this->container->get( 'Log_Controller_Interface' )
				)
		);

		$this->container->set( 'Estate_Controller_Interface',
			DI\object( 'wp_whise\controller\Estate_Controller' )
				->constructor(
					$this->container->get( 'Whise_Controller_Interface' ),
					$this->container->get( 'Log_Controller_Interface' )
				)
		);

		$this->container->set( 'Cron_Controller_Interface',
			DI\object( 'wp_whise\controller\cron\Cron_Controller' )
				->constructor(
					$this->container->get( 'Whise_Controller_Interface' ),
					$this->container->get( 'Log_Controller_Interface' )
				)
		);
	}
}