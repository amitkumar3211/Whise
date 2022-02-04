<?php
namespace wp_navision\view\admin;

use wp_whise\config\Settings_Config;

$client_id = get_option('whise_client_id');
?>
    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        <h1>Whise Integrator Settings</h1>

        <p>Give here below the client ID from the Whise platform so the Whise Integrator can collect the posts from it.</p>

        <form id="form-table" method="post">
            <table class="">
                <tr>
                    <td>
                        <label for="client_id">Client ID</label>
                    </td>
                    <td>
                        <input type="text" id="client_id" name="client_id" value="<?php echo $client_id; ?>" class="regular-text">
                    </td>
                </tr>
            </table>


            <p class="submit">
                <button type="submit" class="button button-primary"><?php _e('Save' ); ?></button>
            </p>

        </form>

    </div>