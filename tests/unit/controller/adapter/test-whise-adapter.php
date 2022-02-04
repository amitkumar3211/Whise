<?php

namespace wp_whise\tests\unit\controller\adapter;

/**
 * Class SampleTest
 *
 * @package SampleTest
 */

use wp_whise\controller\adapter\Whise_Adapter;

/**
 * Sample test case.
 *
 * @group whise
 * @group unit
 * @covers \wp_whise\controller\adapter\Whise_Adapter
 */
class Test_Whise_Adapter extends \WP_UnitTestCase {

	CONST CLIENT_ID = '1829c9494c7d4340a152';

	/**
	 * @var \wp_whise\controller\adapter\Whise_Adapter
	 */
	public $whise;

	function setUp() {
		$this->whise = new Whise_Adapter();
	}

	/**
	 * @covers \wp_whise\controller\adapter\Whise_Adapter::get
	 */
	function test_get_estate_list() {
		$args     = '{"ClientId":"' . static::CLIENT_ID . '","Language":"nl-BE"}';
		$response = $this->whise->get( 'GetEstateList', 'EstateServiceGetEstateListRequest', $args );

		$this->assertContains( 'EstateServiceGetEstateListResponse:Whoman.Estate', $response->d->__type );
	}

	/**
	 * @covers \wp_whise\controller\adapter\Whise_Adapter::get
	 */
	function test_get_estate_categories() {
		$args     = '{"ClientId":"' . static::CLIENT_ID . '","Language":"nl-BE"}';
		$response = $this->whise->get( 'GetCategoryList', 'EstateServiceGetCategoryListRequest', $args );

		$this->assertContains( 'EstateServiceGetCategoryListResponse:Whoman.Estate', $response->d->__type );
	}
}
