<?php

namespace wp_whise\config\cpt;

class Team_Cpt_Config {

	/**
	 * POST type
	 *
	 * @since 1.0.0
	 */
	CONST POST_TYPE = 'team';

	/**
	 * Team_Cpt_Config constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->register_taxonomy();
		$this->register_post_type();

        add_filter('title_save_pre', array($this, 'auto_generate_post_title'));
	}

	/**
	 * Register Teamname taxonomy
	 *
	 * @since 1.0
	 */
	protected function register_taxonomy() {
		register_taxonomy(
			'teamname',
			'team',
			array(
				'hierarchical' => true,
				'label' => __('Teamname'),
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array('slug' => 'teamname'),
			)
		);
	}

	/**
	 * Register team post type
	 *
	 * @since 1.0
	 */
	public function register_post_type() {
		$labels_team = array(
			'name' => __('Team', 'wp_whise'),
			'singular_name' => __('Team', 'wp_whise'),
			'add_new' => __('Add New', 'wp_whise'),
			'add_new_item' => __('Add New Team Item', 'wp_whise'),
			'edit_item' => __('Edit Team Item', 'wp_whise'),
			'new_item' => __('New Team Item', 'wp_whise'),
			'view_item' => __('View Team Item', 'wp_whise'),
			'search_items' => __('Search Team', 'wp_whise'),
			'not_found' => __('Nothing found', 'wp_whise'),
			'not_found_in_trash' => __('Nothing found in Trash', 'wp_whise'),
			'parent_item_colon' => ''
		);

		$args_team = array(
			'labels' => $labels_team,
			'description' => __('Team per category', 'wp_whise'),
			'public' => true,
			'menu_position' => 9,
			'menu_icon' => 'dashicons-admin-home',
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'taxonomies' => array('teamname'),
			'supports' => array('')
		);

		register_post_type(static::POST_TYPE, $args_team);
	}

    public function auto_generate_post_title($title)
    {
        global $post;
        if (isset($post->ID)) {
            if (empty($_POST['post_title']) && get_post_type($post->ID) === 'team') {
                // get the current post ID number
                $id = get_the_ID();
                // add ID number with order strong
                $title = get_field("naam", $id);
            }
        }
        return $title;
    }
}

