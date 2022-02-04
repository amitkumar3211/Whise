<?php

namespace wp_whise\tests\unit\controller\cron;

use wp_whise\controller\adapter\Whise_Adapter;
use wp_whise\controller\cron\Cron_Controller;
use wp_whise\controller\Estate_Controller;
use wp_whise\controller\Whise_Controller;

class Test_Whise_Estates_Cron_Controller extends \WP_UnitTestCase {

	protected $whise_adapter;

	protected $log;

	protected $whise_controller;

	function setUp() {
		$this->whise_adapter    = new Whise_Adapter();
		$this->log              = $this->getMockBuilder( 'wp_whise\controller\log\Database_Log_Controller' )->getMock();
		$this->whise_controller = new Whise_Controller( $this->whise_adapter, $this->log, false );
	}

	/**
	 * @covers \wp_whise\controller\Estate_Controller::get()
	 */
	function test_get_estates() {
		$cron = new Estate_Controller( $this->whise_controller, $this->log );

		$result = $cron->get();

		$this->assertTrue( is_array( $result ) );
	}

	/**
	 * @covers \wp_whise\controller\Estate_Controller::process()
	 */
	function test_process_estates() {
		$cron = new Estate_Controller( $this->whise_controller, $this->log );

		$cron->get();

		$result = $cron->process();

		$this->assertTrue( is_array( $result ) );
	}
}