<?php

namespace wp_whise\view\admin\project;

use wp_whise\lib\helper;
use wp_whise\model\Project;

global $post;

$estate = new Project();

$estate->set_post( $post->ID );

$team_id = $estate->get_meta_by_key( '_team_id' );

$teams = helper::get_teams();

$team_link    = false;

?>
<div id="team">
    <p><?php _e( 'Choose Estate Team', 'wp_whise' ) ?></p>
    <select name="_team_id">
        <option value=""><?php _e( 'Choose Team', 'wp_whise' ); ?></option>

		<?php foreach ( $teams as $team ): ?>
			<?php if ( $team_id == $team->ID ): ?>
				<?php $team_link = get_edit_post_link( $team_id ); ?>
			<?php endif; ?>
            <option value="<?php echo $team->ID; ?>" <?php echo ( $team->ID == $team_id ) ? 'selected="selected"' : ''; ?>><?php echo $team->post_title; ?></option>
		<?php endforeach; ?>
    </select>

	<?php if ( $team_link !== false ): ?>
        <p><a href="<?php echo $team_link; ?>" target="_blank">Link to the team</a></p>
	<?php endif; ?>
</div>