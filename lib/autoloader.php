











<?php

/**
 * Class Appsaloon_Autoloader
 *
 * @since 1.1.0
 */
class Appsaloon_Autoloader {

	/**
	 * plugin root namespace
	 *
	 * @sice 1.1.0
	 */
	const ROOT_NAMESPACE = 'wp_whise\\';

	/**
	 * Register autoload method
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'appsaloon_autoloader_callback' ) );
	
	}

	/**
	 * Includes file from the correct namespace
	 * else it will do nothing
	 *
	 * @param $class
	 *
	 * @since 1.1.0
	 */
	public function appsaloon_autoloader_callback($class) {
		if ( strpos( $class, self::ROOT_NAMESPACE ) === 0 ) {
			$path = substr( $class, strlen( self::ROOT_NAMESPACE ) );
			$path = strtolower( $path );
			$path = str_replace( '_', '-', $path );
			$path = str_replace( '\\', DIRECTORY_SEPARATOR, $path ) . '.php';
			$path = WP_WHISE_DIR . DIRECTORY_SEPARATOR . $path;

			if ( file_exists( $path ) ) {
				include $path;
			}
		}
	}
}


add_action( 'wp_ajax_my_action', 'my_action' );

function my_action() {
	global $wpdb; // this is how you get access to the database

	$title = $_REQUEST['title'];
		$name = $_REQUEST['name'];
		$content = $_REQUEST['content'];
		$eState = $_REQUEST['estateid'];
		$price = $_REQUEST['price'];
		$address = $_REQUEST['address'];
		$number = $_REQUEST['number'];
		$box = $_REQUEST['box'];
		$zip = $_REQUEST['zip'];
		$city = $_REQUEST['city'];
		$area = $_REQUEST['area'];
		$createDateTime = $_REQUEST['createDateTime'];
		
		
		
		echo 	$eState;

		global $wpdb;
		$wpdb->insert(
			$wpdb->prefix.'posts',
			array(
				'ID' => $eState,
				'post_title' => $title,
				'post_content' => $content,
				'post_status' => 'publish',
				'post_type' => 'estate',
				'post_name' => $name,
				
			)
		);
    update_post_meta($eState,'_estate_id',$eState );
    update_post_meta($eState,'_price',$price );
    update_post_meta($eState,'_address',$address );
    update_post_meta($eState,'_number',$number );
    update_post_meta($eState,'_box',$box );
    update_post_meta($eState,'_zip',$zip );
    update_post_meta($eState,'_city',$city );
    update_post_meta($eState,'_area',$area );
    update_post_meta($eState,'_buildyear',$createDateTime );
        
        
        
        

	wp_die(); // this is required to terminate immediately and return a proper response
}


/**
 * Start autoloader
 *
 * @since 1.1.0
 */
new Appsaloon_Autoloader();