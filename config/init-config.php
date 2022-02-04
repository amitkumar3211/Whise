<?php

namespace wp_whise\config;

use wp_whise\config\cpt\Estate_Cpt_Config;
use wp_whise\config\cpt\Project_Cpt_Config;
use wp_whise\config\cpt\Team_Cpt_Config;
use wp_whise\lib\Container;

class Init_Config {

	public $container;

	/**
	 * Init_Config constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->container = Container::getInstance();

		$this->container->container->get( 'plugin_activate' );
		$this->container->container->get( 'plugin_deactivate' );

		$this->clear_old_log_data();

		$this->write_log_to_database();

		add_action( 'init', array( $this, 'register_post_type_and_taxonomies' ) );

		add_action( 'whise_integration', array( $this, 'whise_integration' ) );
        // add_action( 'admin_init', array( $this, 'whise_integration' ) );


        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Clears old log data from the database
	 *
	 * @since 1.0.0
	 */
	public function clear_old_log_data() {
		add_action( 'clear_whise_log', array(
				$this->container->container->get( 'Log_Controller_Interface' ),
				'remove_old_rows'
			)
		);
	}

	/**
	 * Writes log to database
	 *
	 * @since 1.0.0
	 */
	public function write_log_to_database() {
		add_action( 'shutdown', array(
			$this->container->container->get( 'Log_Controller_Interface' ),
			'log_to_database'
		) );
	}

	/**
	 * Register custom post type and custom taxonomies
	 *
	 * @since 1.0.0
	 */
	public function register_post_type_and_taxonomies() {
		$this->register_estate_category_taxonomy();
		$this->load_project_cpt();
		$this->load_estate_cpt();
		$this->load_team_cpt();
		$this->load_project_settings_config();
	}

	protected function register_estate_category_taxonomy() {
		$labels = array(
			'name'              => _x( 'Categories', 'taxonomy general name', 'wp_whise' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'wp_whise' ),
			'search_items'      => __( 'Search Categories', 'wp_whise' ),
			'all_items'         => __( 'All Categories', 'wp_whise' ),
			'parent_item'       => __( 'Parent Category', 'wp_whise' ),
			'parent_item_colon' => __( 'Parent Category:', 'wp_whise' ),
			'edit_item'         => __( 'Edit Category', 'wp_whise' ),
			'update_item'       => __( 'Update Category', 'wp_whise' ),
			'add_new_item'      => __( 'Add New Category', 'wp_whise' ),
			'new_item_name'     => __( 'New Category Name', 'wp_whise' ),
			'menu_name'         => __( 'Category', 'wp_whise' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'genre' ),
		);

		register_taxonomy( 'estate-category', array( 'estate', 'project' ), $args );
	}

	/**
	 * Load project custom post type
	 *
	 * @since 1.0.0
	 */
	protected function load_project_cpt() {
		new Project_Cpt_Config();
	}

	/**
	 * Load estate custom post type
	 *
	 * @since 1.0.0
	 */
	protected function load_estate_cpt() {
		new Estate_Cpt_Config();
	}

	/**
	 * Load team custom post type
	 *
	 * @since 1.0.0
	 */
	protected function load_team_cpt() {
		new Team_Cpt_Config();
	}

	/**
	 * Load project settings config
	 *
	 * @since 1.0.0
	 */
	protected function load_project_settings_config() {
		new Project_Settings_Config();
	}

	/**
	 * Runs cronjob to process Whise data into the WordPress
	 *
	 * @since 1.0.0
	 */
	public function whise_integration() {
		$container = Container::getInstance();

		$cron = $container->container->get( 'Cron_Controller_Interface' );

		$cron->get_categories();

		$cron->get_projects();

		$cron->get_estates();
	}

	/**
	 * Add admin menu
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		new Settings_Config();
	}
}