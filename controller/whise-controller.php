<?php

namespace wp_whise\controller;

use wp_whise\controller\adapter\Whise_Adapter;
use wp_whise\controller\adapter\Whise_Adapter_Interface;
use wp_whise\controller\log\Log_Controller_Interface;

class Whise_Controller implements Whise_Controller_Interface {

	/**
	 * @var Whise_Adapter
	 */
	public $whise_adapter;

	/**
	 * @var Log_Controller_Interface
	 */
	public $log;

	/**
	 * Whise SSID
	 *
	 * @var string
	 */
	private $client_id;

	/**
	 * Whise_Controller constructor.
	 *
	 * @param Whise_Adapter_Interface $whise_adapter
	 * @param Log_Controller_Interface $log
	 * @param string $client_id
	 */
	public function __construct( Whise_Adapter_Interface $whise_adapter, Log_Controller_Interface $log, $client_id ) {
		$this->whise_adapter = $whise_adapter;
		$this->log           = $log;
		$this->client_id     = $client_id;
	}

	/**
	 * Returns estate categories or false if something went wrong
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_estate_categories() {
		$args = '{"ClientId":"' . $this->client_id . '","Language":"nl-BE"}';

		$response = $this->whise_adapter->get( 'GetCategoryList', 'EstateServiceGetCategoryListRequest', $args );

		if ( is_wp_error( $response ) ) {
			return $this->error( $response );
		}

		return $response->d->CategoryList;
	}

	/**
	 * Returns estates from whise or returns false if there is an error
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_estates() {
		$args = '{"ClientId":"' . $this->client_id . '","Language":"nl-BE"}';

		$response = $this->whise_adapter->get( 'GetEstateList', 'EstateServiceGetEstateListRequest', $args );

		if ( is_wp_error( $response ) ) {
			return $this->error( $response );
		}

		return $response->d->EstateList;
	}

	/**
	 * Returns estate projects from whise or returns false if there is an error
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_projects() {
		$args = '{"ClientId":"' . $this->client_id . '","Language":"nl-BE","IsTopParent":true}';

		$response = $this->whise_adapter->get( 'GetEstateList', 'EstateServiceGetEstateListRequest', $args );

		if ( is_wp_error( $response ) ) {
			return $this->error( $response );
		}

		return $response->d->EstateList;
	}

	/**
	 * Returns estates that were last updated the past day
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get_estates_updated_last_day() {
		$from = strtotime( '-1 day' ) * 1000;
		$to   = strtotime( 'now' ) * 1000;

		$args = '{"ClientId":"' . $this->client_id . '","Language":"nl-BE","UpdateDateTimeRange":["/Date(' . $from . ')/", "/Date(' . $to . ')/"]}';

		$response = $this->whise_adapter->get( 'GetEstateList', 'EstateServiceGetEstateListRequest', $args );

		if ( is_wp_error( $response ) ) {
			return $this->error( $response );
		}

		return $response->d->EstateList;
	}

	/**
	 * @param $response \WP_Error
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	private function error( $response ) {
		$msg = $response->get_error_code() . ':' . $response->get_error_message();

		$this->log->error( $msg );

		return false;
	}
}