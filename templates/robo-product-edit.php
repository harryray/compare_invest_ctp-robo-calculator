<?php
/**
 * Created by PhpStorm.
 * Date: 13/12/2018
 * Time: 16:25
 */
?>
<div class="cmb2-wrap form-table">
    <div class="cmb-row cmb-type-text cplat-available-products-gia-note table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="_cplat_available_products_gia_notes">General Investments Note</label>
        </div>
        <div class="cmb-td">
            <input type="text" class="regular-text" name="gia_notes" id="_cplat_available_products_gia_notes"
                   value="<?php echo isset( $robo['gia_notes'] ) ? $robo['gia_notes'] : '' ?>">
        </div>
    </div>
    <div class="cmb-row cmb-type-text cplat-available-products-isa-note table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="_cplat_available_products_isa_notes">ISAS Note</label>
        </div>
        <div class="cmb-td">
            <input type="text" class="regular-text" name="isa_notes" id="_cplat_available_products_isa_notes"
                   value="<?php echo isset( $robo['isa_notes'] ) ? $robo['isa_notes'] : '' ?>">
        </div>
    </div>
    <div class="cmb-row cmb-type-text cplat-available-products-jisa-note table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="_cplat_available_products_jisa_notes">JISAS Note</label>
        </div>
        <div class="cmb-td">
            <input type="text" class="regular-text" name="jisa_notes"
                   id="_cplat_available_products_jisa_notes"
                   value="<?php echo isset( $robo['jisa_notes'] ) ? $robo['jisa_notes'] : '' ?>">
        </div>
    </div>
    <div class="cmb-row cmb-type-text cplat-available-products-sipp-note table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="_cplat_available_products_sipp_notes">Pensions &amp; SIPPS Note</label>
        </div>
        <div class="cmb-td">
            <input type="text" class="regular-text" name="sipp_notes" id="_cplat_available_products_sipp_notes"
                   value="<?php echo isset( $robo['sipp_notes'] ) ? $robo['sipp_notes'] : '' ?>">
        </div>
    </div>

    <div class="cmb-row cmb-type-text cplat-available-products-offshore-bond-note table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="_cplat_available_products_lisa_notes">LISA Note</label>
        </div>
        <div class="cmb-td">
            <input type="text" class="regular-text" name="lisa_notes" id="_cplat_available_products_lisa_notes"
                   value="<?php echo isset( $robo['lisa_notes'] ) ? $robo['lisa_notes'] : '' ?>">
        </div>
    </div>
    <div class="cmb-row cmb-type-text cplat-available-products-offshore-bond-note table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="_cplat_available_products_lisa_notes">Other Note</label>
        </div>
        <div class="cmb-td">
            <input type="text" class="regular-text" name="lisa_notes" id="_cplat_available_products_lisa_notes"
                   value="<?php echo isset( $robo['other_notes'] ) ? $robo['other_notes'] : '' ?>">
        </div>
    </div>
</div>
<!-- End CMB2 Fields -->


