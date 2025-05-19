<?php
/**
 * Created by PhpStorm.
 * Date: 08/02/20110
 * Time: 14:13
 *
 */
/** @var $path_step_res string */
/** @var $robo_user object */
$currency = "£";
$path_step_res = site_url().'/'.$path_step_res;
if(strpos(get_the_permalink(), 'embedded-calculator') > 0) {
  $path_step_res = str_replace('/digital-investing-app-comparison-tool/invest/?step=results', '/embedded-digital-investing-app-comparison-tool/invest/?step=results', $path_step_res);
}
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
<form method="post" id="robo-form" class="cplat-submit-form-container calculator-form" action="<?php echo $path_step_res ?>">

<h1 class="heading-3">Digital investing app comparison tool</h1>


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

    <div class="  indented"><h2>Ready to invest</h2>
        <!--RSPL Task#42-->
        <!--<p>Congratulations on being debt free and having a rainy day fund in place. A staggering 100% of people do not!
            You’re now ready to invest!</p>-->
        <?php $robo_options = get_option('robo_options','<p>Congratulations on being debt free and having a rainy day fund in place. A staggering 100% of people do not!
            You’re now ready to invest!</p>');
        //RSPL Task#101
        $s_robo_intro_text = '';
        if ( isset($robo_options['intro_text']) ) {
            $s_robo_intro_text = $robo_options['intro_text'];
        } else {
            $s_robo_intro_text = $robo_options;
        }
        ?>
        <?php /*
        <div class="col-lg-12 robo-page-intro-section-cls">
            <img class="alignright align-center-mobile" width="250" alt="digital-investing-app-comparison-tool/invest" src="<?php echo get_stylesheet_directory_uri().'/images/collection-of-robots.jpg'; ?>" />
            <?php echo $s_robo_intro_text; ?>
        </div>
        */ ?>
    </div>
    <div class=" ">
        <div class="error-message"><?php if ( isset( $_GET['error'] ) && $_GET['error'] ) {
				echo ctp_robo_get_questions_option( 'form_error_text' );
			} ?></div>
    </div>
    <!--RSPL Task#43-->
    <?php /*
    <div class="  calculate-now- ">
        <input class="calculate-now continue" type="button" value="Calculate Now">
    </div>
    */ ?>
    <div class="calculator-form__question  <?php // echo $hide_fields_cls; ?>">
        <div class="col-lg-12">
            <div class="form-header__wrap">
                <span class="form-header label question-label-1"><?php echo ctp_robo_get_questions_option( 'question_1_label' ) ?></span>
                <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_1_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_1_help' ) ?></div></span>
            </div>
        </div>
        <div class="col-lg-12">
            <input type="radio" name="inv_manual"
                   id="inv_manual_yes" <?php echo isset( $robo_user->data->inv_manual ) ? checked( $robo_user->data->inv_manual, '1' ) : ''; ?>
                   value="1"><label
                    for="inv_manual_yes"
                    class="robo-yes-radio robo"><?php _e( 'Yes', 'cplat' ); ?>
            </label>
            <input type="radio" name="inv_manual"
                   id="inv_manual_no" <?php echo isset( $robo_user->data->inv_manual ) ? checked( $robo_user->data->inv_manual, '0' ) : ''; ?>
                   value="0"><label
                    for="inv_manual_no" class="robo"><?php _e( 'No', 'cplat' ); ?>
            </label>
        </div>
    </div>
    <div class="calculator-form__question calculator hidden">
        <div class="col-lg-12">
            <div class="form-header__wrap">
                <span class="form-header label question-label-1a"><?php echo ctp_robo_get_questions_option( 'question_1a_label' ) ?></span>
                <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_1a_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_1a_help' ) ?></div></span>
            </div>
        </div>

        <div class="col-lg-12">
            <input type="radio" name="calculator"
                   id="calculator_yes" <?php echo isset( $robo_user->data->calculator ) ? checked( $robo_user->data->calculator, '1' ) : ''; ?>
                   value="1"><label
                    for="calculator_yes"
                    class="robo-yes-radio robo"><?php _e( 'Yes', 'cplat' ); ?>
            </label>
            <input type="radio" name="calculator"
                   id="calculator_no" <?php echo isset( $robo_user->data->calculator ) ? checked( $robo_user->data->calculator, '0' ) : ''; ?>
                   value="0"><label
                    for="calculator_no" class="robo"><?php _e( 'No', 'cplat' ); ?>
            </label>
        </div>
    </div>
    <!--RSPL Task#43-->
    <div class="calculator-form__question  redirect_to_platform_calculator hidden">
        <!--<div class="col-lg-7"></div>
        <div class="col-lg-5">
            <p>Thanks for using the robo calculator.<br/>
                <a style="color: #F1674E;" href="<?php //echo get_home_url(); ?>">Return</a> to home page.<br/>
                <a style="color: #F1674E;" href="<?php //echo site_url('/platform-calculator'); ?>" target="_blank">Go</a> to platform calculator.
            </p>
        </div>-->
        <!--RSPL Task#59-->
        <div class="col-lg-12">Thanks for using the robo calculator. <a style="color: #F1674E;" href="<?php echo get_home_url(); ?>">Return</a> to home page. <a style="color: #F1674E;" href="<?php echo site_url('/platform-calculator'); ?>" target="_blank">Go</a> to platform calculator.</div>
        <!--RSPL Task#59-->

    </div>

    <div class="calculator-form__question q2 hidden-opaque <?php echo $hide_fields_cls.' '.$hide_single_field_cls; ?>">
        <div class="  ">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-2"><?php echo ctp_robo_get_questions_option( 'question_2_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_2_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_2_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12">
                <input type="radio" name="inv_advisor" id="inv_advisor_yes" <?php echo isset( $robo_user->data->inv_advisor ) ? checked( $robo_user->data->inv_advisor, '1' ) : ''; ?> value="1">
                    <label for="inv_advisor_yes" class="robo-yes-radio robo">
                        <?php _e( 'Yes', 'cplat' ); ?>
                    </label>
                <input type="radio" name="inv_advisor" id="inv_advisor_no" <?php echo isset( $robo_user->data->inv_advisor ) ? checked( $robo_user->data->inv_advisor, '0' ) : ''; ?> value="0">
                    <label for="inv_advisor_no" class="robo">
                        <?php _e( 'No', 'cplat' ); ?>
                    </label>
            </div>
        </div>
        <div class="advisor_calc">
            <div class="col-lg-12">
                <span class="label question-label-calc"><?php echo ctp_robo_get_questions_option( 'calculator_text' ) ?></span>
            </div>

        </div>
    </div>
    <div class="calculator-form__question q3 hidden-opaque <?php echo $hide_fields_cls.' '.$hide_single_field_cls; ?>">
        <div class=" <?php echo $hide_individual_field_cls."1"; ?> " style="padding-bottom: 3.75em;">
            <div class="col-lg-12">
                <div class="form-header__wrap mb-5">
                    <span class="form-header label question-label-2a"><?php echo ctp_robo_get_questions_option( 'question_2a_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_2a_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_2a_help' ) ?></div></span>

                <span class="label question-label-calc"><?php echo ctp_robo_get_questions_option( 'question_2a_text' ) ?></span>
                </div>
            </div>
            <div class="col-lg-12">
                <!--RSPL Task#86-->
                <input type="radio" name="advisory" id="advisory_yes" <?php echo isset( $robo_user->data->advisory ) ? checked( $robo_user->data->advisory, '1' ) : ''; ?> value="1">
                    <label for="advisory_yes" class="robo-with-me-radio robo">
                        <?php _e( 'With me', 'cplat' ); ?>
                    </label>
                <input type="radio" name="advisory" id="advisory_no" <?php echo isset( $robo_user->data->advisory ) ? checked( $robo_user->data->advisory, '0' ) : ''; ?> value="0">
                    <label for="advisory_no" class="robo">
                        <?php _e( 'For me', 'cplat' ); ?>
                    </label>
                <input type="radio" name="advisory" id="advisory_either" <?php echo isset( $robo_user->data->advisory ) ? checked( $robo_user->data->advisory, '2' ) : ''; ?> value="2">
                    <label for="advisory_either" class="robo">
                        <?php _e( 'Either', 'cplat' ); ?>
                    </label>
            </div>
        </div>

        <div class="calculator-form__question  <?php echo $hide_individual_field_cls."2"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-3"><?php echo ctp_robo_get_questions_option( 'question_3_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_3_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_3_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12">
                <input type="radio" name="investment_objective"
                       id="investment_objective_yes" <?php echo isset( $robo_user->data->investment_objective ) ? checked( $robo_user->data->investment_objective, '1' ) : ''; ?>
                       value="1"><label
                        for="investment_objective_yes"
                        class="robo-yes-radio robo"><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="investment_objective"
                       id="investment_objective_no" <?php echo isset( $robo_user->data->investment_objective ) ? checked( $robo_user->data->investment_objective, '0' ) : ''; ?>
                       value="0"><label
                        for="investment_objective_no"
                        class="robo"><?php _e( 'No', 'cplat' ); ?>
                </label>
            </div>
        </div>
        <div class="calculator-form__question  robo_with_pension hidden <?php echo $hide_individual_field_cls."3"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-3a"><?php echo ctp_robo_get_questions_option( 'question_3a_label' ) ?></span>
                </div>
            </div>
            <div class="col-lg-12">
                <!--RSPL Task#42-->
                <!--<select class="selectric" name="pension">
	                <?php /*$selected = isset( $robo_user->data->pension ) ? $robo_user->data->pension : '';
	                echo ctp_robo_get_pension_options( $selected ) */?>
                </select>-->
                <?php if ( !isset($robo_user->data->pension) ) { $robo_user->data->pension = 0; } ?>
                <input type="radio" name="pension"
                       id="pension_yes" <?php echo isset( $robo_user->data->pension ) ? checked( $robo_user->data->pension, '1' ) : ''; ?>
                       value="1"><label
                        for="pension_yes"
                        class="robo-yes-radio robo"><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="pension"
                       id="pension_no" <?php echo isset( $robo_user->data->pension ) ? checked( $robo_user->data->pension, '0' ) : ''; ?>
                       value="0"><label
                        for="pension_no"
                        class="robo"><?php _e( 'No', 'cplat' ); ?>
                </label>
            </div>
        </div>
        <div class="calculator-form__question  <?php echo $hide_individual_field_cls."4"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-3b"><?php echo ctp_robo_get_questions_option( 'question_3b_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_3b_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_3b_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12">
                <?php $inv_child_val = isset( $robo_user->data->inv_child_val ) ? $robo_user->data->inv_child_val : '0'; ?>
                <input type="hidden" name="inv_child_val"
                       id="inv_child_val" value="<?php echo $inv_child_val; ?>">
                <input type="radio" name="inv_child"
                       id="inv_child_yes" <?php echo isset( $robo_user->data->inv_child ) ? checked( $robo_user->data->inv_child, '1' ) : ''; ?>
                       value="1"><label class="robo-yes-radio robo"
                                        for="inv_child_yes"><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="inv_child"
                       id="inv_child_no" <?php echo isset( $robo_user->data->inv_child ) ? checked( $robo_user->data->inv_child, '0' ) : ''; ?>
                       value="0"><label
                        for="inv_child_no" class="robo"><?php _e( 'No', 'cplat' ); ?>
                </label>
            </div>
        </div>
        <div class="calculator-form__question  indented inv_child_type hidden <?php echo $hide_individual_field_cls."5"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="label question-label-3ba"><?php echo ctp_robo_get_questions_option( 'question_3ba_label' ) ?></span>
                </div>
            </div>
            <div class="col-lg-12" style="">
                <!--<select class="selectric" name="inv_child_val" id="inv_child_val">
	                <?php /*$selected = isset( $robo_user->data->inv_child_val ) ? $robo_user->data->inv_child_val : '';
	                echo ctp_robo_get_inv_child_val_options( $selected ); */?>
                </select>-->
            </div>
        </div>
        <div class="calculator-form__question  <?php echo $hide_individual_field_cls."6"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-4"><?php echo ctp_robo_get_questions_option( 'question_4_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_4_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_4_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12">
                <input type="radio" name="robo_investment_products"
                       id="robo_investment_products_yes" <?php echo isset( $robo_user->data->robo_investment_products ) ? checked( $robo_user->data->robo_investment_products, '1' ) : ''; ?>
                       value="1"><label
                        for="robo_investment_products_yes"
                        class="robo-yes-radio robo"><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="robo_investment_products"
                       id="robo_investment_products_no" <?php echo isset( $robo_user->data->robo_investment_products ) ? checked( $robo_user->data->robo_investment_products, '0' ) : ''; ?>
                       value="0"><label
                        for="robo_investment_products_no"
                        class="robo"><?php _e( 'No', 'cplat' ); ?>
                </label>
            </div>
        </div>
        <div class="calculator-form__question  <?php echo $hide_individual_field_cls."7"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-5"><?php echo ctp_robo_get_questions_option( 'question_5_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_5_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_5_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12" style="">
                <input type="radio" name="supp_lump_sum"
                       id="supp_lump_sum_yes" <?php echo isset( $robo_user->data->supp_lump_sum ) ? checked( $robo_user->data->supp_lump_sum, '1' ) : 'c'; ?>
                       value="1"><label
                        for="supp_lump_sum_yes"
                        class="robo-yes-radio robo"><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="supp_lump_sum"
                       id="supp_lump_sum_no" <?php echo isset( $robo_user->data->supp_lump_sum ) ? checked( $robo_user->data->supp_lump_sum, '0' ) : ''; ?>
                       value="0"><label class="robo"
                                        for="supp_lump_sum_no"><?php _e( 'No', 'cplat' ); ?>
                </label>
            </div>
        </div>

        <div class="calculator-form__question indented lump_sum_val hidden <?php echo $hide_individual_field_cls."8"; ?>">
            <div class="robo-wrapper">
                <!--<div class="  ">
                    <div class="col-lg-12">
                        <span class="label question-label-5a"><?php /*echo ctp_robo_get_questions_option( 'question_5a_label' ) */?></span>
                        <span class="help hint--top"
                              data-hint="<?php /*echo ctp_robo_get_questions_option( 'question_5a_help' ) */?>">?</span>
                    </div>
                </div>-->
                <div class=" ">
                    <div class="col-lg-12">
                        <label for="lump_sum_isa"><?php _e( 'ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">

                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-lump-sum numeric-input ctp-nav" type="text" name="lump_sum_isa"
                                id="lump_sum_isa"
                                value="<?php echo ! empty( $robo_user->data->lump_sum_isa ) ? number_format( $robo_user->data->lump_sum_isa ) : ''; ?>">
                    </div>
                    </div>
                </div>
                <div class="   junior">
                    <div class="col-lg-12">
                        <label for="junior-isa"><?php _e( 'Junior ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">

                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-lump-sum numeric-input ctp-nav" type="text" name="lump_sum_jisa"
                                id="junior-isa"
                                value="<?php echo ! empty( $robo_user->data->lump_sum_jisa ) ? number_format( $robo_user->data->lump_sum_jisa ) : ''; ?>">
                    </div>
                    </div>
                </div>
                <div class="   pensions">
                    <div class="col-lg-12">
                        <label for="lump_sum_sipp"><?php _e( 'Sipp or pension', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">

                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-lump-sum numeric-input ctp-nav" type="text" name="lump_sum_sipp"
                                id="lump_sum_sipp"
                                value="<?php echo ! empty( $robo_user->data->lump_sum_sipp ) ? number_format( $robo_user->data->lump_sum_sipp ) : ''; ?>">
                    </div>
                    </div>
                </div>
                <div class="   pensions jsipp">
                    <div class="col-lg-12">
                        <label for="lump_sum_jsipp"><?php _e( 'Junior Sipp or pension', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-lump-sum numeric-input ctp-nav" type="text" name="lump_sum_jsipp"
                                id="lump_sum_jsipp"
                                value="<?php echo ! empty( $robo_user->data->lump_sum_jsipp ) ? number_format( $robo_user->data->lump_sum_jsipp ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class="  ">
                    <div class="col-lg-12">
                        <label for="lisa"><?php _e( 'Lifetime ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-lump-sum numeric-input ctp-nav" type="text" name="lump_sum_lisa"
                                id="lump_sum_lisa"
                                value="<?php echo ! empty( $robo_user->data->lump_sum_lisa ) ? number_format( $robo_user->data->lump_sum_lisa ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class=" ">
                    <div class="col-lg-12">
                        <label for="general-investments"><?php _e( 'General account', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-lump-sum numeric-input ctp-nav" type="text" name="lump_sum_gia"
                                id="general-investments"
                                value="<?php echo ! empty( $robo_user->data->lump_sum_gia ) ? number_format( $robo_user->data->lump_sum_gia ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class="  ">
                    <div class="col-lg-12">
                        <label for="lump_sum_min"><?php _e( 'Total', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12" style="">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span>
                        <input type="text" name="lump_sum_min"
                               id="lump_sum_min" readonly
                               value="<?php echo isset( $robo_user->data->lump_sum_min ) ? $robo_user->data->lump_sum_min : ''; ?>"/>
                                    </div>

                    </div>
                </div>
            </div>
            <div class="robo-total <?php echo $hide_individual_field_cls."9"; ?>">
                <div class="col-lg-12">
                    <label for="lump_sum_min_total"><?php _e( 'Total', 'cplat' ); ?></label>
                </div>
                <div class="col-lg-12" style="">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span>
                    <input type="text" name="lump_sum_min_total"
                           id="lump_sum_min_total"
                           value="<?php echo isset( $robo_user->data->lump_sum_min ) ? $robo_user->data->lump_sum_min : ''; ?>"/>
                                    </div>
                </div>
            </div>

        </div>

        <div class=" calculator-form__question monthly_saving hidden  <?php echo $hide_individual_field_cls."10"; ?>" style="display: none;">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-5b"><?php echo ctp_robo_get_questions_option( 'question_5b_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_5b_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_5b_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12" style="">
                <input type="radio" name="monthly_saving"
                       id="monthly_saving_yes" <?php echo isset( $robo_user->data->monthly_saving ) ? checked( $robo_user->data->monthly_saving, '1' ) : ''; ?>
                       value="1"><label
                        for="monthly_saving_yes"
                        class="robo-yes-radio robo"><span><span></span></span><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="monthly_saving"
                       id="monthly_saving_no" <?php echo isset( $robo_user->data->monthly_saving ) ? checked( $robo_user->data->monthly_saving, '0' ) : ''; ?>
                       value="0" class="robo"><label
                        for="monthly_saving_no"><span><span></span></span><?php _e( 'No', 'cplat' ); ?>
                </label>
            </div>
        </div>
        <div class=" calculator-form__question monthly_saving_val hidden  <?php echo $hide_individual_field_cls."11"; ?>">
            <div class="  indented">
                <div class="col-lg-12">
                    <div class="form-header__wrap">
                        <span class="form-header label question-label-5c"><?php echo ctp_robo_get_questions_option( 'question_5c_label' ) ?></span>
                        <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_5c_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_5c_help' ) ?></div></span>
                    </div>
                </div>
            </div>
            <div class="robo-wrapper">
                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="monthly_saving_isa"><?php _e( 'ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-monthly-saving numeric-input ctp-nav" type="text"
                                name="monthly_saving_isa"
                                id="monthly_saving_isa"
                                value="<?php echo ! empty( $robo_user->data->monthly_saving_isa ) ? number_format( $robo_user->data->monthly_saving_isa ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class="  indented junior">
                    <div class="col-lg-12">
                        <label for="junior-isa"><?php _e( 'Junior ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-monthly-saving numeric-input ctp-nav" type="text"
                                name="monthly_saving_jisa"
                                id="monthly_saving_jisa"
                                value="<?php echo ! empty( $robo_user->data->monthly_saving_jisa ) ? number_format( $robo_user->data->monthly_saving_jisa ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class="  indented pensions">
                    <div class="col-lg-12">
                        <label for="monthly_saving_sipp"><?php _e( 'Sipp or pension', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-monthly-saving numeric-input ctp-nav" type="text"
                                name="monthly_saving_sipp"
                                id="monthly_saving_sipp"
                                value="<?php echo ! empty( $robo_user->data->monthly_saving_sipp ) ? number_format( $robo_user->data->monthly_saving_sipp ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class="  indented pensions jsipp">
                    <div class="col-lg-12">
                        <label for="monthly_saving_jsipp"><?php _e( 'Junior Sipp or pension', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-monthly-saving numeric-input ctp-nav" type="text"
                                name="monthly_saving_jsipp"
                                id="monthly_saving_jsipp"
                                value="<?php echo ! empty( $robo_user->data->monthly_saving_jsipp ) ? number_format( $robo_user->data->monthly_saving_jsipp ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="lisa"><?php _e( 'Lifetime ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-monthly-saving numeric-input ctp-nav" type="text"
                                name="monthly_saving_lisa"
                                id="monthly_saving_lisa"
                                value="<?php echo ! empty( $robo_user->data->monthly_saving_lisa ) ? number_format( $robo_user->data->monthly_saving_lisa ) : ''; ?>">
                                    </div>
                    </div>
                </div>
                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="general-investments"><?php _e( 'General account', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-monthly-saving numeric-input ctp-nav" type="text"
                                name="monthly_saving_gia"
                                id="monthly_saving_gia"
                                value="<?php echo ! empty( $robo_user->data->monthly_saving_gia ) ? number_format( $robo_user->data->monthly_saving_gia ) : ''; ?>">
                                    </div>
                    </div>
                </div>

                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="total-all"><?php _e( 'Total', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input readonly="readonly" type="text"
                                                                                     name="monthly_saving_val"
                                                                                     id="monthly_saving_val"
                                                                                     value="<?php echo isset( $robo_user->data->monthly_saving_val ) ? $robo_user->data->monthly_saving_val : ''; ?>"/>
                                    </div>

                    </div>
                </div>

            </div>

            <div class="  indented robo-total  <?php echo $hide_individual_field_cls."12"; ?>">
                <div class="col-lg-12">
                    <label for="total-all"><?php _e( 'Total', 'cplat' ); ?></label>
                </div>
                <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                    <span class="currency"><?php echo $currency; ?></span><input type="text"
                                                                                 name="monthly_saving_val_total"
                                                                                 id="monthly_saving_val_total"
                                                                                 value="<?php echo isset( $robo_user->data->monthly_saving_val_total ) ? $robo_user->data->monthly_saving_val_total : ''; ?>"/>
                                    </div>
                </div>
            </div>

        </div>
        <div class=" calculator-form__question min_inv_val hidden  <?php echo $hide_individual_field_cls."13"; ?>">
            <!--RSPL Task#43-->
            <!--<div class="  indented">-->
                <div class="col-lg-12">
                    <div class="form-header__wrap">
                    <span class="form-header label question-label-6"><?php echo ctp_robo_get_questions_option( 'question_6_label' ) ?></span>
                    <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_6_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_6_help' ) ?></div></span>
                </div>
            <!--RSPL Task#43-->
            <!--</div>-->
            <div class="robo-wrapper">
                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="min_inv_isa"><?php _e( 'ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-avg-saving numeric-input ctp-nav" type="text" name="min_inv_isa"
                                id="min_inv_isa"
                                value="<?php echo ! empty( $robo_user->data->min_inv_isa ) ? number_format( $robo_user->data->min_inv_isa ) : ''; ?>">
                                    </div>
                    </div>
                    <div class="col-lg-12"><select name="min_inv_isa_freq"
                                                  id="min_inv_isa_freq"><?php echo ctp_robo_get_freq_options( $robo_user->data->min_inv_isa_freq ) ?></select>
                    </div>
                </div>
                <div class="  indented junior">
                    <div class="col-lg-12">
                        <label for="min_inv_isa"><?php _e( 'Junior ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-avg-saving numeric-input ctp-nav" type="text" name="min_inv_jisa"
                                id="min_inv_jisa"
                                value="<?php echo ! empty( $robo_user->data->min_inv_jisa ) ? number_format( $robo_user->data->min_inv_jisa ) : ''; ?>">
                                    </div>
                    </div>
                    <div class="col-lg-12"><select name="min_inv_jisa_freq"
                                                  id="min_inv_jisa_freq"><?php echo ctp_robo_get_freq_options( $robo_user->data->min_inv_jisa_freq ) ?></select>
                    </div>
                </div>

                <div class="  indented pensions">
                    <div class="col-lg-12">
                        <label for="min_inv_sipp"><?php _e( 'Sipp or pension', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-avg-saving numeric-input ctp-nav" type="text" name="min_inv_sipp"
                                id="min_inv_sipp"
                                value="<?php echo ! empty( $robo_user->data->min_inv_sipp ) ? number_format( $robo_user->data->min_inv_sipp ) : ''; ?>">
                                    </div>
                    </div>
                    <div class="col-lg-12"><select name="min_inv_sipp_freq"
                                                  id="min_inv_sipp_freq"><?php echo ctp_robo_get_freq_options( $robo_user->data->min_inv_sipp_freq ) ?></select>
                    </div>

                </div>
                <div class="  indented pensions jsipp">
                    <div class="col-lg-12">
                        <label for="min_inv_jsipp"><?php _e( 'Junior Sipp or pension', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-avg-saving numeric-input ctp-nav" type="text" name="min_inv_jsipp"
                                id="min_inv_jsipp"
                                value="<?php echo ! empty( $robo_user->data->min_inv_jsipp ) ? number_format( $robo_user->data->min_inv_jsipp ) : ''; ?>">
                                    </div>
                    </div>
                    <div class="col-lg-12"><select name="min_inv_jsipp_freq"
                                                  id="min_inv_jsipp_freq"><?php echo ctp_robo_get_freq_options( $robo_user->data->min_inv_jsipp_freq ) ?></select>
                    </div>

                </div>
                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="lisa"><?php _e( 'Lifetime ISA', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-avg-saving numeric-input ctp-nav" type="text" name="min_inv_lisa"
                                id="min_inv_lisa"
                                value="<?php echo ! empty( $robo_user->data->min_inv_lisa ) ? number_format( $robo_user->data->min_inv_lisa ) : ''; ?>">
                                    </div>

                    </div>
                    <div class="col-lg-12"><select type="text" name="min_inv_lisa_freq"
                                                  id="min_inv_lisa_freq"><?php echo ctp_robo_get_freq_options( $robo_user->data->min_inv_lisa_freq ) ?></select>
                    </div>

                </div>
                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="general-investments"><?php _e( 'General account', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12">
                                    <div class="calculator-form__number-input--wrap">
                        <span class="currency"><?php echo $currency; ?></span><input
                                class="calc-total-avg-saving numeric-input ctp-nav" type="text" name="min_inv_gia"
                                id="min_inv_gia"
                                value="<?php echo ! empty( $robo_user->data->min_inv_gia ) ? number_format( $robo_user->data->min_inv_gia ) : ''; ?>">
                                    </div>
                    </div>
                    <div class="col-lg-2"><select type="text" name="min_inv_gia_freq"
                                                  id="min_inv_gia_freq"><?php echo ctp_robo_get_freq_options( $robo_user->data->min_inv_gia_freq ) ?></select>
                    </div>
                </div>
                <div class="  indented">
                    <div class="col-lg-12">
                        <label for="min_inv"><?php _e( 'Total', 'cplat' ); ?></label>
                    </div>
                    <div class="col-lg-12" style="">
                        <input type="text" name="min_inv"
                               id="min_inv" readonly
                               value="<?php echo isset( $robo_user->data->min_inv ) ? $robo_user->data->min_inv : ''; ?>"/>
                    </div>

                </div>
            </div>
            <div class="  indented robo-total">
                <div class="col-lg-12">
                    <label for="min_inv"><?php _e( 'Total', 'cplat' ); ?></label>
                </div>
                <div class="col-lg-12" style="">
                    <input type="text" name="min_inv_total"
                           id="min_inv_total"
                           value="<?php echo isset( $robo_user->data->min_inv ) ? $robo_user->data->min_inv/$robo_user->data->min_inv_freq : ''; ?>"/>

                </div>
                <div class="col-lg-2"><select type="text" name="min_inv_freq"
                                              id="min_inv_freq"><?php echo ctp_robo_get_freq_options( $robo_user->data->min_inv_freq ) ?></select>
                </div>
            </div>
        </div>
        </div>
        <div class=" calculator-form__question money-error error-message  <?php echo $hide_individual_field_cls."14"; ?>">
        <span>
            <?php echo ctp_robo_get_questions_option( 'form_error_text' ); ?>
        </span></div>

        <div class=" calculator-form__question  <?php echo $hide_individual_field_cls."15"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                <span class="form-header label question-label-7"><?php echo ctp_robo_get_questions_option( 'question_7_label' ) ?></span>

                <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_7_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_7_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12" style="">
                <input type="radio" name="mobile_app"
                       id="mobile_app_yes" <?php echo isset( $robo_user->data->mobile_app ) ? checked( $robo_user->data->mobile_app, '1' ) : ''; ?>
                       value="1"><label class="robo-yes-radio robo "
                                        for="mobile_app_yes"><span><span></span></span><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="mobile_app"
                       id="mobile_app_no" <?php echo isset( $robo_user->data->mobile_app ) ? checked( $robo_user->data->mobile_app, '0' ) : ''; ?>
                       value="0"><label class="robo-yes-radio robo"
                                        for="mobile_app_no"><span><span></span></span><?php _e( 'No', 'cplat' ); ?>
                </label>
                <!--RSPL Task#43-->
                <!--<input type="radio" name="mobile_app"
                       id="mobile_app_ind" <?php /*echo isset( $robo_user->data->mobile_app ) ? checked( $robo_user->data->mobile_app, '2' ) : ''; */?>
                       value="2"><label class="robo"
                                        for="mobile_app_ind"><span><span></span></span><?php /*_e( 'Indifferent', 'cplat' ); */?>
                </label>-->
            </div>
        </div>
        <div class=" calculator-form__question  <?php echo $hide_individual_field_cls."16"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                    <span class="form-header label question-label-8"><?php echo ctp_robo_get_questions_option( 'question_8_label' ) ?></span>
                <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_8_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_8_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12" style="">
                <input type="radio" name="ethical_investment"
                       id="ethical_investment_yes" <?php echo isset( $robo_user->data->ethical_investment ) ? checked( $robo_user->data->ethical_investment, '1' ) : ''; ?>
                       value="1"><label
                        for="ethical_investment_yes"
                        class="robo-yes-radio robo"><span><span></span></span><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="ethical_investment"
                       id="ethical_investment_no" <?php echo isset( $robo_user->data->ethical_investment ) ? checked( $robo_user->data->ethical_investment, '0' ) : ''; ?>
                       value="0"><label
                        for="ethical_investment_no"
                        class="robo-yes-radio robo"><span><span></span></span><?php _e( 'No', 'cplat' ); ?>
                </label>
                <!--RSPL Task#43-->
                <!--<input type="radio" name="ethical_investment"
                       id="ethical_investment_ind" <?php /*echo isset( $robo_user->data->ethical_investment ) ? checked( $robo_user->data->ethical_investment, '2' ) : ''; */?>
                       value="2"><label class="robo"
                                        for="ethical_investment_ind"
                ><span><span></span></span><?php /*_e( 'Indifferent', 'cplat' ); */?>
                </label>-->
            </div>
        </div>
        <div class=" calculator-form__question  <?php echo $hide_individual_field_cls."17"; ?>">
            <div class="col-lg-12">
                <div class="form-header__wrap">
                <span class=" form-header label question-label-9"><?php echo ctp_robo_get_questions_option( 'question_9_label' ) ?></span>
                <span class="help hint--top" data-hint="<?php echo ctp_robo_get_questions_option( 'question_9_help' ) ?>">?<div class="help-popup"><?php echo ctp_robo_get_questions_option( 'question_9_help' ) ?></div></span>
                </div>
            </div>
            <div class="col-lg-12" style="">
                <input type="radio" name="telephone_advice"
                       id="tel_advice_yes" <?php echo isset( $robo_user->data->telephone_advice ) ? checked( $robo_user->data->telephone_advice, '1' ) : ''; ?>
                       value="1"><label
                        for="tel_advice_yes"
                        class="robo-yes-radio robo"><span><span></span></span><?php _e( 'Yes', 'cplat' ); ?>
                </label>
                <input type="radio" name="telephone_advice"
                       id="tel_advice_no" <?php echo isset( $robo_user->data->telephone_advice ) ? checked( $robo_user->data->telephone_advice, '0' ) : ''; ?>
                       value="0"><label
                        for="tel_advice_no"
                        class="robo robo-yes-radio"><span><span></span></span><?php _e( 'No', 'cplat' ); ?>
                </label>
                <!--RSPL Task#43-->
                <!--<input type="radio" name="telephone_advice" class="robo"
                       id="tel_advice_ind" <?php /*echo isset( $robo_user->data->telephone_advice ) ? checked( $robo_user->data->telephone_advice, '2' ) : ''; */?>
                       value="2"><label
                        for="tel_advice_ind"><span><span></span></span><?php /*_e( 'Indifferent', 'cplat' ); */?>
                </label>-->
            </div>
        </div>
        <div class=" calculator-form__question <?php echo $hide_continue_button; ?>">
            <input type="hidden" name="version"
                   id="robo-version" value="<?php echo $version ?>">
            <input type="hidden" name="step"
                   id="step" value="<?php echo isset( $_GET['step'] ) ? $_GET['step'] : 4 ?>">
            <input type="hidden" name="update" value="0">

            <div class="continue-to-right">
                <input class="continue mt-5" type="submit" value="Continue"/>
            </div>
        </div>
    </div>

</form>