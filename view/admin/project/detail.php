<?php

namespace wp_whise\view\admin\project;

use wp_whise\model\Project;

global $post;

$project = new Project();

$project->set_post( $post->ID );

?>
<table class="form-table">

    <tbody>
    <tr>
        <th scope="row"><label for="estate_id"><?php _e( 'Project ID', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_estate_id" type="text" id="estate_id" value="<?php echo $project->get_meta('_estate_id'); ?>" class="regular-text">
            <p class="description" id="tagline-description"><?php _e( 'The project Estate ID in Whise.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="address"><?php _e( 'Address', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_address" type="text" id="address" value="<?php echo $project->get_meta('_address'); ?>" class="regular-text">
            <p class="description" id="tagline-description"><?php _e( 'The project address.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="number"><?php _e( 'Number', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_number" type="text" id="number" value="<?php echo $project->get_meta('_number'); ?>" class="regular-text">
            <p class="description" id="tagline-description"><?php _e( 'The project address number.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="box"><?php _e( 'Box', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_box" type="text" id="box" value="<?php echo $project->get_meta('_box'); ?>" class="regular-text">
            <p class="description" id="tagline-description"><?php _e( 'The project address box.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="zip"><?php _e( 'Zip', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_zip" type="text" id="zip" value="<?php echo $project->get_meta('_zip'); ?>" class="regular-text">
            <p class="description" id="tagline-description"><?php _e( 'The project zip.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="city"><?php _e( 'City', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_city" type="text" id="city" value="<?php echo $project->get_meta('_city'); ?>" class="regular-text">
            <p class="description" id="tagline-description"><?php _e( 'The project city.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="buildyear"><?php _e( 'Build year', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_buildyear" type="text" id="buildyear" value="<?php echo $project->get_meta('_buildyear'); ?>" class="regular-text" aria-describedby="tagline-description">
            <p class="description" id="tagline-description"><?php _e( 'The year project was released.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    <tr>
        <th scope="row"><label for="area"><?php _e( 'Area', 'wp_whise' ); ?></label></th>
        <td>
            <input name="_area" type="text" id="area" value="<?php echo $project->get_meta('_area'); ?>" class="regular-text">
            <p class="description" id="tagline-description"><?php _e( 'The project area.', 'wp_whise' ); ?></p>
        </td>
    </tr>

    </tbody>
</table>