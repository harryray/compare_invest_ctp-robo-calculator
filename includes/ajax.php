<?php

function ctp_save_robo_charges_callback() {
  $nonce = $_POST['get_ctp_robo_nonce'];
  parse_str( $_POST['data'], $data );
  $ret = [ 'updated' => 0, 'msg' => 'Some error occurred in saving data, please try later' ];
  if ( ! wp_verify_nonce( $nonce, 'get_robo_nonce' ) ) {
    $ret['msg'] = 'Permission denied';

    echo( json_encode( $ret ) );
    die;
  }
  if ( ! isset( $data['version'], $data['robo_id'] ) ) {
    $ret['msg'] = 'Version and robo id not found';
    echo( json_encode( $ret ) );
    die;
  }


  $robo_api = new ROBO_API();
  if ( $data['version'] == 0 ) {
    $version_id = 1;
  } else {
    $version    = $robo_api->get_max_version( $data['robo_id'] );
    $version_id = $version + 1;
  }
  $robo_id               = $data['robo_id'];
  $rules                 = $robo_api->get_charges_rules();
  $clean_data            = $robo_api->sanitize( $data, $rules );

  $clean_data['charges'] = [];


  if ( ! empty( $clean_data['custody_charges'] ) ) {
    $clean_data['charges'] = array_merge( $clean_data['charges'], $clean_data['custody_charges'] );
  }
  if ( ! empty( $clean_data['admin_charges'] ) ) {
    $clean_data['charges'] = array_merge( $clean_data['charges'], $clean_data['admin_charges'] );
  }
  $clean_data['user_id'] = wp_get_current_user()->get( 'ID' );
  if ( $version_id == 1 ) {
    $clean_data['status'] = 1;
  } else {
    $clean_data['status'] = - 1;
  }

  $response = $robo_api->save_robo_charges( $clean_data, $robo_id, $version_id );
  if ( $response ) {
    $ret['updated'] = 1;
    $ret['version'] = $response['version'];
    $ret['msg']     = 'ok';

    // RSPL Task#84
    // Get current user details
        $user = wp_get_current_user();

        // Check whether current user has a robo_vendor role or not
        if ( in_array( 'robo_vendor', (array) $user->roles ) ) {
            // Get the details of updated robo
            $robo_post = get_post($robo_id);

            // Set the display name of user (who has updated the robo data)
            $display_name = '';
            if (isset($user->display_name)) {
                $display_name = $user->display_name;
            }

            // Call a function which will send an email to admin alerting about the change
            cplat_robo_update_notification($robo_post->post_title, $display_name);
        }
  }
  echo( json_encode( $ret ) );
  die;
}


add_action( 'wp_ajax_ctp_save_robo_charges', 'ctp_save_robo_charges_callback' );
add_action( 'wp_ajax_nopriv_ctp_save_robo_charges', 'ctp_save_robo_charges_callback' );

function ctp_save_robo_status_callback() {
  $nonce = $_POST['get_ctp_robo_nonce'];
  $data  = $_POST;
  $ret   = [ 'updated' => 0, 'msg' => 'Some error occurred in saving data, please try later' ];
  if ( ! wp_verify_nonce( $nonce, 'get_robo_nonce' ) ) {
    $ret['msg'] = 'Permission denied';

    echo( json_encode( $ret ) );
    die;
  }
  if ( ! isset( $data['version'], $data['robo_id'] ) ) {
    $ret['msg'] = 'Version and robo id not found';
    echo( json_encode( $ret ) );
    die;
  }
  $version   = $data['version'];
  $robo_id   = $data['robo_id'];
  $status    = $data['status'];
  $is_delete = $data['is_delete'];
  $robo_api  = new ROBO_API();
  if ( $is_delete === 'true' ) {
    $response = $robo_api->delete_robo_charge( $robo_id, $version );
  } else {
    $response = $robo_api->update_robo_status( $robo_id, $version, $status );
  }
  if ( $response['msg'] === 'success' ) {
    $ret['updated'] = 1;
    $ret['version'] = $response['version'];
    $ret['msg']     = 'ok';
  }
  echo( json_encode( $ret ) );
  die;
}

add_action( 'wp_ajax_ctp_save_robo_status', 'ctp_save_robo_status_callback' );
add_action( 'wp_ajax_nopriv_ctp_save_robo_status', 'ctp_save_robo_status_callback' );


add_action( 'wp_ajax_ctp_robo_save_results_name', 'ctp_robo_save_results_name_callback' );
add_action( 'wp_ajax_ctp_robo_save_results_name', 'ctp_robo_save_results_name_callback' );

function ctp_robo_save_results_name_callback() {
  $ret = [];
  if ( ! empty( $_POST['version_name'] ) && ! empty( $_POST['version'] ) ) {
    $robo_user            = wp_get_current_user();
    $ctp_robo             = new Robo_Calculator( $robo_user );
    $data                 = [];
    $data['version_name'] = $_POST['version_name'];
    $version              = $_POST['version'];

    $result               = $ctp_robo->save_user_meta( $robo_user->ID, $data, $version );
    if ( $result ) {
      $ret['msg']     = 'ok';
      $ret['version'] = $result;

    }
    echo json_encode( $ret );
    die;
  }
}


/* RSPL Task#60 Start */
add_action( 'wp_ajax_ctp_robo_email_result', 'ctp_robo_email_result_callback' );
add_action( 'wp_ajax_ctp_robo_email_result', 'ctp_robo_email_result_callback' );

function ctp_robo_email_result_callback() {
    $ret = [];
     if ( ! empty( $_POST['version'] ) ) {
        $version = (int) $_POST['version'];
        if ( $version === 0 ) {
            return false;
        }
        $currency = "£";
        if ( is_user_logged_in() ) {
            $robo_user = wp_get_current_user();
        } else {
            return false;
        }
        $robo_calc = new Robo_Calculator();
        if ( isset( $robo_user->ID, $version ) ) {
            $data = $robo_calc->get_user_meta( $robo_user->ID, $version );

            $robo_user->data = $data;


            $robo_api = new ROBO_API();

            $robos = $robo_api->get_queue( (object) $data, $version, $robo_user->ID );

            // RSPL Task#49
            $t_recommended = get_recommended_and_rating_for_robos($robos);
            ob_start();
            //include CTP_ROBO_PLUGIN_DIR . 'includes/templates/robo-email.php';
            include CTP_ROBO_PLUGIN_DIR . 'templates/robo-email.php';
            $message = ob_get_clean();

            // Email vars
            $blogname    = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
            $admin_email = get_option( 'admin_email' );

            $headers = 'From: Compare and Invest <no-reply@compareandinvest.co.uk>' . "\r\n";
            $user    = get_current_user_id();
            $user    = get_userdata( $user );
            $subject = 'compareandinvest.co.uk results';

            add_filter( 'wp_mail_content_type', 'set_html_content_type' );
            $result = wp_mail( $user->user_email, $subject, $message );
            remove_filter( 'wp_mail_content_type', 'set_html_content_type' );            
            if ( $result ) {
                $ret['msg']     = 'ok';
                $ret['email_msg'] = 'Your results have been sent to '.$user->user_email;
            }else
            { 
                $ret['msg']     = 'Notok';                
                $ret['email_msg'] = 'Sending result to email '.$user->user_email.' failed!';
            }
            echo json_encode( $ret );
            die;

        }
    }
}
/* RSPL Task#60 End */


add_action( 'wp_ajax_ctp_robo_sort_results', 'ctp_robo_sort_results_callback' );
add_action( 'wp_ajax_nopriv_ctp_robo_sort_results', 'ctp_robo_sort_results_callback' );

function ctp_robo_sort_results_callback() {
  $ret = [];
    $s_html = '';
  if ( ! empty( $_REQUEST['version'] ) && ! empty( $_REQUEST['version'] ) ) {
        $version         = $_REQUEST['version'];
        $order_by        = $_REQUEST['order_by'];
        $robo_count      = $_REQUEST['robo_count'];
        //$user            = wp_get_current_user();
        $user = new stdClass();
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
        } else {
            $user->ID = $version;
        }
        $robo            = new Robo_Calculator( $user );
        $data            = $robo->get_user_meta( $user->ID, $version );
        $robo_user       = $robo->user;
        $robo_api        = new ROBO_API();
        $robos           = $robo_api->get_queue( $robo_user->data, $version, $robo_user->ID );
    if ( $robos ) {
      if ( $order_by == 'cost_low_high' || $order_by == 'cost_high_low' ) {
                usort($robos, 'sortByPrice');
                if ( $order_by == 'cost_high_low' ) {
                    $robos = array_reverse($robos);
                }
            }

            $count       = 0;
            $all_robos = array();
            foreach ( $robos as $robo ) {
                $robo_data = $robo['details'];
                $robo_id = $robo['details']['robo_id'];

                $count++;
                $last = $robo_count == $count ? 'last' : '';
                $first = $count === 1 ? 'first' : '';
                $feat_image = $robo_data['img'];

                $rating = 0;
                $ratings = get_post_meta($robo_data['robo_id'], 'ratings_details', true);
                if (isset($ratings[0]['_robo_ratings']) && !empty($ratings[0]['_robo_ratings'])) {
                    $rating = $ratings[0]['_robo_ratings'];
                }
                $robo['cost']['rating'] = (int)$rating;
                $all_robos[] = $robo;

                if ($count % 2 == 0) {
                    $stripe = 'stripe';
                } else {
                    $stripe = '';
                }

                if (isset($feat_image)) {
                    $feat_image = esc_url($feat_image);
                } else {
                    $feat_image = '';
                }

                //$recomended = '<span class="cross circle-icon"><i class="fusion-li-icon fa"></i></span>';
                //$robo_details = get_post_meta($robo_data['robo_id'], 'robo_details', true);
                /*if (isset($robo_details[0]['recommended']) && !empty($robo_details[0]['recommended']) && $robo_details[0]['recommended'] == 1) {
                    $recomended = '<span class="check circle-icon"><i class="fusion-li-icon fa"></i></span>';
                }*/
                $robo_comapare_custody_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]);
                $robo_comapare_product_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]);

                if ( $order_by != 'rating_high_low' || $order_by != 'rating_low_high' ) {
                    $s_html .= '<div class="ind-robo-result-item robo-result-item ' . $first . ' ' . $last . ' ' . $stripe . '" id="robo-info-' . $robo_id . '">
                        <div class="main-row clearfix">
                            <div class="result-main-col">
                                <img src="' . $feat_image . '" class="robo-img" alt="' . $robo_data['name'] . '"/>
                            </div>
                            <div class="result-main-col ">
                                <div class="main-result-total">
                                    <span class="fee-title-mobile">FEE: </span>' . cplat_curr_form($robo['cost']['total']) . '
                                </div>
                                <div class="robo-results-details">
                                    <a href="#" class="robo-results-details" data-id="' . $robo_data['robo_id'] . '">Show<br/>calculation</a>
                                </div>
                                <span> </span>
                            </div>
                            <div class="result-main-col ">
                                <div class="result-rating rating-' . $rating . '">
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
                                <a href="' . $robo_data[ROBO_API::FIELD_INFO_URL] . '#" class="robo-result-more-details">Robo<br>Info</a>
                            </div>
                            <div class="result-main-col">
                                <a class="robo-result-website-link" target="_blank" href="' . $robo_data[ROBO_API::FIELD_WEBSITE] . '" class="robo-web-button">Visit The Website</a>
                            </div>';
                    $s_html .= "<div class='result-main-col robo-comapare-main-col'>
                            <input type='checkbox' class='robo-comapare-checkbox' name='robo_compare[]' value='".$robo_id."' />
                            <input type='hidden' class='robo-comapare-custody-charge' value='".$robo_comapare_custody_charge."' />
                            <input type='hidden' class='robo-comapare-product-charge' value='".$robo_comapare_product_charge."' />
                            <input type='hidden' class='robo-comapare-product-charge-total' value='".$robo['cost']['total']."' />
                        </div>";
                        $s_html .= '</div>
                        <div class="results-details">
                            <div class="results-table-summary-' . $robo_data['robo_id'] . ' hidden">
                                <h3 class="results-details-heading">Charges - Total:
                                    <span>' . cplat_curr_form($robo['cost']['total']) . '</span>
                                </h3>
                                <div class="results-table">
                                    <h4 class="results-details-subheading">Custody fees </h4>
                                    <div class="results-product-row clearfix">
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">GIA</span>
                                            <span class="result-product-label">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['gia']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">ISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['isa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['jisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">SIPP</span>
                                            <span
                                                class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['sipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JSIPP</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['jsipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">LISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['lisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="result-product-label">TOTAL</span>
                                            <span class="result-product-value"><span>' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['total']) . '</span></span>
                                        </div>
                                    </div>
                                    <h4 class="results-details-subheading">Annual Product fees</h4>
                                    <div class="results-product-row clearfix">
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">GIA</span>
                                            <span class="result-product-label">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['gia']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">ISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['isa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['jisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">SIPP</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['sipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JSIPP</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['jsipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="result-product-label">LISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['lisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="result-product-label">TOTAL</span>
                                            <span class="result-product-value"><span>' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['total']) . '</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <a href="#" data-platform_info="platform-info-' . $robo_data['robo_id'] . '" class="close-result-details" data-id="' . $robo_data['robo_id'] . '">Hide Calculations</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }

            if ( $order_by == 'rating_high_low' || $order_by == 'rating_low_high' ) {
                //usort($all_robos, 'sortByRating');
                //RSPL Task#69
                $t_rating = array();
                $t_price = array();
                foreach ( $all_robos as $robo ) {
                    $t_rating[] = $robo['cost']['rating'];
                    $t_price[] = $robo['cost']['total'];
                }
                if ( $order_by == 'rating_low_high' ) {
                    // Sorting Robos by Rating (ASC) and Price (ASC)
                    array_multisort($t_rating, SORT_ASC, SORT_NUMERIC, $t_price, SORT_ASC, $all_robos);
                } else {
                    // Sorting Robos by Rating (DESC) and Price (ASC)
                    array_multisort($t_rating, SORT_DESC, SORT_NUMERIC, $t_price, SORT_ASC, $all_robos);
                }
                $s_html = '';
                $count       = 0;
                foreach ( $all_robos as $robo ) {
                    $robo_data = $robo['details'];
                    $robo_id = $robo['details']['robo_id'];

                    $count++;
                    $last = $robo_count == $count ? 'last' : '';
                    $first = $count === 1 ? 'first' : '';
                    $feat_image = $robo_data['img'];

                    $rating = $robo['cost']['rating'];

                    if ($count % 2 == 0) {
                        $stripe = 'stripe';
                    } else {
                        $stripe = '';
                    }

                    if (isset($feat_image)) {
                        $feat_image = esc_url($feat_image);
                    } else {
                        $feat_image = '';
                    }

                    //$recomended = '<span class="cross circle-icon"><i class="fusion-li-icon fa"></i></span>';
                    //$robo_details = get_post_meta($robo_data['robo_id'], 'robo_details', true);
                    /*if (isset($robo_details[0]['recommended']) && !empty($robo_details[0]['recommended']) && $robo_details[0]['recommended'] == 1) {
                        $recomended = '<span class="check circle-icon"><i class="fusion-li-icon fa"></i></span>';
                    }*/
                    $robo_comapare_custody_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_CUSTODY ]);
                    $robo_comapare_product_charge = json_encode($robo['cost'][ ROBO_API::FEE_TYPE_PRODUCT_CHARGES ]);

                    $s_html .= '<div class="ind-robo-result-item robo-result-item ' . $first . ' ' . $last . ' ' . $stripe . '" id="robo-info-' . $robo_id . '">
                        <div class="main-row clearfix">
                            <div class="result-main-col">
                                <img src="' . $feat_image . '" class="robo-img" alt="' . $robo_data['name'] . '"/>
                            </div>
                            <div class="result-main-col ">
                                <div class="main-result-total">
                                    <span class="fee-title-mobile">FEE: </span>' . cplat_curr_form($robo['cost']['total']) . '
                                </div>
                                <div class="robo-results-details">
                                    <a href="#" class="robo-results-details" data-id="' . $robo_data['robo_id'] . '">Show<br/>calculation</a>
                                </div>
                                <span> </span>
                            </div>
                            <div class="result-main-col ">
                                <div class="result-rating rating-' . $rating . '">
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
                                <a href="' . $robo_data[ROBO_API::FIELD_INFO_URL] . '#" class="robo-result-more-details">Robo<br>Info</a>
                            </div>
                            <div class="result-main-col">
                                <a class="robo-result-website-link" target="_blank" href="' . $robo_data[ROBO_API::FIELD_WEBSITE] . '" class="robo-web-button">Visit The Website</a>
                            </div> ';
                    $s_html .= "<div class='result-main-col robo-comapare-main-col'>
                            <input type='checkbox' class='robo-comapare-checkbox' name='robo_compare[]' value='".$robo_id."' />
                            <input type='hidden' class='robo-comapare-custody-charge' value='".$robo_comapare_custody_charge."' />
                            <input type='hidden' class='robo-comapare-product-charge' value='".$robo_comapare_product_charge."' />
                            <input type='hidden' class='robo-comapare-product-charge-total' value='".$robo['cost']['total']."' />
                        </div>";
                        $s_html .= '</div>
                        <div class="results-details">
                            <div class="results-table-summary-' . $robo_data['robo_id'] . ' hidden">
                                <h3 class="results-details-heading">Charges - Total:
                                    <span>' . cplat_curr_form($robo['cost']['total']) . '</span>
                                </h3>
                                <div class="results-table">
                                    <h4 class="results-details-subheading">Custody fees </h4>
                                    <div class="results-product-row clearfix">
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">GIA</span>
                                            <span class="result-product-label">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['gia']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">ISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['isa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['jisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">SIPP</span>
                                            <span
                                                class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['sipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JSIPP</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['jsipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">LISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['lisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="result-product-label">TOTAL</span>
                                            <span class="result-product-value"><span>' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_CUSTODY]['total']) . '</span></span>
                                        </div>
                                    </div>
                                    <h4 class="results-details-subheading">Annual Product fees</h4>
                                    <div class="results-product-row clearfix">
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">GIA</span>
                                            <span class="result-product-label">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['gia']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">ISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['isa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['jisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">SIPP</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['sipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="resultproduct-label">JSIPP</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['jsipp']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="result-product-label">LISA</span>
                                            <span class="result-product-value">' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['lisa']) . '</span>
                                        </div>
                                        <div class="result-product-col">
                                            <span class="result-product-label">TOTAL</span>
                                            <span class="result-product-value"><span>' . cplt_mesc($robo['cost'][ROBO_API::FEE_TYPE_PRODUCT_CHARGES]['total']) . '</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <a href="#" data-platform_info="platform-info-' . $robo_data['robo_id'] . '" class="close-result-details" data-id="' . $robo_data['robo_id'] . '">Hide Calculations</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
            $ret['msg']     = 'ok';
            $ret['version'] = $s_html;
        }
    echo json_encode( $ret );
    die;
  }
}

function sortByPrice($a, $b) {
    return $a['cost']['total'] - $b['cost']['total'];
}
//RSPL Task#69 (Commenting out this code as it is no more require because we have remove single sorting from rating and used another funcion for sorting which includes sorting by rating and price both at the same time)
/*function sortByRating($a, $b) {
    if( $a['cost']['rating'] === $b['cost']['rating'] ) {
        return 0;
    }
    return ( $a['cost']['rating'] < $b['cost']['rating'] ) ? 1 : -1;
}*/


//RSPL Task#71

add_action( 'wp_ajax_ctp_robo_comparisons_data', 'ctp_robo_comparisons_data_callback' );
add_action( 'wp_ajax_nopriv_ctp_robo_comparisons_data', 'ctp_robo_comparisons_data_callback' );

function ctp_robo_comparisons_data_callback() {
    $ret['msg']     = 'notok';
    if ( isset($_POST['post_data_arr']) && !empty($_POST['post_data_arr']) && isset($_POST['s_selected_robos']) && !empty($_POST['s_selected_robos']) ) {
        session_start();
        $_SESSION['s_selected_robos'] = $_POST['s_selected_robos'];
        $_SESSION['post_data_arr'] = $_POST['post_data_arr'];
    }
    echo json_encode( $ret );
    die;
}