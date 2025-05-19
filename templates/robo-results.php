<?php
/**
 * Created by PhpStorm.
 * Date: 04/03/2019
 * Time: 14:38
 */
$robo_count = $robos ? count( $robos ) : 0;
?>
<?php
// Show steps on mobile

$step_current = isset($_GET['step']) ? intval($_GET['step']) : 1;
$step_count = 0;
if (strpos(esc_url(get_permalink()), 'platform-calculator-consumer') > 0 || strpos(esc_url(get_permalink()), 'platform-calculator') > 0) {
$step_count = 3;
};
if (strpos(esc_url(get_permalink()), 'simplified-calculator') > 0 || strpos(esc_url(get_permalink()), 'digital-investing-app-comparison-tool/invest') > 0 || strpos(esc_url(get_permalink()), 'exit-charge-calculator') > 0) {
$step_count = 2;
};

?>
<h1 class="heading-3">Digital investment app comparison tool</h1>


                <!-- STEPS -->
                <div class="steps--mobile steps--mobile--robo">
                  <div>
                    <?php
                    if ($step_count && $step_count > 0) {
                      $i = 1;
                      if ($_GET['step'] == 'results') {
                        $step = $step_count;
                      }
                      echo "<ul class='calculator-header__steps'>";
                      while ($i < ($step_count + 1)) {
                        $class = '';
                        if (strpos(esc_url(get_permalink()), 'platform-calculator-consumer') > 0 || strpos(esc_url(get_permalink()), 'platform-calculator') > 0) {
                          if ($step > 1) {
                            if ($step == $i + 1) {
                              $class = 'class="active"';
                            }
                          } else {
                            if ($step == $i) {
                              $class = 'class="active"';
                            }
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        } else {
                          if ($step == $i) {
                            $class = 'class="active"';
                          }
                          echo "<li " . $class . ">Step " . $i . "</li>";
                          $i++;
                        }
                      }
                      echo "</ul>";
                    }
                    ?>
                  </div>
                </div>
<p>You can click on the links and buttons in the list below to find out more information about these providers.</p>
<p>This list is not an endorsement or recommendation. It is simply a list of providers that offer guidance and you should make further enquiries to make sure that they are
    suitable for your needs.</p>
<div id="msg"><?php echo $msg; ?></div>
<div class="robo-result-summary clearfix section-padding">
	<?php if ( isset( $robo_user->data->supp_lump_sum ) && $robo_user->data->supp_lump_sum == 1 ) {
		if ( isset( $robo_user->data->lump_sum_min ) && $robo_user->data->lump_sum_min > 0 ) {
			?>
            <div class="row">
            <div class="col-lg-6 ">
                <span class="summary-label"><?php echo 'Lump sum total' ?></span>
            </div>
            <div class="col-lg-6">
                <span class="summary-label"><?php echo cplat_curr_form( $robo_user->data->lump_sum_min ) ?></span>
            </div></div><?php }
		if ( isset( $robo_user->data->min_inv ) && $robo_user->data->min_inv > 0 ) { ?>
            <div class="row">
            <div class="col-lg-6 ">
                <!--RSPL TASK#40-->
<!--                <span class="summary-label">--><?php //echo 'Min investment total' ?><!--</span>-->
                <span class="summary-label"><?php echo 'Annual Top-up total' ?></span>
            </div>
            <div class="col-lg-6">
                <!--RSPL TASK#40-->
                <!--<span class="summary-label">--><?php //echo cplat_curr_form( $robo_user->data->min_inv ) ?><!--</span>-->
                <span class="summary-label"><?php echo cplat_curr_form( $robo_user->data->annual_top_up ) ?></span>
            </div>
            </div><?php }

	}
	if ( isset( $robo_user->data->monthly_saving ) && $robo_user->data->monthly_saving == 1 ) { ?>
        <div class="row">
            <div class="col-lg-6 ">
                <span class="summary-label"><?php echo 'Monthly savings total' ?></span>
            </div>
            <div class="col-lg-6">
                <span class="summary-label">
                    <?php
                    // RSPL TASK#38
                    if ( isset( $robo_user->data->monthly_saving ) &&  $robo_user->data->monthly_saving == 1 ) {
                        echo cplat_curr_form( $robo_user->data->monthly_saving_val / 12 );
                    } else {
                        echo cplat_curr_form( $robo_user->data->monthly_saving_val );
                    }
                    ?>
                </span>
            </div>
        </div>
	<?php } ?>
</div>
<?php

        if(strpos(get_the_permalink(), 'embedded-calculator') < 1) {
            echo do_shortcode('[display_ad category="calculator" placement="simple" step="results" size="leaderboard"]'); 
        }
?>
<!--RSPL Task#48-->
<div class="row results-button-container robo-results-button-container">
    <div class="col-lg-6">
      <div class="row"><span class="form-header">Order By:</span></div>
        <label class="dropdown">
            <input type="hidden" name="order_by" id="order_by" value="cost_low_high">
            <input type="hidden" name="robo_count" id="robo_count" value="<?php echo $robo_count; ?>">
            <select name="order_results_by" id="order_resluts_by"
                    onchange="document.getElementById('order_by').value = this.value;">
                <option value="cost_low_high">Fee - low to high</option>
                <option value="cost_high_low">Fee - high to low</option>
                <option value="rating_high_low">Our rating - high to low</option>
                <option value="rating_low_high">Our rating - low to high</option>
            </select>
        </label>
    </div>
    <div class="col-lg-6 continue-to-right">
        <button class="update-results" type="button" value="Update Results"><?php _e( 'Update Results', 'cplat' ); ?></button>
    </div>
</div>

<div class="results-controls">
    <?php
    $s_login_link = '';
    $s_registration_link = '';
    if ( !is_user_logged_in() ) {
        $redirection_link = get_home_url().'/digital-investing-app-comparison-tool/invest/?step=results&update=true&version='.$version.'&save_version=1';
        $s_login_link = get_home_url().'/login/?redirect_to='.urlencode($redirection_link);
        $s_registration_link = get_home_url().'/registration';
    }
    ?>
    <form method="POST" action="<?php echo $s_login_link; ?>" id="robo-save-search">
      <div class="row">
        <span class="form-header">Name this search</span>
        <input class="name-this-search ctp-nav" type="text" placeholder="Name this search..."
               name="robo_version_name"
               value="<?php echo isset( $robo_user->data->version_name ) ? $robo_user->data->version_name : null ?>"
               id="robo-version-name">
        <input type="hidden" name="action" value="save_robo_results">
        <input type="hidden" name="version" id="robo-version" value="<?php echo $version ?>">
        <div class="col-12 px-0 mt-4">
          <?php
          if ( is_user_logged_in() ) {
              echo '<input class="results-save results-save-pf btn btn-orange" type="button" value="Save" id="robo-result-save">';
              echo '<a href="#" class="results-email results-email-pf btn btn-ghost" id="robo-result-email">Email</a>';
              echo '<a target="" href="#" data-toggle="modal" data-target=".fusion-modal.print-result-modal" class="results-print results-print-pf btn btn-ghost">Print</a>';//rspl task 61
          } else {
              echo '<input class="results-save results-save-pf btn btn-orange" type="submit" value="Save">';
              echo '<a href="'.$s_login_link.'" class="results-email results-email-pf btn btn-ghost">Email</a>';
              echo '<a href="'.$s_login_link.'" class="results-print results-print-pf btn btn-ghost">Print</a>';
              echo '<script type="text/javascript">jQuery("#menu-item-70 a").attr("href","'.$s_login_link.'");</script>';
              echo '<script type="text/javascript">jQuery("#menu-item-69 a").attr("href","'.$s_registration_link.'");</script>';
          }
          ?>
        </div>
      </div>
		<div class="pt-4">
			<div class="col-md-8 col-12">
				<?php
				if ( $user_type != 'advisor' ) {
					$result_page_page_note_acf = ctp_get_questions_option('result_page_page_consumer_note');
				} else {
					$result_page_page_note_acf = ctp_get_questions_option('result_page_page_note');
				}
				if (!empty($result_page_page_note_acf)) {
					echo '<div class="charges-note">' . $result_page_page_note_acf . '</div>';
				}
				?>
			</div>
		</div>
    </form>
</div>

<div class="robo-result-item">
    <div class="results-found"><?php echo $robo_count; ?><?php _e( ' results found', 'cplat' ); ?></div>

    <div class="result-item-header result-item-header--robo clearfix">
        <div class="row">
            <div class="result-main-col"><span>Digital App</span></div>
            <div class="result-main-col"><span>Cost</span></div>
            <!--RSPL Task#48-->
            <!--<div class="result-main-col"><span>RECOMMENDED <br>FUNDS LIST <br>AVAILABLE</span></div>-->
            <!--RSPL Task#43-->
            <div class="result-main-col "><span>OUR RATING</span></div>
            <div class="result-main-col "><span>More info</span></div>
            <div class="result-main-col "><span>Link</span></div>
            <div class="result-main-col robo-comparisons-container">
                <span>
                  <?php 
                    $comparison_url = get_home_url() . '/digital-investing-app-comparison-tool/compare/';

                    if($calc_user->data->embedded_calculator) {
                      $comparison_url = get_home_url() . '/embedded-digital-investing-app-comparison-tool/compare/';
                    }
                  ?>
                  <b data-val="<?php echo $comparison_url; ?>" data-version="<?php echo $_REQUEST['version']; ?>" data-serialized-inputs='<?php echo serialize($calc_user->data); ?>' data-serialized-platforms='<?php echo json_encode($platforms); ?>' class="robo-compare-btn-go disabled" type="button" value="Compare" id="robo-compare-btn-go">Compare</b>
                  <?php /*
                    <b data-val="<?php echo get_home_url().'/robo-comparisons/'; ?>" data-version="<?php echo $_REQUEST['robo_version']; ?>" class="robo-compare-btn-go disabled" type="button" value="Compare" id="robo-compare-btn-go">Compare</b>
                    <?php *//* 
                    <b data-val="<?php echo $comparison_url; ?>" data-version="<?php echo $_REQUEST['version']; ?>" data-serialized-inputs='<?php echo serialize($calc_user->data); ?>' data-serialized-platforms='<?php echo json_encode($platforms); ?>' class="platform-compare-btn-go disabled" type="button" value="Compare" id="platform-compare-btn-go">Compare</b>
                    */ ?>
                    <span class="select-up-to">(Select up to 3)</span>
                </span>
            </div>
        </div>


    </div>

    <div class="platform-loading-update robo-loading-update"><h3>Loading results</h3><div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
	<?php
	$count       = 0;

	foreach ( $robos as $robo ) :
		$robo_data = $robo['details'];
		$robo_id = $robo['details']['robo_id'];


		$count ++;
		$last       = $robo_count === $count ? 'last' : '';
		$first      = $count === 1 ? 'first' : '';
		$feat_image = $robo_data['img'];

		if ( $count % 2 == 0 ) {
			$stripe = 'stripe';
		} else {
			$stripe = '';
		}

        $rating = 0;
        $ratings = get_post_meta($robo_data['robo_id'], 'ratings_details', true);
        if (isset($ratings[0]['_robo_ratings']) && !empty($ratings[0]['_robo_ratings'])) {
            $rating = $ratings[0]['_robo_ratings'];
        }

        $recomended = 'no';
        $robo_details = get_post_meta($robo_data['robo_id'], 'robo_details', true);
        if (isset($robo_details[0]['recommended']) && !empty($robo_details[0]['recommended']) && $robo_details[0]['recommended'] == 1) {
            $recomended = 'yes';
        }
        ?>

        <div class="ind-robo-result-item platform-result-item robo-result-item <?php echo $first . '' . $last . ' ' . $stripe; ?>"
             id="robo-info-<?php echo $robo_id ?>">

            <div class="main-row clearfix">
                <div class="result-main-col robo-platform-img-div">
                    <img src="<?php echo isset( $feat_image ) ? esc_url( $feat_image ) : ''; ?>"
                         class="robo-img" alt="<?php echo $robo_data['name'] ?>"/>
                </div>

                <div class="result-main-col ">

                    <div class="main-result-total"><span
                                class="fee-title-mobile">FEE: </span><?php echo cplat_curr_form( $robo['cost']['total'] ) ?>
                    </div>
                    <div class="robo-results-details"><a href="#" class="robo-results-details"
                                                         data-id="<?php echo $robo_data['robo_id'] ?>">Show
                            calculation</a></div>

                    <span> </span>

                </div>

                <!--RSPL Task#48-->
                <!--<div class="result-main-col ">
                    <?php /*if ( $recomended === 'yes' ) : */?>
                        <span class="check circle-icon"><i class="fusion-li-icon fa"></i></span>
                    <?php /*else : */?>
                        <span class="cross circle-icon"><i class="fusion-li-icon fa"></i></span>
                    <?php /*endif; */?>
                </div>-->

                <!--RSPL Task#43-->
                <div class="result-main-col ">
                        <?php
                        //$rating = get_post_meta($platform_data['platform_id'], '_cplat_rating', true);
                        ?>
                        <div class="result-rating rating-<?php echo $rating; ?>">
                            <div class="rating rating-number"><?php echo $rating; ?>/5</div>
                            <div class="rating-bullets">
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                                <div class="bullet"></div>
                            </div>
                        </div>
                </div>

                <div class="result-main-col  robo-info">
                    <a href="<?php echo /*urldecode(urldecode($robo_data[ ROBO_API::FIELD_INFO_URL ]));*/ get_post_permalink($robo_data['robo_id']); ?>#"
                       class="robo-result-more-details" target="_blank"
                       data-vars-ga-category="Robo Adviser Platform Page" 
				data-vars-ga-action="<?php echo get_post_permalink($robo_data['robo_id']); ?>" 
				data-vars-ga-label="<?php echo get_the_title($robo_data['robo_id']); ?>"><?php _e( 'Robo<br>Info', 'cplat' ); ?></a>
                </div>

                <div class="result-main-col">
                    <a class="robo-result-website-link ga-track" target="_blank"
                       href="<?php echo urldecode($robo_data[ ROBO_API::FIELD_WEBSITE ]); ?>"
                       class="robo-web-button" target="_blank"
                       data-vars-ga-category="Robo Calculator Platforms"
                       data-vars-ga-action="<?php echo urldecode($robo_data[ ROBO_API::FIELD_WEBSITE ]); ?>"
                       data-vars-ga-label="Visit The Website - <?php echo get_the_title($robo_data['robo_id']); ?>"
                       ><?php _e( 'Visit The Website', 'cplat' ); ?></a>
                </div>

                <div class="result-main-col robo-comapare-main-col">
                  <label class="compare-check-toggle">
                    <input type="checkbox" class="robo-comapare-checkbox" name="robo_compare[]" value="<?php echo $robo_data['robo_id']; ?>" disabled="disabled" />
                  </label>
                    <?php
                    $robo_comapare_custody_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]);
                    $robo_comapare_product_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]);
                    ?>
                    <input type="hidden" class="robo-comapare-custody-charge" value='<?php echo $robo_comapare_custody_charge; ?>' />
                    <input type="hidden" class="robo-comapare-product-charge" value='<?php echo $robo_comapare_product_charge; ?>' />
                    <input type="hidden" class="robo-comapare-product-charge-total" value='<?php echo $robo['cost']['total']; ?>' />
                </div>
            </div>
            <div class="results-details">

                <div class="results-table-summary-<?php echo $robo_data['robo_id'] ?> hidden">
                    <h3 class="results-details-heading">Charges - Total:
                        <span><?php echo cplat_curr_form( $robo['cost']['total'] ); ?></span>
                    </h3>
                    <div class="results-table">
                        <h4 class="results-details-subheading">Custody fees </h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col">
                                <span class="resultproduct-label">GIA</span>
                                <span
                                        class="result-product-label"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]['gia'] ); ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">ISA</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]['isa'] ); ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">JISA</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]['jisa'] ); ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">SIPP</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]['sipp'] ); ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">JSIPP</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]['jsipp'] ); ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">LISA</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]['lisa'] ); ?></span>
                            </div>

                            <div class="result-product-col">
                                <span class="result-product-label">TOTAL</span>
                                <span
                                        class="result-product-value"><span><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]['total'] ); ?></span></span>
                            </div>
                        </div>


                        <h4 class="results-details-subheading">Annual Product fees</h4>
                        <div class="results-product-row clearfix">
                            <div class="result-product-col">
                                <span class="resultproduct-label">GIA</span>
                                <span
                                        class="result-product-label"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]['gia'] ) ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">ISA</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]['isa'] ) ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">JISA</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]['jisa'] ) ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">SIPP</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]['sipp'] ) ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="resultproduct-label">JSIPP</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]['jsipp'] ); ?></span>
                            </div>
                            <div class="result-product-col">
                                <span class="result-product-label">LISA</span>
                                <span
                                        class="result-product-value"><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]['lisa'] ) ?></span>
                            </div>


                            <div class="result-product-col">
                                <span class="result-product-label">TOTAL</span>
                                <span
                                        class="result-product-value"><span><?php echo cplt_mesc( $robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]['total'] ) ?></span></span>
                            </div>
                        </div>
                    </div>


                    <div class="clearfix">
                        <a href="#" data-platform_info="platform-info-<?php echo $robo_data['robo_id'] ?>"
                           class="close-result-details" data-id="<?php echo $robo_data['robo_id'] ?>">Hide Calculations</a>
                    </div>
                </div>
            </div>
        </div>
	<?php

	endforeach; ?></div>
<div class="row">

    <?php //var_dump($_SERVER['REQUEST_URI']); ?>
    
    <div class="col-sm-4"><label><a href="<?php echo get_home_url().'/'.Robo_Calculator::ROBO_PAGE_URL . '/?version='.$_REQUEST['version']; ?>" class="back-to-platforms-link back-to-platforms-link--arrow back-to-platforms-link--calculator"><div class="chevron"><svg class="header-menu__submenu-column-link--arrow" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.82197 0L18 8.03042L9.82197 16L8.28208 14.398L13.7341 9.1052L0 9.12547L0 6.95564L13.7341 6.95564L8.21965 1.54119L9.82197 0Z" fill="#404041"></path></svg></div>Back</a></label></div>
    <div class="col-sm-4"></div>
    <div class="col-sm-4"></div>
</div>

<?php

        if(strpos(get_the_permalink(), 'embedded-calculator') < 1) {
            echo do_shortcode('[display_ad category="calculator" placement="simple" step="results" size="leaderboard"]'); 
        }
?>
<style>
  .display-ad {
    margin: auto;
  }
</style>


