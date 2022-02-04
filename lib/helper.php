<?php

namespace wp_whise\lib;

use wp_whise\config\cpt\Project_Cpt_Config;
use wp_whise\config\cpt\Team_Cpt_Config;

class helper {

	/**
	 * Renames estate response from the webservice to model Estate
	 *
	 * @param $instance
	 * @param $className
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public static function objectToObject( $instance, $className ) {
		return unserialize( sprintf(
			'O:%d:"%s"%s',
			strlen( $className ),
			$className,
			strstr( strstr( serialize( $instance ), '"' ), ':' )
		) );
	}

	/**
	 * Returns estate one by one to save memory
	 *
	 * @param $estates
	 *
	 * @return \Generator
	 *
	 * @since 1.0.0
	 */
	public static function generator( $estates ) {
		foreach ( $estates as $estate ) {
			yield $estate;
		}
	}

	/**
	 * Upload image to wordpress
	 *
	 * @param $path         string  Image path in the FTP /artikels/dsfsdf-1.jpg
	 * @param $post_id   string  Image data
	 *
	 * @return int|\WP_Error
	 *
	 * @since 1.0.0
	 */
	public static function upload_image_to_wordpress( $filename, $path, $post_id ) {
		$upload_dir = wp_upload_dir(); // Set upload folder

		// Check folder permission and define file location
		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		// Create the image  file on the server
		file_put_contents( $file, file_get_contents( $path ) );

		// Check image file type
		$wp_filetype = wp_check_filetype( $filename, null );

		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

		// Include image.php
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

	/**
	 * Check if attachment exists
	 *
	 * @param $file_name
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public static function does_attachment_exist( $file_name ) {
		global $wpdb;

		$query = "SELECT ID 
		FROM " . $wpdb->posts . "
        WHERE post_title = '" . sanitize_file_name( $file_name ) . "' 
        AND post_type = 'attachment' LIMIT 1";

		return $wpdb->get_var( $query );
	}

	/**
	 * Returns terms with category ID from Whise
	 *
	 * @param $taxonomy
	 * @param $meta_keys
	 *
	 * @return array|bool
	 *
	 * @since 1.0.0
	 */
	public static function get_term_meta_by_key( $taxonomy, $meta_keys ) {
		global $wpdb;

		$terms = false;

		if ( is_array( $meta_keys ) ) {
			$query = "SELECT * FROM " . $wpdb->termmeta . " WHERE meta_key in ('" . implode( "','", $meta_keys ) . "')";
		} else {
			$query = "SELECT * FROM " . $wpdb->termmeta . " WHERE meta_key in ('" . $meta_keys . "')";
		}

		$result = $wpdb->get_results( $query );

		if ( $result && count( $result ) != 0 ) {
			$terms = static::rearrange_termmeta_array( $result );
		}

		return $terms;
	}

	/**
	 * Rearrange array by meta_key (_category_id & _subcategory_id)
	 *
	 * @param $metas
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	protected static function rearrange_termmeta_array( $metas ) {
		$response = array();

		foreach ( $metas as $meta ) {
			$response[ $meta->meta_key ][ $meta->meta_value ] = $meta->term_id;
		}

		return $response;
	}

	/**
	 * @param $whise_timestamp
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public static function get_date_time( $whise_timestamp ) {
		/**
		 * Skip if empty
		 */
		if ( empty( $whise_timestamp ) ) {
			return $whise_timestamp;
		}

		preg_match( '/([0-9]+)\+([0-9]+)/', $whise_timestamp, $matches );

		$timestamp = round( $matches[1] / 1000 );

		$date = new \DateTime();
		$date->setTimestamp( $timestamp );

		/**
		 * GMT+2
		 */
		if ( $timezone_string = get_option( 'timezone_string' ) != '' ) {
			$date->setTimezone( new \DateTimeZone( get_option( 'timezone_string' ) ) );
		}

		return $date->getTimestamp();
	}

	/**
	 * Returns array of projects
	 *
	 * @since 1.0.0
	 */
	public static function get_projects() {
		global $wpdb;

		$query = "SELECT * 
					FROM " . $wpdb->posts . " 
					INNER JOIN " . $wpdb->postmeta . "
						ON wp_postmeta.post_id = wp_posts.ID
						AND wp_postmeta.meta_key = '_estate_id'
					WHERE post_type='" . Project_Cpt_Config::POST_TYPE . "'";

		return $wpdb->get_results( $query );
	}

	/**
	 * Returns array of projects
	 *
	 * @since 1.0.0
	 */
	public static function get_teams() {
		$args = array(
			'post_type' => Team_Cpt_Config::POST_TYPE,
		);

		return get_posts( $args );
	}
}