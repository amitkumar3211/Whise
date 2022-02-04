<?php

namespace wp_whise\controller\adapter;

interface Whise_Adapter_Interface {

	/**
	 * GET data from the Whise webservice
	 *
	 * @param $method       string
	 * @param $parameter    string
	 * @param $request      string
	 */
	public function get( $method, $parameter, $request );

	/**
	 * POST data to Whise webservice
	 *
	 * @param $method       string
	 * @param $parameter    string
	 * @param $body         string
	 */
	public function post( $method, $parameter, $body );
}