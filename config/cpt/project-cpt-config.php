<?php

namespace wp_whise\config\cpt;

use wp_whise\model\Project;

class Project_Cpt_Config {

	/**
	 * POST type
	 *
	 * @since 1.0.0
	 */
	CONST POST_TYPE = 'project';

	/**
	 * Project_Cpt_Config constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->register_post_type();

		add_action( 'add_meta_boxes_' . static::POST_TYPE, array( $this, 'project_add_meta_boxes' ) );

		add_action( 'save_post_' . static::POST_TYPE, array( $this, 'project_save_meta_box_data' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Load scripts and style for project custom post type
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts() {
		global $post_type;

		if ( static::POST_TYPE == $post_type ) {
			wp_enqueue_media();

			wp_enqueue_script( 'whise-gallery-js', WP_WHISE_URL . 'js/admin/gallery.js', array(
				'media-models'
			), WP_WHISE_VERSION );

			wp_enqueue_script( 'whise-document-js', WP_WHISE_URL . 'js/admin/document.js', array(
				'media-models'
			), WP_WHISE_VERSION );

			wp_enqueue_style( 'whise-estate-css', WP_WHISE_URL . 'css/admin/estate.css' );
		}
	}

	/**
	 * Register custom post type Project
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {
		$labels_project = array(
			'name'               => __( 'Project', 'wp_whise' ),
			'singular_name'      => __( 'Project', 'wp_whise' ),
			'add_new'            => __( 'Add New', 'wp_whise' ),
			'add_new_item'       => __( 'Add New Project Item', 'wp_whise' ),
			'edit_item'          => __( 'Edit Project Item', 'wp_whise' ),
			'new_item'           => __( 'New Project Item', 'wp_whise' ),
			'view_item'          => __( 'View Project Item', 'wp_whise' ),
			'search_items'       => __( 'Search Project', 'wp_whise' ),
			'not_found'          => __( 'Nothing found', 'wp_whise' ),
			'not_found_in_trash' => __( 'Nothing found in Trash', 'wp_whise' ),
			'parent_item_colon'  => ''
		);

		$args_project = array(
			'labels'             => $labels_project,
			'description'        => __( 'Project per category', 'wp_whise' ),
			'public'             => true,
			'menu_position'      => 8,
			'menu_icon'          => 'dashicons-admin-multisite',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'query_var'          => true,
            'rewrite'           => array('slug' => 'projecten'),
			'has_archive'        => true,
			'capability_type'    => 'post',
			'hierarchical'       => false,
			'taxonomies'         => array(),
			'supports'           => array( 'title', 'thumbnail', 'editor', 'excerpt' )
		);

		register_post_type( static::POST_TYPE, $args_project );
	}

	/**
	 * Add metaboxes for project content and gallery
	 *
	 * @param $post
	 *
	 * @since 1.0.0
	 */
	public function project_add_meta_boxes( $post ) {
		add_meta_box( 'woocommerce-product-images', __( 'Gallery', 'wp_whise' ), array(
			$this,
			'gallery_build_meta_box'
		), static::POST_TYPE, 'advanced', 'low' );

		add_meta_box( 'woocommerce-product-documents', __( 'Documents', 'wp_whise' ), array(
			$this,
			'documents_build_meta_box'
		), static::POST_TYPE, 'advanced', 'low' );

		add_meta_box( 'project_meta_box', __( 'Project content', 'wp_whise' ), array(
			$this,
			'project_build_meta_box'
		), static::POST_TYPE, 'advanced', 'low' );

        add_meta_box( 'project-management', __( 'Management', 'wp_whise' ), array(
            $this,
            'project_management_meta_box'
        ), static::POST_TYPE, 'side', 'low' );
	}

	/**
	 * Display gallery meta box for project post type
	 *
	 * @param $post
	 *
	 * @since 1.0.0
	 */
	public function gallery_build_meta_box( $post ) {
		include_once WP_WHISE_DIR . 'view/admin/project/gallery.php';
	}

	/**
	 * Display gallery meta box for project post type
	 *
	 * @param $post
	 *
	 * @since 1.0.0
	 */
	public function documents_build_meta_box( $post ) {
		include_once WP_WHISE_DIR . 'view/admin/project/document.php';
	}

	/**
	 * Display project content meta box
	 *
	 * @param $post
	 *
	 * @since 1.0.0
	 */
	public function project_build_meta_box( $post ) {
		include_once WP_WHISE_DIR . 'view/admin/project/detail.php';
	}

	/**
	 * Store custom field meta box data
	 *
	 * @param int $post_id The post ID.
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
	 *
	 * @since 1.0.0
	 */
	function project_save_meta_box_data( $post_id ) {
		// return if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		/**
		 * Get Project model
		 * @var \wp_whise\model\Project
		 */
		$project = new Project();
		$project->set_post( $post_id );

		/**
		 * Catch gallery image ids
		 */
		$attachment_ids = isset( $_POST['product_image_gallery'] ) ? array_filter( explode( ',', $_POST['product_image_gallery'] ) ) : array();

		/**
		 * Set gallery image ids
		 */
		$image_ids = wp_parse_id_list( $attachment_ids );
		$project->set_gallery_image_ids( $image_ids );

		/**
		 * Catch document IDs
		 */
		$attachment_ids = isset( $_POST['product_document'] ) ? array_filter( explode( ',', $_POST['product_document'] ) ) : array();

		/**
		 * Set document IDs
		 */
		$document_ids = wp_parse_id_list( $attachment_ids );
		$project->set_document_ids( $document_ids );

		$project->set_post_data( $_POST );

		/**
		 * Save Project
		 */
		$project->save();
	}

    /**
     * Display parent meta box to select teammember
     *
     * @param $post
     *
     * @since 1.0.0
     */
    public function project_management_meta_box( $post ) {
        include_once WP_WHISE_DIR . 'view/admin/estate/project-management.php';
    }
}