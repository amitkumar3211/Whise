<?php

namespace wp_whise\model;

class Project {

	/**
	 * Project ID
	 *
	 * @var int
	 *
	 * @since 1.0.0
	 */
	public $post_id;

	/**
	 * All project meta data
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	public $post_meta = array();

	/**
	 * Only update meta data
	 * This is handy to save amount of query
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	public $updated_post_meta = array();

	/**
	 * Fields to ignore from the backend fields
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	private $ignore_fields = array(
		'_edit_lock',
		'_edit_last',
		'_wpnonce',
		'_wp_http_referer',
		'_wp_original_http_referer',
		'_thumbnail_id',
		'_ajax_nonce-add-estate-category'
	);

	/**
	 * Project constructor.
	 *
	 * Currently does nothing
	 */
	public function __construct() {

	}

	/**
	 * Get the project data
	 *
	 * @param $post_id
	 *
	 * @since 1.0.0
	 */
	public function set_post( $post_id ) {
		$this->post_id = $post_id;

		$this->post_meta = get_post_meta( $post_id );
	}

	/**
	 * This method is called after saving the project in the backend.
	 *
	 * Here we do filter the meta keys so only custom fields are saved
	 *
	 * @param $post_data    array
	 *
	 * @since 1.0.0
	 */
	public function set_post_data( $post_data ) {
		foreach ( $post_data as $key => $value ) {
			/**
			 * If unique custom field
			 */
			if ( substr( $key, 0, 1 ) === '_'
			     && substr( $key, 0, 4 ) !== '_acf'
			     && ! in_array( $key, $this->ignore_fields )
			) {
				/**
				 * custom field does not exist or the value is not the same
				 */
				if ( ! isset( $this->post_meta[ $key ] ) || $value != $this->post_meta[ $key ] ) {
					$this->post_meta[ $key ]         = $value;
					$this->updated_post_meta[ $key ] = $value;
				}
			}
		}
	}

	/**
	 * Sets gallery image ids
	 *
	 * @param $attachment_ids
	 *
	 * @since 1.0.0
	 */
	public function set_gallery_image_ids( $attachment_ids ) {
		$this->post_meta['_gallery_image_ids'] = $attachment_ids;

		$this->updated_post_meta['_gallery_image_ids'] = $attachment_ids;
	}

	/**
	 * Returns gallery image ids
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function get_gallery_image_ids() {
		return maybe_unserialize( $this->post_meta['_gallery_image_ids'][0] );
	}

	/**
	 * Sets document ids
	 *
	 * @param $attachment_ids
	 *
	 * @since 1.0.0
	 */
	public function set_document_ids( $attachment_ids ) {
		$this->post_meta['_document_ids'] = $attachment_ids;

		$this->updated_post_meta['_document_ids'] = $attachment_ids;
	}

    /**
     * Returns meta value or false if it does not exist
     *
     * @param $meta_key
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function get_meta_by_key( $meta_key ) {
        return ( isset( $this->post_meta[ $meta_key ][0] ) ) ? $this->post_meta[ $meta_key ][0] : false;
    }

	/**
	 * Returns document IDs
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function get_document_ids() {
		return maybe_unserialize( $this->post_meta['_document_ids'][0] );
	}

	/**
	 * Returns meta key
	 * If it does not exist then it will return empty string
	 *
	 * @param $meta_key string  Meta key to look for
	 *
	 * @return string   Meta key or empty string
	 *
	 * @since 1.0.0
	 */
	public function get_meta( $meta_key ) {
		return ( isset( $this->post_meta[ $meta_key ] ) ) ? $this->post_meta[ $meta_key ][0] : '';
	}

	/**
	 * Save post meta
	 *
	 * @since 1.0.0
	 */
	public function save() {
		if ( is_array( $this->updated_post_meta ) && count( $this->updated_post_meta ) != 0 ) {
			foreach ( $this->updated_post_meta as $meta_key => $meta_value ) {
				update_post_meta( $this->post_id, $meta_key, $meta_value );
			}
		}
	}
}