<?php

namespace wp_whise\config;

class Project_Settings_Config {

	CONST OPTION_NAME = 'wp_whise_project_settings';

	/**
	 * Register submenu for project settings
	 *
	 * Project_Settings_Config constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_projecten_submenu_page' ) );
	}

	/**
	 * Adds new submenu to project post type
	 *
	 * @since 1.0.0
	 */
	public function register_projecten_submenu_page() {
		add_submenu_page( 'edit.php?post_type=project', __( 'Settings', 'compass' ), __( 'Settings', 'compass' ), 'manage_options', 'project', array(
			$this,
			'adminPanel'
		) );
	}

	/**
	 * Displays submenu form + save the results
	 *
	 * @since 1.0.0
	 */
	public function adminPanel() {
		if ( isset( $_POST['security'] ) || wp_verify_nonce( $_POST['security'], 'project_settings_nonce' ) && isset( $_POST['title'] ) && isset( $_POST['description'] ) ) {
			$option['title']       = $_POST["title"];
			$option['description'] = $_POST["description"];
			$option_to_json        = json_encode( $option );
			update_option( static::OPTION_NAME, $option_to_json );
		}

		$options = get_option( static::OPTION_NAME );
		if ( $options ) {
			$options = json_decode( $options, true );
		} else {
			$options['title']       = '';
			$options['description'] = '';
		}

		?>
        <div class="wrap">
            <h2><?php _e( 'Settings' ); ?></h2>
            <div class="inside">
                <form method="post">
					<?php wp_nonce_field( 'project_settings_nonce', 'security' ); ?>
                    <label for="title"><?php _e('Title'); ?></label>
                    <input type="text" name="title" id="title" value="<?php echo $options['title']; ?>"
                           style="width: 100%;"/>

                    <label for="description"><?php _e('Description'); ?></label>
                    <textarea type="text" name="description" id="description" style="width: 100%;"
                              rows="10"><?php echo $options['description']; ?></textarea>
                    <input type="submit" class="button button-primary" name="save"
                           value="<?php _e( 'Save' ); ?>">
                </form>
            </div>

        </div>
		<?php

	}
}