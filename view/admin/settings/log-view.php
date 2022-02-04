<?php
namespace wp_navision\view\admin;

use wp_whise\config\Settings_Config;

?>
    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        <h1>Log Whise Integrator</h1>

        <form id="clear-table" method="post">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <input type="hidden" name="truncate" value="yes">
            <button type="submit" class="button button-secondary">Clear Log</button>
        </form>

        <form method="get" id="sortAndFilter" action="">
            <input type="hidden" name="page" value="<?php echo Settings_Config::LOG_PAGE_SLUG; ?>">

            <label for="selectType">Filter log type: </label>
            <select id="selectType" name="selectType">
                <option value="All" <?php isOptionSelected( 'All' ); ?> >All</option>
                <option value="Off" <?php isOptionSelected( 'Off' ); ?>>Off</option>
                <option value="Fatal" <?php isOptionSelected( 'Fatal' ); ?> >Fatal</option>
                <option value="Error" <?php isOptionSelected( 'Error' ); ?> >Error</option>
                <option value="Warning" <?php isOptionSelected( 'Warning' ); ?> >Warning</option>
                <option value="Info" <?php isOptionSelected( 'Info' ); ?> >Info</option>
                <option value="Debug" <?php isOptionSelected( 'Debug' ); ?> >Debug</option>
                <option value="Trace" <?php isOptionSelected( 'Trace' ); ?> >Trace</option>
            </select>

            <button type="submit" name="btnFilter" class="button button-secondary">Filter</button>
            <span style="float: right">
                <?php $log_list_table->search_box( 'search', 'search_id' ); ?>
            </span>
        </form>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="log-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
            <!-- Now we can render the completed list table -->
			<?php $log_list_table->display() ?>
        </form>

    </div>

<?php
function isOptionSelected( $option ) {
	if ( isset( $_REQUEST['selectType'] ) && $_REQUEST['selectType'] == $option ) {
		echo 'selected';
	}
}

?>