<?php

namespace wp_whise\view\admin\project;

use wp_whise\model\Estate;
use wp_whise\lib\helper;

global $post;

$estate = new Estate();

$estate->set_post( $post->ID );

$parent_id = $estate->get_meta_by_key( '_parent_id' );

$team_id = $estate->get_meta_by_key( '_team_id' );

$projects = helper::get_projects();

$teams = helper::get_teams();

$project_link = false;
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
<div id="project">
    <p><?php _e( 'Choose Estate Project', 'wp_whise' ) ?></p>
    <select name="_parent_id">
        <option value=""><?php _e( 'Choose Project', 'wp_whise' ); ?></option>

		<?php foreach ( $projects as $project ): ?>
			<?php if ( $project->meta_value == $parent_id ): ?>
				<?php $project_link = get_edit_post_link( $project->ID ); ?>
			<?php endif; ?>
            <option value="<?php echo $project->ID; ?>" <?php echo ( $project->meta_value == $parent_id ) ? 'selected="selected"' : ''; ?>><?php echo $project->post_title; ?></option>
		<?php endforeach; ?>
    </select>

	<?php if ( $project_link !== false ): ?>
        <p><a href="<?php echo $project_link; ?>" target="_blank">Link to the Project</a></p>
	<?php endif; ?>
</div>