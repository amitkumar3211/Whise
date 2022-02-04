<?php
/*
Plugin Name: Whise integration
Plugin URI:
Description: Synchronise WordPress with Whise service
Version: 1.0
Author: AppSaloon
Author URI: https://www.appsaloon.be/
License: GPL3
*/



namespace wp_whise;

use wp_whise\lib\Container;
use wp_whise\lib\Container_Interface;

define( 'WP_WHISE_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_WHISE_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_WHISE_FILE', __FILE__ );
define( 'WP_WHISE_VERSION', '1.0' );

/**
 * Register autoloader to load files/classes dynamically
 */
include_once WP_WHISE_DIR . 'lib/autoloader.php';

/**
 * Load composer/PHP-DI container
 *
 * FYI vendor files are moved from /vendor to /lib/ioc/ directory
 *
 * "php-di/php-di": "5.0"
 */
include_once WP_WHISE_DIR . 'lib/ioc/autoload.php';

class Plugin_Boilerplate {

	/**
	 * Plugin_Boilerplate constructor.
	 *
	 * @param Container_Interface $container
	 */
	public function __construct( Container_Interface $container ) {
		$container->container->get( 'init_config' );

          
	}
	
	
	
	
	
	
}

new Plugin_Boilerplate( Container::getInstance() );

 wp_enqueue_script( 'custom', WP_WHISE_URL.'/ap.js', true );

?>




