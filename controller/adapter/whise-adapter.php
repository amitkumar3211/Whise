<?php

namespace wp_whise\controller\adapter;

class Whise_Adapter implements Whise_Adapter_Interface {

	CONST URL = 'http://webservices.whoman2.be/websiteservices/EstateService.svc/';

	/**
	 * GET data from the Whise webservice
	 *
	 * @param $method       string
	 * @param $parameter    string
	 * @param $request      string
	 *
	 * @return array|\WP_Error
	 *
	 * @since 1.0.0
	 */
	public function get( $method, $parameter, $request ) {
		$url = static::URL . $method . '?' . $parameter . '=' . $request;

		$response = wp_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return json_decode( wp_remote_retrieve_body( $response ) );
	}

	/**
	 * POST data to Whise webservice
	 *
	 * @param $method       string
	 * @param $parameter    string
	 * @param $body         string
	 *
	 * @return array|\WP_Error
	 *
	 * @since 1.0.0
	 */
	public function post( $method, $parameter, $body ) {
		$url = static::URL . $method;

		$body_data = '{"' . $parameter . '":' . json_encode( $body ) . '}';

		$data = array(
			'headers' => array(
				'Content-Type'   => 'application/json',
				'Content-Length' => strlen( $body_data )
			),
			'body'    => $body_data
		);

		$response = wp_remote_post( $url, $data );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return json_decode( wp_remote_retrieve_body( $response ) );
	}
}