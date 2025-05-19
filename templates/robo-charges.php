<?php
/**
 * Created by PhpStorm.
 * Date: 21/12/2018
 * Time: 10:01
 */
global $post_id;
if ( null === $post_id ) {
	_e( 'You have to save before you can edit robo data', 'cplat' );

	return false;
}
$version_exist = false;
if ( ! empty( $robo_details ) ) {

	?>
    <div id="delete-confirm" class="hidden" style="max-width:800px">
        <h3>Are you sure you want to remove this charge?</h3>

    </div>
    <div class="cmb2-wrap form-table">
<div id="robo-msg"></div>
        <div class="cmb-td">

            <table class="wp-list-table widefat fixed posts">
                <thead>
                <tr>
                    <th>Version</th>
                    <th>Date Created</th>
                    <th>Active From</th>
                    <th>Active To</th>
                    <th>Approved</th>
                    <th>Edit</th>
                    <th>Save</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody><?php foreach ( $robo_details as $version_id => $version ) {
					$date_parts = explode( '-', $version['active_from'] );
					if ( checkdate( $date_parts[1], $date_parts[2], $date_parts[0] ) ) {
						$version_exist = true;
						$version_link  = "robo-charges/?robo_id={$robo['robo_id']}&version={$version_id}";
						?>
                        <tr class="tr-<?php echo $version_id?>">
                            <td><?php echo $version['version'] ?></td>
                            <td><?php echo date( 'd M Y', strtotime( $version['created_at'] ) ) ?></td>
                            <td><?php echo date( 'd M Y', strtotime( $version['active_from'] ) ) ?></td>
                            <td><?php echo date( 'd M Y', strtotime( $version['active_to'] ) ); ?></td>
                            <td>
                                <select name="version[<?php echo $version_id ?>][status]">
                                    <option value="-1" <?php echo $version['status']=='-1'?' selected':''?>>Pending</option>
                                    <option value="1" <?php echo $version['status']=='1'?' selected':''?>>Approved</option>
                                    <option value="0" <?php echo $version['status']=='0'?' selected':''?>>Rejected</option>
                                </select>
                            </td>
                            <td><a target="_blank"
                                   href="<?php echo $version_link ?>">Edit</a>
                            </td>
                            <td><input type="button" value="Save" class="ctp-robo-status-save button"
                                       data-version="<?php echo $version_id ?>"
                                       data-robo="<?php echo $robo['robo_id'] ?>">
                            </td>
                            <td><input type="button" value="Remove" class="ctp-robo-status-remove button"
                                data-version="<?php echo $version_id ?>"
                                data-robo="<?php echo $robo['robo_id'] ?>">
                            </td>

                        </tr>
					<?php }
				} ?>
                </tbody>
            </table>

        </div>
    </div>
<?php }
if ( ! $version_exist ) { ?>
    <a href="robo-charges/?robo_id=<?php echo $robo['robo_id'] ?>&version=0" class="button btn-default" target="_blank">Add
        new</a>
<?php } ?>
