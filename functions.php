<?php
/**
 * Created by PhpStorm.
 * Date: 07/02/2019
 * Time: 10:51
 */
function robo_calculator() {
    ob_start();
    $user = new stdClass();
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();
        if ( isset($_REQUEST['save_version']) && $_REQUEST['save_version'] == 1 ) {
            //Store robo_financial_data for user after login
            $version = $_REQUEST['version'];
            $prev_data = get_user_meta( $version, 'robo_financial_data', true );
            //$_POST = (array)$prev_data[$version];
            $saved_data = get_user_meta( $user->ID, 'robo_financial_data', true );
            if ( empty( $saved_data ) ) {
                $saved_data = array();
            }
            $saved_data[ $version ] = $prev_data[$version];
            update_user_meta( $user->ID, 'robo_financial_data', $saved_data );

            //Store robo_financial_data for user after login
            $prev_user_meta = get_user_meta($version, 'robo_results');
            $user_meta = get_user_meta($user->ID, 'robo_results');
            $prev_user_meta = is_array( $prev_user_meta ) ? $prev_user_meta : [];
            $user_meta[ $version ] = $prev_user_meta;
            update_user_meta( $user->ID, 'robo_results', $user_meta );
        }
    }
    $step = 1;
    if ( isset( $_GET['step'] ) ) {
        $step = $_GET['step'];
    }
    include_once 'templates/header.php';
    $version = isset( $_GET['version'] ) ? $_GET['version'] : false;
    if ( ! $version ) {
        $version = time();
    }
    $robo = new Robo_Calculator( $user );

    $_SESSION['new_user_result_versions'] = 0;
    if ( !is_user_logged_in() ) {
        $_SESSION['new_user_result_version'] = $version;
        $_SESSION['new_user_result_versions'] = $version;
        $user->ID = $version;
    } else {
        //update_user_meta( $user->ID, 'new_user_result_session', '0' );
    }
    //update_user_meta($user->ID, 'new_user_result_session', $version);

    $data      = $robo->get_user_meta( $user->ID, $version );

    $robo_user = $robo->user;
    //RSPL Task#43
    $hide_fields_cls = '';
    $hide_single_field_cls = '';
    $hide_individual_field_cls = '';
    $hide_continue_button = '';
    if ( !isset($robo_user->data->inv_manual) ) {
        $hide_fields_cls = 'robo-calc-hidden-cls';
        $hide_single_field_cls = 'robo-single-field-hidden-cls';
        $hide_individual_field_cls = 'robo-individual-field-hidden-cls robo-individual-field-hidden-cls';
        $hide_continue_button = 'final-row-continue';
    }

    if ( ! empty( $_POST ) ) {
        $robo    = new Robo_Calculator($user);
        //RSPL TASK#40
        $_POST['min_inv'] = robo_sanitize_number($_POST['min_inv']);
        $_POST['min_inv_total'] = robo_sanitize_number($_POST['min_inv_total']);
        $_POST['lump_sum_min'] = robo_sanitize_number($_POST['lump_sum_min']);
        $_POST['lump_sum_min_total'] = robo_sanitize_number($_POST['lump_sum_min_total']);
        $_POST['lump_sum_val_total'] = robo_sanitize_number($_POST['lump_sum_val_total']);
        $_POST['annual_top_up'] = robo_sanitize_number($_POST['min_inv_total']);
        $_POST['monthly_saving_val'] = robo_sanitize_number($_POST['monthly_saving_val']);
        $_POST['monthly_saving_val_total'] = robo_sanitize_number($_POST['monthly_saving_val_total']);
        if ( isset($_POST['supp_lump_sum']) && $_POST['supp_lump_sum'] == 1 ) {
            // isa
            if (isset($_POST['min_inv_isa_freq']) && $_POST['min_inv_isa_freq'] >= 1) {
                $_POST['annual_top_up'] += $_POST['min_inv_isa'] * $_POST['min_inv_isa_freq'];
            } else {
                $_POST['annual_top_up'] += $_POST['min_inv_isa'];
            }
            // jisa
            if (isset($_POST['min_inv_jisa_freq']) && $_POST['min_inv_jisa_freq'] >= 1) {
                $_POST['annual_top_up'] += $_POST['min_inv_jisa'] * $_POST['min_inv_jisa_freq'];
            } else {
                $_POST['annual_top_up'] += $_POST['min_inv_jisa'];
            }
            //sipp
            if (isset($_POST['min_inv_sipp_freq']) && $_POST['min_inv_sipp_freq'] >= 1) {
                $_POST['annual_top_up'] += $_POST['min_inv_sipp'] * $_POST['min_inv_sipp_freq'];
            } else {
                $_POST['annual_top_up'] += $_POST['min_inv_sipp'];
            }
            //jsipp
            if (isset($_POST['min_inv_jsipp_freq']) && $_POST['min_inv_jsipp_freq'] >= 1) {
                $_POST['annual_top_up'] += $_POST['min_inv_jsipp'] * $_POST['min_inv_jsipp_freq'];
            } else {
                $_POST['annual_top_up'] += $_POST['min_inv_jsipp'];
            }
            //lisa
            if (isset($_POST['min_inv_lisa_freq']) && $_POST['min_inv_lisa_freq'] >= 1) {
                $_POST['annual_top_up'] += $_POST['min_inv_lisa'] * $_POST['min_inv_lisa_freq'];
            } else {
                $_POST['annual_top_up'] += $_POST['min_inv_lisa'];
            }
            //gia
            if (isset($_POST['min_inv_gia_freq']) && $_POST['min_inv_gia_freq'] >= 1) {
                $_POST['annual_top_up'] += $_POST['min_inv_gia'] * $_POST['min_inv_gia_freq'];
            } else {
                $_POST['annual_top_up'] += $_POST['min_inv_gia'];
            }
        }
        //	RSPL TASK#16
        if ( isset($_POST['min_inv_freq']) && $_POST['min_inv_freq'] >= 1 ) {
            $_POST['min_inv_total'] = $_POST['min_inv_total']*$_POST['min_inv_freq'];
        }
        if ( isset($_POST['robo_investment_products']) && $_POST['robo_investment_products'] != 1 ) {
            $_POST['min_inv'] = robo_sanitize_number($_POST['min_inv_total']);
        }
        $_POST['min_inv_val_total'] = $_POST['min_inv_total'];
        $_POST['annual_top_up'] = $_POST['min_inv_total'];
        $_POST['lump_sum_val_total'] = $_POST['lump_sum_min_total'];
        $_POST['lump_sum_min'] = $_POST['lump_sum_min_total'];
        // RSPL TASK#38
        if ( isset($_POST['monthly_saving']) && $_POST['monthly_saving'] == 1 ) {
            $_POST['monthly_saving_val_total'] = $_POST['monthly_saving_val_total'] * 12;
        }
        if ( !isset($_POST['monthly_saving_val']) || empty($_POST['monthly_saving_val']) ) {
            $_POST['monthly_saving_val'] = $_POST['monthly_saving_val_total'];
        } else {
            if ( isset($_POST['monthly_saving']) && $_POST['monthly_saving'] == 1 ) {
                $_POST['monthly_saving_val'] = $_POST['monthly_saving_val']*12;
            }
        }

        $version = $robo->save_user_meta( $user->ID, $_POST, $version );

        $data = $robo->get_user_meta( $user->ID, $version );
    } else {
        $_POST = $data;
    }
    $data['version'] = $version;
    $data['user_id'] = $user->ID;
    $msg             = "";
    switch ( $step ) {

        default:

            $path_step_res = Robo_Calculator::ROBO_PAGE_URL . '/?step=results&update=true&version=' . $version;
            $back_url      = Robo_Calculator::ROBO_PAGE_URL . '/?step=2&version=' . $version;
            if ( (isset($robo_user->data->monthly_saving_isa) && !empty($robo_user->data->monthly_saving_isa)) ||
                (isset($robo_user->data->monthly_saving_jisa) && !empty($robo_user->data->monthly_saving_jisa)) ||
                (isset($robo_user->data->monthly_saving_sipp) && !empty($robo_user->data->monthly_saving_sipp)) ||
                (isset($robo_user->data->monthly_saving_jsipp) && !empty($robo_user->data->monthly_saving_jsipp)) ||
                (isset($robo_user->data->monthly_saving_lisa) && !empty($robo_user->data->monthly_saving_lisa)) ||
                (isset($robo_user->data->monthly_saving_gia) && !empty($robo_user->data->monthly_saving_gia)) ) {
                $robo_user->data->monthly_saving_val = $robo_user->data->monthly_saving_isa + $robo_user->data->monthly_saving_jisa + $robo_user->data->monthly_saving_sipp + $robo_user->data->monthly_saving_jsipp + $robo_user->data->monthly_saving_lisa + $robo_user->data->monthly_saving_gia;
            } else {
                $robo_user->data->monthly_saving_val_total = $robo_user->data->monthly_saving_val/12;
                $robo_user->data->monthly_saving_val = 0;
            }
            if ( !isset($robo_user->data->min_inv_isa_freq) || empty($robo_user->data->min_inv_isa_freq) || $robo_user->data->min_inv_isa_freq == 0 ) {
                $robo_user->data->min_inv_isa_freq = 1;
            }
            if ( !isset($robo_user->data->min_inv_jisa_freq) || empty($robo_user->data->min_inv_jisa_freq) || $robo_user->data->min_inv_jisa_freq == 0 ) {
                $robo_user->data->min_inv_jisa_freq = 1;
            }
            if ( !isset($robo_user->data->min_inv_sipp_freq) || empty($robo_user->data->min_inv_sipp_freq) || $robo_user->data->min_inv_sipp_freq == 0 ) {
                $robo_user->data->min_inv_sipp_freq = 1;
            }
            if ( !isset($robo_user->data->min_inv_jsipp_freq) || empty($robo_user->data->min_inv_jsipp_freq) || $robo_user->data->min_inv_jsipp_freq == 0 ) {
                $robo_user->data->min_inv_jsipp_freq = 1;
            }
            if ( !isset($robo_user->data->min_inv_lisa_freq) || empty($robo_user->data->min_inv_lisa_freq) || $robo_user->data->min_inv_lisa_freq == 0 ) {
                $robo_user->data->min_inv_lisa_freq = 1;
            }
            if ( !isset($robo_user->data->min_inv_gia_freq) || empty($robo_user->data->min_inv_gia_freq) || $robo_user->data->min_inv_gia_freq == 0 ) {
                $robo_user->data->min_inv_gia_freq = 1;
            }
            if ( !isset($robo_user->data->min_inv_freq) || empty($robo_user->data->min_inv_freq) || $robo_user->data->min_inv_freq == 0 ) {
                $robo_user->data->min_inv_freq = 1;
            }
            include_once 'templates/robo-step.php';
            break;

        case 'results':
            $back_url = Robo_Calculator::ROBO_PAGE_URL . '/?version=' . $version;
            if ( empty( $robo_user->data ) ) {
                wp_redirect( $back_url );
            }

            $robo_api = new ROBO_API();
            if ( ! $robo->validate( $_POST ) ) {
                //$back_url .= "&error=true";
                $back_url = get_home_url() . '/' . Robo_Calculator::ROBO_PAGE_URL . '/?version=' . $version . '&error=true';
                wp_redirect( $back_url );
            }

            if ( ! isset( $_GET['update'] ) ) {
                $robos = $robo->get_user_meta( $robo_user->ID, $version, Robo_Calculator::USER_RESULTS_META_KEY );
            }
            if ( empty( $robos ) ) {
                if ( !is_user_logged_in() ) {
                    if ( $robo_user->data == new stdClass() ) {
                        $robo_user->data = (object)$_POST;
                    }
                }

                $robos = $robo_api->get_queue( $robo_user->data, $version, $user->ID );
                //wp_redirect( Robo_Calculator::ROBO_PAGE_URL . '/?step=results&version=' . $version );
            }
            if ( ! $robos ) {
                $msg = ctp_robo_get_questions_option( 'result_error_text' );
                // Replaced the constant [robo-version-val] with ?version=xxxxxxxxx so that when someone clicks on it they get their previously submitted data
                $msg = str_replace("[robo-version-val]","?version=".$_GET['version'],$msg);
            }
            include_once 'templates/robo-results.php';
            //RSPL TASK #61
            include_once 'templates/robo-result-popup.php'; //#61
            break;            


    }
    include_once 'templates/footer.php';
    return ob_get_clean();
}

add_shortcode( 'robo_calculator', 'robo_calculator' );

function robo_charges() {
    $user = wp_get_current_user();
    if ( user_can( $user, 'edit_robo_advisers' ) ) {
        $robo_id = null;

        $version = 1;
        if ( isset( $_GET['robo_id'] ) ) {
            $robo_id = $_GET['robo_id'];
        } else {
            wp_redirect( '
' );
        }
        if ( isset( $_GET['version'] ) ) {
            $version = $_GET['version'];
            if ( $version == 0 ) {
                $version = 1;
            }
        }
        $charge_types = array(
            Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
            Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
            Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
            Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
        );
        $robo_api     = new ROBO_API();
        $robo_data    = $robo_api->get_robo_data( $robo_id, $version );
        $post_title   = get_post( $robo_id )->post_title;

        $all_data = $robo_data['version'][ $version ];

        include_once 'templates/robo-data.php';
    } else {
        wp_redirect( 'digital-investing-app-comparison-tool/invest' );
    }
}

add_shortcode( 'robo_charges', 'robo_charges' );
add_action( 'cmb2_admin_init', 'robo_register_user_profile_metabox' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function robo_register_user_profile_metabox() {

    // Start with an underscore to hide fields from custom fields list
    $prefix = '_robo_user_';

    /**
     * Metabox for the user profile screen
     */
    $cmb_user = new_cmb2_box( array(
        'id'               => $prefix . 'edit',
        'title'            => __( 'Access to robo data', 'cmb2' ),
        'object_types'     => array( 'user' ),
        'show_names'       => true,
        'new_user_section' => 'add-new-user',
        // where form will show on new user page. 'add-existing-user' is only other valid option.
    ) );

    $cmb_user->add_field( array(
        'name'     => __( 'Access to robo', 'cmb2' ),
        'desc'     => __( '', 'cmb2' ),
        'id'       => $prefix . 'robo',
        'type'     => 'sfn_chosen_multi',
        'on_front' => false,
        'default'  => '',
        'options'  => robo_get_select_list()
    ) );
}

function robo_get_select_list() {

    $args = array(
        'posts_per_page' => - 1,
        'offset'         => 0,
        'orderby'        => 'title',
        'order'          => 'DESC',
        'post_type'      => Robo_Calculator::POST_TYPE
    );

    $posts = get_posts( $args );

    $select = array();

    foreach ( $posts as $post ) {
        $select[ $post->ID ] = $post->post_title;
    }

    return $select;
}

/**
 * @param int $user_id
 * @param [] $new_role
 */
function robo_editor_meta( $user_id, $new_role ) {
    if ( $new_role === Robo_Calculator::ROLE && isset( $_POST['_cplat_user_robo'] ) ) {
        $robos = $_POST['_cplat_user_robo'];
        update_user_meta( $user_id, Robo_Calculator::POST_META_KEY, $robos );
    }
}

add_action( 'add_user_role', 'robo_editor_meta', 10, 3 );
add_action( 'remove_user_role', 'robo_editor_remove_meta', 10, 3 );
function robo_editor_remove_meta( $user_id, $role ) {
    if ( $role === 'platinum_vendor' ) {
        delete_user_meta( $user_id, Sandbox_Vendor_Data::USER_META_KEY );
    }
}

function ctp_robo_enqueue_scripts() {
    wp_enqueue_script( 'ctp-robo-script', plugin_dir_url( __FILE__ ) . 'js/ctp-robo.js', array( 'jquery' ), time(), true );

    wp_enqueue_style( 'robo-style', plugin_dir_url( __FILE__ ) . 'css/ctp-robo.css', [], time() );

    /**
     * Global js variables
     */
    wp_localize_script( 'ctp-robo-script', 'get_ctp_robo_vars', array(
        'ajaxurl'    => admin_url( 'admin-ajax.php' ),
        'robo_nonce' => wp_create_nonce( 'get_robo_nonce' ),
        'form_error' => ctp_robo_get_questions_option( 'form_error_text' )
    ) );
}

add_action( 'wp_enqueue_scripts', 'ctp_robo_enqueue_scripts' );
function sanitize_fee( $data ) {
    $new_data = [];
    if ( ! is_array( $data ) ) {
        $data = (array) $data;

    }
    foreach ( $data as $key => $val ) {
        if ( is_array( $val ) ) {
            foreach ( $val as $k => $v ) {
                //dont want to change name to num
                if ( $k === 'fee_name' || $v == "" ) {
                    $new_data[ $key ][ $k ] = $v;

                } else {
                    $new_data[ $key ][ $k ] = robo_sanitize_number( $v );
                }

            }

        } else {
            $new_data[ $key ] = robo_sanitize_number( $val );
        }
    }

    return $new_data;
}

function robo_sanitize_number( $arg ) {
    $num = preg_replace( '/[^0-9\.]/', '', $arg );

    if ( ! is_numeric( $num ) ) {
        $num = null;
    }

    return $num;
}

function ctp_robo_enqueue_admin_scripts() {
    wp_enqueue_script( 'jquery-ui-dialog' ); // jquery and jquery-ui should be dependencies, didn't check though...
    wp_enqueue_style( 'wp-jquery-ui-dialog' );
    wp_register_script( 'ctp-robo-admin-script', CTP_ROBO_PLUGIN_DIR_URL . 'js/ctp-robo-admin.js', [], time() );
    wp_register_script( 'repeater', CTP_ROBO_PLUGIN_DIR_URL . '/js/repeater.js', [], time() );
    wp_register_style( 'ctp-robo-admin-style', CTP_ROBO_PLUGIN_DIR_URL . 'css/ctp-robo-admin.css', [], time() );
    wp_localize_script( 'ctp-robo-admin-script', 'get_ctp_robo_vars', array(
        'ajaxurl'    => admin_url( 'admin-ajax.php' ),
        'robo_nonce' => wp_create_nonce( 'get_robo_nonce' )
    ) );
    wp_enqueue_script( 'ctp-robo-admin-script' );
    wp_enqueue_style( 'ctp-robo-admin-style' );
    wp_enqueue_script( 'repeater' );

}

add_action( 'admin_enqueue_scripts', 'ctp_robo_enqueue_admin_scripts' );

/**
 * @param string $date Y-m-d format date
 *
 * @return bool
 */
function ctp_robo_validate_date( $date ) {
    $date_parts = explode( '-', $date );

    return isset( $date_parts[1], $date_parts[2], $date_parts[0] ) && checkdate( $date_parts[1], $date_parts[2], $date_parts[0] );
}

//redirect if they are not logged in
function ctp_robo_redirect() {
    session_start();
    if ( is_page( 'digital-investing-app-comparison-tool/invest' ) && ! is_user_logged_in() ) {
        //wp_redirect( home_url( '/login/' ) );
        //die;
    }
    if ( is_page( 'platform-calculator' ) ) {
        if ( is_user_logged_in() ) {
            $user_id = get_current_user_id();
            //Store robo_financial_data for user after login
	        if ( ! isset( $_SESSION['new_user_result_versions'] ) ) {
		        return;
	        }
            $version = $_SESSION['new_user_result_versions'];
            $prev_data = get_user_meta( $version, 'robo_financial_data', true );
            //$_POST = (array)$prev_data[$version];
            $saved_data = get_user_meta( $user_id, 'robo_financial_data', true );
            if ( empty( $saved_data ) ) {
                $saved_data = array();
            }
            $saved_data[ $version ] = $prev_data[$version];
            update_user_meta( $user_id, 'robo_financial_data', $saved_data );

            //Store robo_financial_data for user after login
            $prev_user_meta = get_user_meta($version, 'robo_results');
            $user_meta = get_user_meta($user_id, 'robo_results');
            $prev_user_meta = is_array( $prev_user_meta ) ? $prev_user_meta : [];
            $user_meta[ $version ] = $prev_user_meta;
            update_user_meta( $user_id, 'robo_results', $user_meta );
            $i_user_id = get_current_user_id();
            if (isset($_SESSION['new_user_result_versions']) && !empty($_SESSION['new_user_result_versions']) && $_SESSION['new_user_result_versions'] >= 1) {
                $redirect_to = get_home_url() . '/digital-investing-app-comparison-tool/invest/?step=results&update=true&version=' . $_SESSION['new_user_result_versions'];
                wp_redirect($redirect_to);
            }
            $i_redirect = get_user_meta($i_user_id, 'new_user_result_session', true);
            update_user_meta($i_user_id, 'new_user_result_session', '0');
            if (isset($i_redirect) && !empty($i_redirect) && $i_redirect >= 1) {
                $redirect_to = get_home_url() . '/digital-investing-app-comparison-tool/invest/?step=results&update=true&version=' . $i_redirect;
                wp_redirect($redirect_to);
            }
        }
    }
    if ( is_page( 'registration' ) ) {
        if ( is_user_logged_in() ) {
            $redirect_to = get_home_url() . '/digital-investing-app-comparison-tool/invest';
            wp_redirect($redirect_to);
        }
    }
}

function ctp_robo_email_result() {
    if ( ! empty( $_GET['roboemailresult'] ) ) {
        $version = (int) $_GET['roboemailresult'];
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
            $my_acc_url = get_permalink( get_page_by_path( 'client-area' ) );
            if ( $result ) {
                $my_acc_url = add_query_arg( 'message', 'email_success', $my_acc_url );
            } else {
                $my_acc_url = add_query_arg( 'email', 'email_failed', $my_acc_url );
            }
            wp_redirect( $my_acc_url );
            exit;


        }
    }
}

function ctp_robo_print_result() {
    $version = isset( $_GET['roboprintresult'] ) ? (int) $_GET['roboprintresult'] : 0;
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
        include CTP_ROBO_PLUGIN_DIR . 'templates/robo-print.php';

        exit;
    }
}

function ctp_robo_get_freq_options( $selected_option = "" ) {
    $freq_options = "<option value=''>Please Select</option>";
    foreach ( Robo_Calculator::LUMP_SUM_FREQ as $freq => $label ) {
        $selected = "";
        if ( $freq == $selected_option ) {
            $selected = "selected";
        }
        $freq_options .= "<option value='$freq' $selected>$label</option>";
    }

    return $freq_options;
}

function ctp_robo_get_inv_child_val_options( $selected_option = "" ) {
    $freq_options = "<option value=''>Please Select</option>";
    foreach ( Robo_Calculator::INV_CHILD_OPTIONS as $val => $label ) {
        $selected = "";
        if ( $val == $selected_option ) {
            $selected = "selected";
        }
        $freq_options .= "<option value='$val' $selected>$label</option>";
    }

    return $freq_options;
}

function ctp_robo_get_pension_options( $selected_option = "" ) {
    $freq_options = "<option value=''>Please Select</option>";
    foreach ( Robo_Calculator::PENSION_OPTIONS as $val => $label ) {
        $selected = "";
        if ( $val == $selected_option ) {
            $selected = "selected";
        }
        $freq_options .= "<option value='$val' $selected>$label</option>";
    }

    return $freq_options;
}


function robo_html_sanitize( $content ) {
    return stripslashes_deep( $content );
}

add_action( 'init', 'ctp_robo_print_result' );
add_action( 'init', 'ctp_robo_email_result' );

add_action( 'template_redirect', 'ctp_robo_redirect' );

// RSPL Task#49
function get_recommended_and_rating_for_robos($robo_array) {
    //var_dump($t_robo_details, $t_robo_ids);
    if(!$robo_array) {
        $robo_array = [];
    }
    if(!$t_robo_details) {
        $t_robo_details = [];
    }
    $t_recommended = array();
    $t_robo_details = array_column($robo_array, 'details');
    $t_robo_ids = array_column($t_robo_details, 'robo_id');
    if ( isset($t_robo_ids) && !empty($t_robo_ids) ) {
        foreach ($t_robo_ids as $t_robo_id ) {
            $robo_recommended_details = get_post_meta($t_robo_id, 'robo_details', true);
            $t_recommended['recommended'][$t_robo_id] = 'No';
            if (isset($robo_recommended_details[0]['recommended']) && !empty($robo_recommended_details[0]['recommended']) && $robo_recommended_details[0]['recommended'] == 1) {
                $t_recommended['recommended'][$t_robo_id] = 'Yes';
            }
            $t_recommended['rating'][$t_robo_id] = 0;
            $ratings = get_post_meta($t_robo_id, 'ratings_details', true);
            if (isset($ratings[0]['_robo_ratings']) && !empty($ratings[0]['_robo_ratings'])) {
                $t_recommended['rating'][$t_robo_id] = $ratings[0]['_robo_ratings'];
            }
        }
    }
    return $t_recommended;
}



add_action( 'user_register', 'myplugin_registration_save', 10, 1 );
function myplugin_registration_save( $user_id ) {
    session_start();
    if ( isset($_SESSION['new_user_result_version']) && !empty($_SESSION['new_user_result_version']) ) {
        update_user_meta($user_id, 'new_user_result_session', $_SESSION['new_user_result_version']);
        //Store robo_financial_data for user after login
        $version = $_SESSION['new_user_result_version'];
        $prev_data = get_user_meta( $version, 'robo_financial_data', true );
        //$_POST = (array)$prev_data[$version];
        $saved_data = get_user_meta( $user_id, 'robo_financial_data', true );
        if ( empty( $saved_data ) ) {
            $saved_data = array();
        }
        $saved_data[ $version ] = $prev_data[$version];
        update_user_meta( $user_id, 'robo_financial_data', $saved_data );

        //Store robo_financial_data for user after login
        $prev_user_meta = get_user_meta($version, 'robo_results');
        $user_meta = get_user_meta($user_id, 'robo_results');
        $prev_user_meta = is_array( $prev_user_meta ) ? $prev_user_meta : [];
        $user_meta[ $version ] = $prev_user_meta;
        update_user_meta( $user_id, 'robo_results', $user_meta );
    }
}

function my_login_redirect( $redirect_to, $request, $user ) {
    $i_user_id = $user->ID;
    $i_redirect = get_user_meta( $i_user_id, 'new_user_result_session', true );
    //update_user_meta( $i_user_id, 'new_user_result_session', '0' );
    if ( isset($i_redirect) && !empty($i_redirect) && $i_redirect >= 1 ) {
        $redirect_to = get_home_url().'/digital-investing-app-comparison-tool/invest/?step=results&update=true&version='.$i_redirect;
    }
    return $redirect_to;
}

// add_filter( 'login_redirect', 'my_login_redirect', 1, 3 );

//RSPL Task#55
function robo_user_registration_form_callback() {
    ?>
    <div class="cplat-submit-form-container cplat-robo-registration-form-container">
        <div class="register-container">
            <h2><?php _e( 'First Time? Tell Us A Little More', 'cplat' ) ?></h2>
            <?php
                if ( class_exists( 'Ctp_Theme_My_Login' ) ) {
                    echo do_shortcode( '[ctp-theme-my-login default_action="register"]');
                } else {
                    echo "required plugin Theme My Login is not activated or installed";
                }
                ?>
        </div>
    </div>
    <?php
}
add_shortcode('robo_user_registration_form', 'robo_user_registration_form_callback');

//RSPL Task#52
function use_custom_template($tpl){
    if ( is_post_type_archive ( 'robo-adviser' ) ) {
        $tpl = plugin_dir_path( __FILE__ ) . '/templates/archive-robo-adviser.php';
    }
    return $tpl;
}

add_filter( 'archive_template', 'use_custom_template' ) ;

//RSPL Task#52
function the_title_trim($title) {
    $title = esc_attr($title);
    $findthese = array(
        '#Protected:#',
        '#Private:#'
    );
    $replacewith = array(
        '', // What to replace "Protected:" with
        '' // What to replace "Private:" with
    );
    $title = preg_replace($findthese, $replacewith, $title);
    return $title;
}
add_filter('the_title', 'the_title_trim');


function cplat_robo_vendor_account() {

    if ( ! is_user_logged_in() || ! cplat_check_user_role( 'robo_vendor' ) ) {
        $redirect_to_login = esc_url( get_permalink( get_page_by_title( 'Login' ) ) );
        wp_redirect( $redirect_to_login );
        exit;
    }

    $currency = "£";
    $user     = wp_get_current_user();

    $robo_ids = get_user_meta( $user->ID, '_robo_user_robo', true );
    $robo_id  = is_array( $robo_ids ) ? $robo_ids[0] : $robo_ids;

    $switch_robo_id = isset( $_GET['robo_id'] ) ? intval( $_GET['robo_id'] ) : 0;

    if ( $switch_robo_id ) {
        if ( is_array( $robo_ids ) && in_array( $switch_robo_id, $robo_ids ) ) {
            $robo_id = $switch_robo_id;
        }
    }

    $charge_types = array(
        Calculator_Compare::CALC_TYPE_AD_VALORAM      => __( 'Ad valorem' ),
        Calculator_Compare::CALC_TYPE_FLAT_RATE       => __( 'Flat rate' ),
        Calculator_Compare::CALC_TYPE_PER_INVESTMENT  => __( 'Per investment' ),
        Calculator_Compare::CALC_TYPE_PER_TRANSACTION => __( 'Per transaction' )
    );

    $transfer_types   = array(
        'flat_rate'      => __( 'Flat rate' ),
        'per_investment' => __( 'Per Investment' ),
    );

    if ( ! empty( $robo_id ) ) {
        $robo_api     = new ROBO_API();
        $robo_data    = $robo_api->get_robo_data( $robo_id );
        $post_title   = get_post( $robo_id )->post_title;
        $all_data = $robo_data['version'];
    } else {
        $all_data       = false;
        $post_title = '';
    }

    if ( isset( $all_data ) ) {
        /**
         * Get latest version
         */

        if(is_bool($all_data)) {
          end( $all_data );
        }
      
        $version_id = key( $all_data );
    } else {
        /**
         * This is first version
         */
        $version_id = '';
    }
    $_GET['robo_id'] = $robo_id;
    $_GET['version'] = $version_id;

    ob_start();
    if ( 0 < $robo_id && empty( $version_id ) ) {
        // _e('Unable to load your robo data', 'cplat');
        include 'templates/robo-data.php';
    } elseif ( 0 < $robo_id && 0 < $version_id ) {
        $all_data = isset( $all_data[ $version_id ] ) ? $all_data[ $version_id ] : false;
        include 'templates/robo-data.php';
    } else {
        //_e('Unable to load your robo data', 'cplat');
    }

    return ob_get_clean();
}

add_shortcode( 'robo_vendor_account', 'cplat_robo_vendor_account' );

//RSPL Task#84
function cplat_robo_update_notification( $robo_name, $updated_by = null ) {

    // Set TO email address by retrieving an email from settings->general->Platform Update Notification Email
    $admin_email = get_option( 'cplat_notification_email' );
    $to        = $admin_email;

    // Set FROM name by retrieving it from options
    $from_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

    // Set the headers
    $headers   = sprintf( 'From: %s <%s>', $from_name, $admin_email ) . "\r\n";

    // Set the Subject
    $subject   = 'Robo ' . $robo_name . ' has been updated';

    // Set the content type for current email
    add_filter( 'wp_mail_content_type', 'set_html_content_type' );

    // Set the message which needs to be send to admin
    $message = 'Platform ' . $robo_name . ' has been updated on ' . date( 'd M Y H:i' );

    // Update the message by appending the timestamp
    if ( $updated_by !== null ) {
        $message .= " by $updated_by .". "\r\n\r\n";
    } else {
        $message .= '.' . "\r\n\r\n";
    }

    // Send email to admin
    wp_mail( $to, $subject, $message, $headers );

}