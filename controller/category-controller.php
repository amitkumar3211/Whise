<?php

namespace wp_whise\controller;

use wp_whise\controller\log\Log_Controller_Interface;
use wp_whise\lib\helper;

class Category_Controller implements Category_Controller_Interface {

	public $whise_controller;
	public $log;

	public $categories;

	public function __construct( Whise_Controller_Interface $whise_controller, Log_Controller_Interface $log ) {
		$this->whise_controller = $whise_controller;
		$this->log              = $log;
	}

	/**
	 * Get estates from the Whise service
	 *
	 * @return bool
	 *
	 * @since 1.0.0
	 */
	public function get() {
		$this->categories = $this->whise_controller->get_estate_categories();

		return $this->categories;
	}

	/**
	 * Process the categories from the Whise service
	 *
	 * @since 1.0.0
	 */
	public function process() {
		if ( false !== $this->categories && is_array( $this->categories ) && isset( $this->categories[0] ) ) {

			foreach (helper::generator( $this->categories ) as $category ) {

				$parent_id = $this->insert_term( $category->Name );

				if ( isset( $category->SubCategoryList ) && count( $category->SubCategoryList ) != 0 ) {
					foreach (helper::generator( $category->SubCategoryList ) as $sub_category ) {
						$term_id = $this->insert_term( $sub_category->Name, $parent_id );

						update_term_meta( $term_id, '_subcategory_id', $sub_category->SubCategoryId );
					}
				}

				update_term_meta( $parent_id, '_category_id', $category->CategoryId );
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Insert term or do nothing
	 *
	 * @param $term_name    string  Term name
	 * @param bool $parent integer Parent ID
	 *
	 * @return integer  Term ID or 0
	 */
	public function insert_term( $term_name, $parent = false ) {
		$term_id = 0;

		$args = array();

		if ( $parent !== false ) {
			$args['parent'] = $parent;
		}

		$term = wp_insert_term( $term_name, 'estate-category', $args );
		if ( is_wp_error( $term ) ) {
			if ( isset( $term->error_data['term_exists'] ) ) {
				$term_id = $term->error_data['term_exists'];
			} else {
				$this->log->error( 'Something went wrong with adding new term: ' . $term_name );
			}
		} else {
			$term_id = $term['term_id'];
		}

		return $term_id;
	}
}