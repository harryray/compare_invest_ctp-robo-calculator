<?php
/**
 * Created by PhpStorm.
 * Date: 10/12/2018
 * Time: 16:03
 */
$currency = "£";
get_template_part( 'template-robo-data-header' ); ?>
<h1><?php echo esc_attr( $post_title ); ?></h1>
<div class="robo-data">
    <div id="msg"></div>
    <form id="robo-data" class="" action="" method="post">

        <h2 class="active-from-heading"><?php _e( 'Active from / to', 'cplat' ); ?></h2>
        <table class="platform-data">
            <thead>
            <tr>
                <th><?php _e( 'From', 'cplat' ); ?></th>
                <th><?php _e( 'To', 'cplat' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input placeholder="enter date" type="text" id="active-from" name="active_from"
                           value="<?php echo ctp_robo_validate_date( $all_data['active_from'] ) ? date( 'd F Y', strtotime( $all_data['active_from'] ) ) : ''; ?>">
                </td>
                <td><input placeholder="enter date" type="text" id="active-to" name="active_to"
                           value="<?php echo ctp_robo_validate_date( $all_data['active_to'] ) ? date( 'd F Y', strtotime( $all_data['active_to'] ) ) : ''; ?>">
                </td>
            </tr>
            </tbody>
        </table>
        <div class="clearfix">
            <h2><?php _e( 'Robo Details', 'cplat' ); ?></h2>
            <table class="robo-data supported-products-table">
                <thead>
                <tr>
                    <th><?php _e( 'Investment Objective', 'cplat' ); ?></th>
                    <th><?php _e( 'Advisory', 'cplat' ); ?></th>
                    <th><?php _e( 'Discretionary', 'cplat' ); ?></th>
                    <th><?php _e( 'Telephone Advice', 'cplat' ); ?></th>
                    <th><?php _e( 'Mobile App', 'cplat' ); ?></th>
                    <th><?php _e( 'Risk Profiling', 'cplat' ); ?></th>
                    <th><?php _e( 'ESG', 'cplat' ); ?></th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="checkbox" <?php echo isset( $all_data[ ROBO_API::FIELD_INVESTMENT_OBJECTIVE ] ) && $all_data[ ROBO_API::FIELD_INVESTMENT_OBJECTIVE ] == 1 ? 'checked' : '' ?>
                               name="investment_objective"
                               id="investment_objective"
                               value="1"></td>
                    <td>
                        <input type="checkbox" <?php echo isset( $all_data[ ROBO_API::FIELD_ADVISORY ] ) && $all_data[ ROBO_API::FIELD_ADVISORY ] == 1 ? 'checked' : '' ?>
                               name="advisory"
                               id="advisory" value="1"></td>
                    <td>
                        <input type="checkbox" <?php echo isset( $all_data[ ROBO_API::FIELD_DISCRETIONARY ] ) && $all_data[ ROBO_API::FIELD_DISCRETIONARY ] == 1 ? 'checked' : '' ?>
                               name="discretionary"
                               id="discretionary" value="1"></td>

                    <td>
                        <input type="checkbox" <?php echo isset( $all_data[ ROBO_API::FIELD_TELEPHONE_ADVICE ] ) && $all_data[ ROBO_API::FIELD_TELEPHONE_ADVICE ] == 1 ? 'checked' : '' ?>
                               name="telephone_advice"
                               id="telephone_advice" value="1"></td>
                    <td>
                        <input type="checkbox" <?php echo isset( $all_data[ ROBO_API::FIELD_MOBILE_APP ] ) && $all_data[ ROBO_API::FIELD_MOBILE_APP ] == 1 ? 'checked' : '' ?>
                               name="mobile_app"
                               id="mobile_app" value="1"></td>
                    <td>
                        <input type="checkbox" <?php echo isset( $all_data[ ROBO_API::FIELD_RISK_PROFILING ] ) && $all_data[ ROBO_API::FIELD_RISK_PROFILING ] == 1 ? 'checked' : '' ?>
                               name="risk_profiling"
                               id="risk_profiling" value="1"></td>
                    <td>
                        <input type="checkbox" <?php echo isset( $all_data[ ROBO_API::FIELD_ETHICAL_INVESTMENT ] ) && $all_data[ ROBO_API::FIELD_ETHICAL_INVESTMENT ] == 1 ? 'checked' : '' ?>
                               name="ethical_investment"
                               id="ethical_investment" value="1"></td>
                </tr>
                </tbody>
            </table>

        </div>
        <h2 class="supported-products-heading"><?php _e( 'Supported Products', 'cplat' ); ?></h2>
        <table class="robo-data supported-products-table">
            <thead>
            <tr>
                <th><?php _e( 'GIA', 'cplat' ); ?></th>
                <th><?php _e( 'ISA', 'cplat' ); ?></th>
                <th><?php _e( 'JISA', 'cplat' ); ?></th>
                <th><?php _e( 'Sipp', 'cplat' ); ?></th>
                <th><?php _e( 'JSIPP', 'cplat' ); ?></th>
                <th><?php _e( 'Lifetime ISA', 'cplat' ); ?></th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td><input type="checkbox" <?php echo checked( $all_data['supp_gia'], '1' ) ?> name="supp_gia"
                           value="1"></td>
                <td><input type="checkbox" <?php echo checked( $all_data['supp_isa'], '1' ) ?> name="supp_isa"
                           value="1"></td>
                <td><input type="checkbox" <?php echo checked( $all_data['supp_jisa'], '1' ) ?>
                           name="supp_jisa" value="1"></td>
                <td><input type="checkbox" <?php echo checked( $all_data['supp_sipp'], '1' ) ?>
                           name="supp_sipp" value="1"></td>
                <td><input type="checkbox" <?php echo checked( $all_data['supp_jsipp'], '1' ) ?>
                           name="supp_jsipp" value="1"></td>
                <td><input type="checkbox" <?php echo checked( $all_data['supp_lisa'], '1' ) ?>
                           name="supp_lisa" value="1"></td>
            </tr>
            </tbody>
        </table>

        <h2><?php _e( 'Monthly Saving Plan Min/Max', 'cplat' ); ?></h2>
        <h4>Use this to define Monthly Saving Plan.</h4>
        <table class="robo-data">
            <thead>
            <tr>
                <th><?php _e( 'Monthly Saving Plan', 'cplat' ); ?></th>
                <th><?php _e( 'Minimum Investment', 'cplat' ); ?></th>
                <th><?php _e( 'Min', 'cplat' ); ?></th>
                <th><?php _e( 'Max', 'cplat' ); ?></th>
                <th><?php _e( 'Notes', 'cplat' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="checkbox" <?php echo isset( $all_data['supp_monthly_saving'] ) && $all_data['supp_monthly_saving'] == 1 ? 'checked' : '' ?>
                           name="supp_monthly_saving"
                           value="1"></td>
                <td><input type="checkbox" <?php echo isset( $all_data['monthly_saving_min_inv'] ) ? 'checked' : ''; ?>
                           name="is_monthly_saving_min_inv" id="is_monthly_saving_min_inv"
                           value="1">
                </td>
                <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input
                            placeholder="" type="text" name="monthly_saving_min_inv" id="monthly_saving_min_inv"
                            class="numeric-input"
                            value="<?php echo $all_data['monthly_saving_min_inv']; ?>"></td>
                <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input
                            placeholder="" type="text" name="monthly_saving_max_inv" id="monthly_saving_max_inv"
                            value="<?php echo $all_data['monthly_saving_max_inv']; ?>" class="numeric-input"></td>

                <td><input placeholder="" type="text" name="monthly_saving_notes"
                           value="<?php echo isset( $all_data['monthly_saving_notes'] ) && $all_data['monthly_saving_notes'] !== 'null' ? $all_data['monthly_saving_notes'] : ''; ?>">
                </td>
            </tr>
            </tbody>
        </table>
        <h2><?php _e( 'Lump Sum Min/Max', 'cplat' ); ?></h2>
        <h4>Use this to define Lump Sum Investments.</h4>
        <table class="robo-data">
            <thead>
            <tr>
                <th><?php _e( 'Lump Sum', 'cplat' ); ?></th>
                <th><?php _e( 'Minimum Investment', 'cplat' ); ?></th>
                <th><?php _e( 'Min', 'cplat' ); ?></th>
                <th><?php _e( 'Max', 'cplat' ); ?></th>
                <th><?php _e( 'Notes', 'cplat' ); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="checkbox" <?php echo isset( $all_data['supp_lump_sum'] ) && $all_data['supp_lump_sum'] == 1 ? 'checked' : '' ?>
                           name="supp_lump_sum"
                           value="1"></td>
                <td><input type="checkbox" <?php echo empty( $all_data['lump_sum_min'] ) ? '' : 'checked' ?>
                           name="is_lump_sum_min" id="is_lump_sum_min"
                           value="1"></td>
                <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input
                            placeholder="" type="text" name="lump_sum_min" id="lump_sum_min"
                            value="<?php echo isset( $all_data['lump_sum_min'] ) ? $all_data['lump_sum_min'] : ''; ?>"
                            class="numeric-input"></td>
                <td class="number-value"><span class="symbol"><?php echo $currency ?></span><input
                            placeholder="" type="text" class="numeric-input"
                            value="<?php echo isset( $all_data['lump_sum_max'] ) ? $all_data['lump_sum_max'] : ''; ?>"
                            name="lump_sum_max" id="lump_sum_max"></td>
                <td><input placeholder="" type="text" name="lump_sum_notes"
                           value="<?php echo isset( $all_data['lump_sum_notes'] ) && $all_data['lump_sum_notes'] !== 'null' ? $all_data['lump_sum_notes'] : ''; ?>">
                </td>
            </tr>
            </tbody>
        </table>


        <div class="platform-data-table">
            <h2><?php _e( 'Minimum Investment By Wrapper', 'cplat' ); ?></h2>
            <table class="platform-data">
                <thead>
                <th width="6%%"><?php _e( 'Minimum Investment by wrapper', 'cplat' ); ?></th>
                <th width="8%"><?php _e( 'Minimum Investment', 'cplat' ); ?></th>
                <th width="8%"><?php _e( 'GIA', 'cplat' ); ?></th>
                <th width="8%"><?php _e( 'ISA', 'cplat' ); ?></th>
                <th width="8%"><?php _e( 'JISA', 'cplat' ); ?></th>
                <th width="8%"><?php _e( 'SIPP', 'cplat' ); ?></th>
                <th width="8%"><?php _e( 'JSIPP', 'cplat' ); ?></th>
                <th width="8%"><?php _e( 'LISA', 'cplat' ); ?></th>
                </thead>
                <tbody class="container">
                <tr>
                    <th>
                        <input type="checkbox" <?php echo $all_data['supp_min_inv'] == '1' ? 'checked' : '' ?>
                               name="min_inv[supp_min_inv]"
                               value="1"></th>
                    <td class="number-value"><span
                                class="symbol"><?php echo $currency ?></span><input
                                type="text" name="min_inv[min_inv]"
                                value="<?php echo cplat_text( $all_data['min_inv']['min_inv'], '' ); ?>"
                                class="numeric-input"></td>
                    <td class="number-value"><span
                                class="symbol"><?php echo $currency ?></span><input
                                type="text" name="min_inv[gia]"
                                value="<?php echo cplat_text( $all_data['min_inv']['gia'], '' ); ?>"
                                class="numeric-input"></td>
                    <td class="number-value"><span
                                class="symbol"><?php echo $currency ?></span><input
                                type="text" name="min_inv[isa]"
                                value="<?php echo cplat_text( $all_data['min_inv']['isa'], '' ); ?>"
                                class="numeric-input"></td>
                    <td class="number-value"><span
                                class="symbol"><?php echo $currency ?></span><input
                                type="text" name="min_inv[jisa]"
                                value="<?php echo cplat_text( $all_data['min_inv']['jisa'], '' ); ?>"
                                class="numeric-input"></td>
                    <td class="number-value"><span
                                class="symbol"><?php echo $currency ?></span><input
                                type="text" name="min_inv[sipp]"
                                value="<?php echo cplat_text( $all_data['min_inv']['sipp'], '' ); ?>"
                                class="numeric-input"></td>
                    <td class="number-value"><span
                                class="symbol"><?php echo $currency ?></span><input
                                type="text" name="min_inv[jsipp]"
                                value="<?php echo cplat_text( $all_data['min_inv']['jsipp'], '' ); ?>"
                                class="numeric-input"></td>
                    <td class="number-value"><span
                                class="symbol"><?php echo $currency ?></span><input
                                type="text" name="min_inv[lisa]"
                                value="<?php echo cplat_text( $all_data['min_inv']['lisa'], '' ); ?>"
                                class="numeric-input"></td>
                </tr>

                </tbody>
            </table>

        </div>
		<?php include_once 'admin-charges.php';
		include_once 'custody-charges.php';
        include_once 'annual-product-fee.php';
		?>
        <input type="hidden" value="<?php echo $_GET['version'] ?>" name="version">
        <input type="hidden" value="<?php echo $_GET['robo_id'] ?>" name="robo_id">


        <button type="submit" class="save-robo-charges save-btn" id="save-robo-charges">
            <i class="fa fa-circle-o-notch fa-spin-animate"></i> <i class="fa fa-check"></i>
            <span><?php _e( 'Save', 'cplat' ); ?></span>
        </button>

    </form>
</div>