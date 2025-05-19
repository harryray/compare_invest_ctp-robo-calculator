<?php
/**
 * Created by PhpStorm.
 * Date: 13/12/2018
 * Time: 13:22
 */
?>
<div id="my-dialog" class="hidden" style="max-width:800px">
    <h3>Are you sure you want to remove this charge?</h3>

</div>
<div class="cmb2-wrap form-table">
    <div class="cmb-row cmb-type-text cmb2-id--cplat-robo adviser-link table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="_cplat_robo_link">Website Link</label>
        </div>
        <div class="cmb-td">
            <input type="text" class="regular-text" name="website" id="website_link"
                   value="<?php echo isset( $robo['website'] ) ? $robo['website'] : '' ?>">
        </div>
    </div>
    <input type="hidden" name="version" value="<?php echo isset( $robo['version'] ) ? $robo['version'] : 1 ?>">
</div>
