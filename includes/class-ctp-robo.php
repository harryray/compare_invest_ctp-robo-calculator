<?php

/**
 * Created by PhpStorm.
 * Date: 10/12/2018
 * Time: 12:05
 */
class Robo_Calculator {
	private $rules;
	public $user;
	const POST_TYPE = 'robo-adviser';
	const ROBO_PAGE_URL = 'digital-investing-app-comparison-tool/invest';
	const ROLE = 'robo-editor';
	const PRODUCT_ISA = 'isa';
	const PRODUCT_GIA = 'gia';
	const PRODUCT_SIPP = 'sipp';
	const PRODUCT_JSIPP = 'jsipp';
	const PRODUCT_JISA = 'jisa';

	const PRODUCT_LISA = 'lisa';
	const POST_META_KEY = 'robo_advisor_data';
	const USER_META_KEY = 'robo_financial_data';
	const USER_RESULTS_META_KEY = 'robo_results';
	const USER_ROBO_META_KEY = 'user_robo_data';

	const FIELD_TOTAL_SAVINGS = 'total_savings';
	const FIELD_PER_MONTH_SAVINGS = 'per_month_savings';
	const FIELD_SAVING_GOALS = 'saving_goals';
	const ADVICE_OPT = [
		'detailed'   => 'Fully regulated (algorithms and those with by the hour advice)',
		'simplified' => 'simplified advice, guidance or higher',
		'none'       => "Don't mind"
	];

	//form fields
	const FIELD_VERSION_NAME = 'version_name';
	const FIELD_STEP = 'step';
	const FIELD_DEBT_FREE = 'debt_free';
	const FIELD_RAINY_DAY = 'rainy_day';
	const FIELD_INV_MANUAL = 'inv_manual';
	const FIELD_INV_ADVISOR = 'inv_advisor';
	const FIELD_ADVISORY = 'advisory';
	const FIELD_INV_OBJECTIVE = 'investment_objective';
	const FIELD_TIME_TO_MANAGE = 'time_to_manage';
	const FIELD_WEB_HELP = 'web_help';
	const FIELD_INV_CHILD = 'inv_child';
	const FIELD_INV_CHILD_VAL = 'inv_child_val';
	const FIELD_SUPP_LUMP_SUM = 'supp_lump_sum';
	const FIELD_LUMP_SUM_ISA = 'lump_sum_isa';
	const FIELD_LUMP_SUM_JISA = 'lump_sum_jisa';
	const FIELD_LUMP_SUM_GIA = 'lump_sum_gia';
	const FIELD_LUMP_SUM_SIPP = 'lump_sum_sipp';
	const FIELD_LUMP_SUM_JSIPP = 'lump_sum_jsipp';
	const FIELD_LUMP_SUM_LISA = 'lump_sum_lisa';
	const FIELD_LUMP_SUM_MIN = 'lump_sum_min';
	const FIELD_LUMP_SUM_VAL = 'lump_sum_val';
	const FIELD_MONTHLY_SAVING = 'monthly_saving';
	const FIELD_MONTHLY_SAVING_ISA = 'monthly_saving_isa';
	const FIELD_MONTHLY_SAVING_JISA = 'monthly_saving_jisa';
	const FIELD_MONTHLY_SAVING_GIA = 'monthly_saving_gia';
	const FIELD_MONTHLY_SAVING_SIPP = 'monthly_saving_sipp';
	const FIELD_MONTHLY_SAVING_JSIPP = 'monthly_saving_jsipp';
	const FIELD_MONTHLY_SAVING_LISA = 'monthly_saving_lisa';
	const FIELD_MONTHLY_SAVING_VAL = 'monthly_saving_val';
	const FIELD_MIN_INV = 'min_inv';
	const FIELD_MIN_INV_ISA = 'min_inv_isa';
	const FIELD_MIN_INV_JISA = 'min_inv_jisa';
	const FIELD_MIN_INV_GIA = 'min_inv_gia';
	const FIELD_MIN_INV_SIPP = 'min_inv_sipp';
	const FIELD_MIN_INV_LISA = 'min_inv_lisa';
	const FIELD_MIN_INV_ISA_FREQ = 'min_inv_isa_freq';
	const FIELD_MIN_INV_JISA_FREQ = 'min_inv_jisa_freq';
	const FIELD_MIN_INV_GIA_FREQ = 'min_inv_gia_freq';
	const FIELD_MIN_INV_SIPP_FREQ = 'min_inv_sipp_freq';
    //RSPL TASK#40
    const FIELD_MIN_INV_JSIPP = 'min_inv_jsipp';
	const FIELD_MIN_INV_JSIPP_FREQ = 'min_inv_jsipp_freq';
	const FIELD_ANNUAL_TOP_UP = 'annual_top_up';
	const FIELD_MIN_INV_LISA_FREQ = 'min_inv_lisa_freq';
	const FIELD_MIN_INV_FREQ = 'min_inv_freq';

    //	RSPL TASK#16
    const FIELD_LUMP_SUM_MIN_TOTAL = 'lump_sum_min_total';
    const FIELD_MIN_INV_VAL_TOTAL = 'min_inv_val_total';
    const FIELD_LUMP_SUM_VAL_TOTAL = 'lump_sum_val_total';
    const FIELD_PENSION = 'pension';

	const FIELD_MOBILE_APP = 'mobile_app';
	const FIELD_TEL_ADVICE = 'telephone_advice';
	const FIELD_ROBO_INV_PORODUCTS = 'robo_investment_products';
	const FIELD_CALCULATOR = 'calculator';
	const FIELD_WEB_HELP_CALCULATOR = 'web_help_calculator';
	const FIELD_ETHICAL_INVESTMENT = 'ethical_investment';
	const LUMP_SUM_FREQ = [
		4 => "Quarterly",
		2 => "Half Yearly",
		1 => "Annually"
	];

	const INV_CHILD_OPTIONS = [
		1 => "Junior ISA",
		2 => "Junior SIPP",
		3 => "Both"
	];

	const PENSION_OPTIONS = [
		0 => "No",
		1 => "Yes",
		2 => "Don't know"
	];


	public function __construct( $user = null ) {
		if ( $user ) {
			$this->user = $user;
		} else {
            if ( is_user_logged_in() ) {
                $this->user = wp_get_current_user();
            } else {
                $this->user = new stdClass();
            }
		}
	}

	public function user_rules() {

		$rules                                    = [];
		$rules[ self::FIELD_VERSION_NAME ]        = 'sanitize_text_field';
		$rules[ self::FIELD_STEP ]                = 'intval';
		$rules[ self::FIELD_INV_MANUAL ]          = 'intval';
		$rules[ self::FIELD_INV_ADVISOR ]         = 'intval';
		$rules[ self::FIELD_INV_OBJECTIVE ]       = 'intval';
		$rules[ self::FIELD_ADVISORY ]            = 'intval';
		$rules[ self::FIELD_TIME_TO_MANAGE ]      = 'intval';
		$rules[ self::FIELD_ROBO_INV_PORODUCTS ]  = 'intval';
		$rules[ self::FIELD_WEB_HELP ]            = 'intval';
		$rules[ self::FIELD_WEB_HELP_CALCULATOR ] = 'intval';
		$rules[ self::FIELD_CALCULATOR ]          = 'intval';
		$rules[ self::FIELD_INV_CHILD ]           = 'intval';
		$rules[ self::FIELD_INV_CHILD_VAL ]       = 'intval';
		$rules[ self::FIELD_SUPP_LUMP_SUM ]       = 'intval';
		$rules[ self::FIELD_LUMP_SUM_GIA ]        = 'robo_sanitize_number';
		$rules[ self::FIELD_LUMP_SUM_ISA ]        = 'robo_sanitize_number';
		$rules[ self::FIELD_LUMP_SUM_JISA ]       = 'robo_sanitize_number';
		$rules[ self::FIELD_LUMP_SUM_JSIPP ]      = 'robo_sanitize_number';
		$rules[ self::FIELD_LUMP_SUM_SIPP ]       = 'robo_sanitize_number';
		$rules[ self::FIELD_LUMP_SUM_LISA ]       = 'robo_sanitize_number';
		$rules[ self::FIELD_MONTHLY_SAVING ]      = 'intval';
		$rules[ self::FIELD_MONTHLY_SAVING_GIA ]  = 'robo_sanitize_number';
		$rules[ self::FIELD_MONTHLY_SAVING_ISA ]  = 'robo_sanitize_number';
		$rules[ self::FIELD_MONTHLY_SAVING_JISA ] = 'robo_sanitize_number';
		$rules[ self::FIELD_MONTHLY_SAVING_LISA ] = 'robo_sanitize_number';
		$rules[ self::FIELD_MONTHLY_SAVING_SIPP ] = 'robo_sanitize_number';
		$rules[ self::FIELD_MONTHLY_SAVING_JSIPP ] = 'robo_sanitize_number';
		$rules[ self::FIELD_MONTHLY_SAVING_VAL ]  = 'robo_sanitize_number';
		$rules[ self::FIELD_LUMP_SUM_VAL ]        = 'robo_sanitize_number';
		$rules[ self::FIELD_LUMP_SUM_MIN ]        = 'robo_sanitize_number';
		$rules[ self::FIELD_MIN_INV ]             = 'robo_sanitize_number';
		$rules[ self::FIELD_MIN_INV_GIA ]         = 'robo_sanitize_number';
		$rules[ self::FIELD_MIN_INV_ISA ]         = 'robo_sanitize_number';
		$rules[ self::FIELD_MIN_INV_JISA ]        = 'robo_sanitize_number';
		$rules[ self::FIELD_MIN_INV_LISA ]        = 'robo_sanitize_number';
		$rules[ self::FIELD_MIN_INV_SIPP ]        = 'robo_sanitize_number';
        //RSPL TASK#40
		$rules[ self::FIELD_MIN_INV_JSIPP ]        = 'robo_sanitize_number';
		$rules[ self::FIELD_MIN_INV_GIA_FREQ ]    = 'intval';
		$rules[ self::FIELD_MIN_INV_ISA_FREQ ]    = 'intval';
		$rules[ self::FIELD_MIN_INV_JISA_FREQ ]   = 'intval';
		$rules[ self::FIELD_MIN_INV_LISA_FREQ ]   = 'intval';
		$rules[ self::FIELD_MIN_INV_FREQ ]   = 'intval';
		$rules[ self::FIELD_MIN_INV_SIPP_FREQ ]   = 'intval';
        //RSPL TASK#40
		$rules[ self::FIELD_MIN_INV_JSIPP_FREQ ]   = 'intval';
		$rules[ self::FIELD_ANNUAL_TOP_UP ]   = 'intval';
		$rules[ self::FIELD_TEL_ADVICE ]          = 'intval';
		$rules[ self::FIELD_MOBILE_APP ]          = 'intval';
		$rules[ self::FIELD_ETHICAL_INVESTMENT ]  = 'intval';
        //	RSPL TASK#16
		$rules[ self::FIELD_LUMP_SUM_MIN_TOTAL ]  = 'intval';
		$rules[ self::FIELD_MIN_INV_VAL_TOTAL ]   = 'intval';
		$rules[ self::FIELD_LUMP_SUM_VAL_TOTAL ]  = 'intval';
		$rules[ self::FIELD_PENSION ]  = 'intval';

		return $rules;
	}

	public function user_data_sanitize( $data ) {
		$clean_meta = [];
		$rules      = $this->user_rules();
		if ( ! empty( $data[ self::FIELD_ROBO_INV_PORODUCTS ] ) && $data[ self::FIELD_ROBO_INV_PORODUCTS ] === 1 ) {
			if ( ! empty( $data['min_inv_val_total'] ) ) {
				$data['min_inv_val'] = $data['min_inv_val_total'];
			}
			if ( ! empty( $data['monthly_saving_val_total'] ) ) {
				$data['monthly_saving_val'] = $data['monthly_saving_val_total'];
			}
			if ( ! empty( $data['lump_sum_val_total'] ) ) {
				$data['lump_sum_val'] = $data['lump_sum_val_total'];
			}
		} else {
            //	RSPL TASK#16
            $data[ self::FIELD_MIN_INV ] = $data['min_inv_val_total'];
            $data[ self::FIELD_LUMP_SUM_VAL ] = $data['lump_sum_val_total'];
        }
		foreach ( $data as $key => $val ) {
			if ( isset( $rules[ $key ] ) ) {
				$clean_meta[ $key ] = call_user_func( $rules[ $key ], $val );
			}
		}

		return $clean_meta;
	}

	/**
	 * @param int $user_id
	 * @param array $data
	 * @param null|string $version timestamp
	 * @param string $key
	 *
	 * @return string|null
	 */
	public function save_user_meta( int $user_id, array $data, $version = null ) {
		$clean_meta = $this->user_data_sanitize( $data );

		$saved_data = get_user_meta( $user_id, self::USER_META_KEY, true );
		if ( empty( $saved_data ) ) {
			$saved_data = array();
		}
		if ( $version === 'new' ) {
			$version = time();
		}
		if ( isset( $saved_data[ $version ] ) ) {
			foreach ( $clean_meta as $key => $value ) {
				$saved_data[ $version ][ $key ] = $value;
			}
		} else {
            $saved_data[ $version ] = $clean_meta;
		}
		update_user_meta( $user_id, self::USER_META_KEY, $saved_data );

		return $version;
	}

	public function get_user_meta( $user_id, $version, $key = self::USER_META_KEY ) {
		$ret  = array();
		$data = get_user_meta( $user_id, $key, true );
		if ( isset( $data[ $version ] ) ) {
			$ret = $data[ $version ];
		}
		$this->user->data = (object) $ret;

		return $ret;
	}

	static public function init() {
		//add page
		$new_page_title   = 'Robo calculator';
		$new_page_content = '[robo_calculator]';

		$page_check = get_page_by_title( $new_page_title );
		$new_page   = array(
			'post_type'     => 'page',
			'post_title'    => $new_page_title,
			'post_content'  => $new_page_content,
			'post_status'   => 'publish',
			'post_author'   => 1,
			'page_template' => 'template-calculator-user-submit.php'
		);
		if ( ! isset( $page_check->ID ) ) {
			$new_page_id = wp_insert_post( $new_page );

		}
//add page
		$new_page_title   = 'Robo charges';
		$new_page_content = '[robo_charges]';

		$page_check = get_page_by_title( $new_page_title );
		$new_page   = array(
			'post_type'    => 'page',
			'post_title'   => $new_page_title,
			'post_content' => $new_page_content,
			'post_status'  => 'publish',
			'post_author'  => 1,

		);
		if ( ! isset( $page_check->ID ) ) {
			$new_page_id = wp_insert_post( $new_page );

		}
	}

	public function save_user_results( $data, $version ) {
        if ( !is_user_logged_in() ) {
            $i_user_id = $version;
        } else {
            $i_user_id = $this->user->ID;
        }
        $user_meta = get_user_meta($i_user_id, self::USER_RESULTS_META_KEY);
        $user_meta = is_array( $user_meta ) ? $user_meta : [];
		$user_meta[ $version ] = $data;
		update_user_meta( $i_user_id, self::USER_RESULTS_META_KEY, $user_meta );

		return $version;
	}

	public function validate( $data ) {
		$valid = true;
		if ( ! empty( $data[ self::FIELD_ROBO_INV_PORODUCTS ] ) && $data[ self::FIELD_ROBO_INV_PORODUCTS ] === 1 ) {
			if ( ! empty( $data['min_inv_val_total'] ) ) {
				$data[ self::FIELD_MIN_INV ] = $data['min_inv_total'];
			}
			if ( ! empty( $data['monthly_saving_val_total'] ) ) {
				$data[ self::FIELD_MONTHLY_SAVING_VAL ] = $data['monthly_saving_val_total'];
			}
			if ( ! empty( $data['lump_sum_val_total'] ) ) {
				$data[ self::FIELD_LUMP_SUM_VAL ] = $data['lump_sum_val_total'];
			}
		} else {
            //	RSPL TASK#16
            $data[ self::FIELD_MIN_INV ] = $data['min_inv_val_total'];
            $data[ self::FIELD_LUMP_SUM_VAL ] = $data['lump_sum_val_total'];
        }
		if ( empty( $data[ self::FIELD_MIN_INV ] ) && empty( $data[ self::FIELD_LUMP_SUM_VAL ] ) && empty( $data[ self::FIELD_MONTHLY_SAVING_VAL ] ) ) {
			$valid = false;
		}

		return $valid;
	}
}