<?php
/**
 * Created by PhpStorm.
 * Date: 08/02/2019
 * Time: 09:32
 */
?>

<!-- RSPL TASK #61 -->
<?php
 $version = isset( $_GET['version'] ) ? (int) $_GET['version'] : 0;
    if ( $version === 0 ) {
        return false;
    }
    $currency = "£";

    if ( is_user_logged_in() ) {
        $robo_user = wp_get_current_user();
    } else {
        return false;
    }

    $robo = new Robo_Calculator( $robo_user );
    $data = $robo->get_user_meta( $robo_user->ID, $version );

    if ( isset( $data ) ) {
        $robo_user->data = $data;

        $robo->user->data = $robo_user->data;
        $robo_api         = new ROBO_API();
        $robos            = $robo_api->get_queue( (object) $data, $version, $robo_user->ID );

        // RSPL Task#49
        $t_recommended = get_recommended_and_rating_for_robos($robos);

?>
<style>
@media print {
    #print-link {display: none;}
    table {page-break-inside: auto}
    #printThisDocument{display: none;}
    tr {page-break-inside: avoid;page-break-after: auto}
    thead {display: table-header-group}
    #btnPrint{display: none;}
    body.modalprinter * {visibility: hidden;}
    body.modalprinter .modal-dialog.focused {position: absolute;padding: 0;margin: 0;left: 0;top: 0;}
    body.modalprinter .modal-lg.custom-modal-lg{width:100%;margin:0;}
    body.modalprinter .modal-dialog.focused .modal-content {border-width: 0;}
    body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title,
    body.modalprinter .modal-dialog.focused .modal-content .modal-body,
    body.modalprinter .modal-dialog.focused .modal-content .modal-body * {visibility: visible;}
    body.modalprinter .modal-dialog.focused .modal-content .modal-header,
    body.modalprinter .modal-dialog.focused .modal-content .modal-body {padding: 0;}
    body.modalprinter .modal-dialog.focused .modal-content .modal-header .modal-title {margin-bottom: 20px;}
    body.modalprinter .layout-wide-mode #wrapper{display:none;}
    body.modalprinter .invoice-items tbody td, body.modalprinter .invoice-items thead th{padding:20px 10px !important;}
    body.modalprinter .invoice-items tbody td{word-break: break-word;}
    .content-block{padding-bottom:20px !important;}
    .url-block{width: 100px;white-space: pre-wrap;background:red;}
}
/*RSPL Task#77*/
@media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {
    td > img {
        max-width: 300px !important;
    }
}
.url-block{width: 250px;word-break: break-all;}
</style>
<!-- Result Print Pop modal -->
<div class="fusion-modal modal fade modal-4 modal4 in print-result-modal printable autoprint" id="printThisDocument">
    <div class="modal-dialog focused modal-lg custom-modal-lg">
        <div class="modal-content fusion-modal-content" style="background-color:#fff">
            <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    
            <div class="modal-body fusion-clearfix">
                <!--<div class="print-btn-wrapper">-->
                    <!--RSPL Task#61-->
                    <!--<button id="btnPrint" class="results-print-btn" onclick="js:window.print()">Print this page</button>-->
                    <!--<button id="btnPrint" class="results-print-btn">Print this page</button>-->
                <!--</div>-->
                <!--RSPL Task#61-->
                <div class="custom-responsive" id="custom-print-results-id">
                    <table class="body-wrap"
                           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0;">
                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            
                            <td width="100%"
                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top;"
                                valign="top">
                                <div class="content"
                                     style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 100%; display: block; margin: 0 auto;">
                                    <table class="main" width="100%" cellpadding="0" cellspacing="0"
                                           style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; "
                                           bgcolor="#fff">
                                        <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-wrap aligncenter"
                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0;"
                                                align="center" valign="top">
                                                <table width="100%" cellpadding="0" cellspacing="0"
                                                       style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">


                                                    <!-- Logo Header -->

                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                            valign="top">

                                                            <h1 class="aligncenter"
                                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,'Lucida Grande',sans-serif; box-sizing: border-box; font-size: 32px; color: #000; line-height: 1.2em; font-weight: 500; text-align: center; margin: 40px 0 0;"
                                                                align="center">
                                            <img width="130" src="https://compareandinvest.co.uk/wp-content/uploads/2025/01/compareandinvest-logo.png">
                                                            </h1>

                                                        </td>
                                                    </tr>

                                                    <!-- Subheading -->

                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;"
                                                            valign="top">
                                                            <h3 class="aligncenter"
                                                                style="font-family: 'Helvetica Neue',Helvetica,Arial,'Lucida Grande',sans-serif; box-sizing: border-box; font-size: 21px; color: #000; line-height: 1.2em; font-weight: 400; text-align: center; margin: 20px 0 0;"
                                                                align="center">
                                                                Your Search Results - <?php echo date( 'd/m/Y' ); ?>
                                                            </h3>
                                                        </td>
                                                    </tr>


                                                    <!-- Content -->

                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block aligncenter"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                                            align="center" valign="top">

                                                            <table class="invoice"
                                                                   style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; text-align: left; width: 100%; margin: 20px auto;">

                                                                <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                    <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px;"
                                                                        valign="top">



                                                                        <!-- Results -->


                                                                        <table class="invoice-items" cellpadding="0" cellspacing="0"
                                                                               style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0; border-width: 1px; border: 1px solid #eee; border-style: solid; margin-top: 20px;">
                                                                            <thead>
                                                                                <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                                                    <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:17px; color:#747474; text-align:center;"
                                                                                        valign="middle">ROBO ADVISER
                                                                                    </th>
                                                                                    <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:17px; color:#747474; text-align:center;"
                                                                                        valign="middle">ROBO ADVISER FEE
                                                                                    </th>
                                                                                    <!--<th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:17px; color:#747474; text-align:center;"
                                                                                        valign="middle">RECOMMENDED FUNDS LIST AVAILABLE
                                                                                    </th>-->
                                                                                    <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:17px; color:#747474; text-align:center;"
                                                                                        valign="middle">OUR RATING
                                                                                    </th>
                                                                                    <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:17px; color:#747474; text-align:center;"
                                                                                        valign="middle">MORE INFO
                                                                                    </th>
                                                                                    <th style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;vertical-align: middle; font-weight:normal;line-height:17px; color:#747474; text-align:center;"
                                                                                        valign="middle">WEBSITE
                                                                                    </th>
                                                                                </tr>

                                                                            </thead>

                                                                            <tbody>
                                                                                <?php
                                                                                $count = 0;
                                                                                foreach ($robos as $robo) :
                                                                                    $robo_data = $robo['details'];
                                                                                    $robo_cost = isset($robo['cost']['total']) ? $robo['cost']['total'] : '';
                                                                                    $feat_image = isset($robo_data['img']) ? $robo_data['img'] : '';
                                                                                    $info_url = isset($robo_data['info_url']) ? urldecode($robo_data['info_url']) : '';
                                                                                    $website_url = isset($robo_data['website']) ? urldecode($robo_data['website']) : '';
                                                                                    $count ++;
                                                                                    if ($count % 2 == 0) {
                                                                                        $stripe = 'stripe';
                                                                                    } else {
                                                                                        $stripe = '';
                                                                                    }
                                                                                    ?>
                                                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; <?php echo $stripe === 'stripe' ? 'background-color:#f8f8f8' : ''; ?>">
                                                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px;border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;width:20%;"
                                                                                            valign="middle">
                                                                                            <img width="130" style="max-width: 300px; width:100%;height:auto;display:block;"
                                                                                                 src="<?php echo!empty($feat_image) ? esc_url($feat_image) : ''; ?>"/>
                                                                                        </td>
                                                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;"
                                                                                            valign="top">
                                                                                                <?php echo $currency . ' ' . esc_money($robo_cost); ?>
                                                                                        </td>
                                                                                        <!--<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;"
                                                                                            valign="top">
                                                                                        <?php /* if ( $t_recommended['recommended'][$robo_data['robo_id']] ) {
                                                                                          echo $t_recommended['recommended'][$robo_data['robo_id']];
                                                                                          } else {
                                                                                          echo 'No';
                                                                                          } */ ?>
                                                                                        </td>-->

                                                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;"
                                                                                            valign="top">
                                                                                                <?php
                                                                                                $rating = 0;
                                                                                                if ($t_recommended['rating'][$robo_data['robo_id']]) {
                                                                                                    $rating = $t_recommended['rating'][$robo_data['robo_id']];
                                                                                                }

                                                                                                //RSPL Task#77
                                                                                                for ( $orange = 1; $orange <= $rating; $orange ++ ) {
                                                                                                    echo '<span style="font-size: 32px; color: #05ff8f;">&#8226;</span>';
                                                                                                    $remain = $orange;
                                                                                                }
                                                                                                for ( $gray = $remain; $gray < 5; $gray ++ ) {
                                                                                                    echo '<span style="font-size: 32px; color: #e8e8e8; opacity: 0.6;">&#8226;</span>';
                                                                                                }
                                                                                                ?>
                                                                                                <!--<div class="result-rating rating-<?php /*echo $rating; */?>">
                                                                                                    <div class="rating-bullets">
                                                                                                        <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array(intval($rating), range(1, 5)) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                             class="bullet"></div>
                                                                                                        <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array(intval($rating), range(2, 5)) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                             class="bullet"></div>
                                                                                                        <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array(intval($rating), range(3, 5)) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                             class="bullet"></div>
                                                                                                        <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array(intval($rating), range(4, 5)) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                             class="bullet"></div>
                                                                                                        <div style="width: 8px; height: 8px; border-radius:50%; display: inline-block; background:<?php /*echo in_array(intval($rating), array(5)) ? '#F1674E' : '#e8e8e8'; */?>;"
                                                                                                             class="bullet"></div>
                                                                                                    </div>
                                                                                                </div>-->
                                                                                        </td>

                                                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;color:#747474;"
                                                                                            class="url-td">
                                                                                            <a style="color:#034f52; text-decoration: none;"
                                                                                               href="<?php echo urldecode($info_url); ?>"
                                                                                               target="_blank">ROBO ADVISER <br>INFO</a>
                                                                                        </td>

                                                                                        <td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px 10px; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid; text-align:center; vertical-align:middle;color:#747474;"
                                                                                            class="url-td">
                                                                                                <div style="width: 200px;word-break: break-all;margin: 0 auto;"> <?php echo urldecode($website_url); ?></div>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>

                                                                            </tbody>
                                                                        </table>

                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>


                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block aligncenter"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                                            align="center" valign="top">
                                                            <!--                     <a href="http://comparetheplatform.com" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #348eda; text-decoration: underline; margin: 0;">
                                                                                                                                            comparetheplatform.com
                                                                                                                                            </a> -->
                                                        </td>
                                                    </tr>

                                                    <!-- Company address -->
                                                    <tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <td class="content-block aligncenter"
                                                            style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
                                                            align="center" valign="top">

                                                            <!-- Some footer text goes here -->

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </td>
                            
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer"><input type="button" class="results-save" data-dismiss="modal" value="Close"></div>
        </div>
    </div>
</div>
<!--  RSPl task #61-->
    <?php } ?>
