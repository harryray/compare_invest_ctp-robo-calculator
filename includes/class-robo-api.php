<?php

/**
 * Created by PhpStorm.
 * Date: 21/12/2018
 * Time: 15:21
 */
Class ROBO_API {
	private $api_path, $rules, $charge_rules;
	const ROBO_DATA_PATH = "robo/";
	const GET_ROBO_PATH = "robo/get/";
	const MAX_VER_ROBO_PATH = "robo/get/version/";
	const EXIST_ROBO_PATH = "robo/exist/";
	const ADD_ROBO_PATH = "robo/add/";
	const ADD_ROBO_CHARGES_PATH = "robo/charges/add/";
	const UPDATE_ROBO_PATH = "robo/update/";
	const UPDATE_ROBO_STATUS_PATH = "robo/update/status/";
	const DELETE_ROBO_CHARGE_PATH = "robo/delete/";
	const CALCULATE_PATH = "robo/calculate/";
	public $header;
	private $values;
	private $api_auth;
	public $version_data;
	const FIELD_NAME = 'name';
	const FIELD_POST_TITLE = 'post_title';
	const FIELD_CONTENT = 'content';
	const FIELD_INV_TYPE = 'investment_type';
	const FIELD_ADVICE_TYPE = 'advice_type';
	const FIELD_MOBILE_APP = 'mobile_app';
	const FIELD_TELEPHONE_ADVICE = 'telephone_advice';
	const FIELD_ADVISORY = 'advisory';
	const FIELD_DISCRETIONARY = 'discretionary';
	const FIELD_INVESTMENT_OBJECTIVE = 'investment_objective';
	const FIELD_SUPP_GIA = 'supp_gia';
	const FIELD_SUPP_ISA = 'supp_isa';
	const FIELD_SUPP_SIPP = 'supp_sipp';
	const FIELD_SUPP_JISA = 'supp_jisa';
	const FIELD_SUPP_LISA = 'supp_lisa';
	const FIELD_SUPP_JSIPP = 'supp_jsipp';
	const FIELD_GIA_NOTES = 'gia_notes';
	const FIELD_ISA_NOTES = 'isa_notes';
	const FIELD_SIPP_NOTES = 'sipp_notes';
	const FIELD_JISA_NOTES = 'jisa_notes';
	const FIELD_LISA_NOTES = 'lisa_notes';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_WEBSITE = 'website';
	const FIELD_INFO_URL = 'info_url';
	const FIELD_NOTES = 'notes';
	const FIELD_IMG = 'img';
	const FIELD_CONTACT = 'contact';
	const FIELD_VERSION = 'version';
	const FIELD_ROBO_VERSION = 'version';
	const FIELD_REFERRAL = 'referral';
	const FIELD_CHARGES_DETAILS = 'charges_details';
	const FIELD_PRODUCT_DETAILS = 'product_details';
	const FIELD_RESEARCH_DETAILS = 'research_details';
	const FIELD_RATINGS_DETAILS = 'ratings_details';
	const FIELD_ROBO_STATUS = 'status';
	const FIELD_ROBO_PUBLISHED = 'published';
	const FIELD_ESG = 'esg';
	const FIELD_RISK_PROFILING = 'risk_profiling';
	const FEE_TYPE_CUSTODY = 1;
	const FEE_TYPE_PRODUCT_CHARGES = 2;

	//charges
	const FIELD_LUMP_SUM = 'supp_lump_sum';
	const FIELD_LUMP_SUM_MIN = 'lump_sum_min';
	const FIELD_LUMP_SUM_MAX = 'lump_sum_max';
	const FIELD_LUMP_SUM_NOTES = 'lump_sum_notes';

	const FIELD_MONTHLY_SAVING = 'supp_monthly_saving';
	const FIELD_MONTHLY_SAVING_MIN = 'monthly_saving_min_inv';
	const FIELD_MONTHLY_SAVING_MAX = 'monthly_saving_max_inv';
	const FIELD_MONTHLY_SAVING_NOTES = 'monthly_saving_notes';
	const FIELD_CHARGES = 'charges';
	const FIELD_ADMIN_CHARGES = 'admin_charges';
	const FIELD_CUSTODY_CHARGES = 'custody_charges';
	const FIELD_PORTFOLIO_TYPE_ID = 'portfolio_type_id';
	const FIELD_ACTIVE_FROM = 'active_from';
	const FIELD_ACTIVE_TO = 'active_to';
	const FIELD_MIN_INV = 'min_inv';
	const FIELD_ETHICAL_INVESTMENT = 'ethical_investment';
	const FIELD_RECOMMENDED = 'recommended';
	const PORTFOLIO_ALL = 1;
	const PORTFOLIO_FIXED_ALLOC = 2;
	const PORTFOLIO_FULLY_MANAGED = 3;
	const PORTFOLIO_ETHICAL_INVESTMENT = 4;


	const PORTFOLIO_TYPES = [
		self::PORTFOLIO_ALL                => 'All',
		self::PORTFOLIO_FIXED_ALLOC        => 'Fixed allocation',
		self::PORTFOLIO_FULLY_MANAGED      => 'Fully managed',
		self::PORTFOLIO_ETHICAL_INVESTMENT => 'Ethical investment',

	];


	public function get_rules() {
		$this->rules = [
			self::FIELD_NAME             => 'sanitize_text_field',
			self::FIELD_POST_TITLE       => 'sanitize_text_field',
			self::FIELD_CONTENT          => 'sanitize_text_field',
			self::FIELD_WEBSITE          => 'sanitize_text_field',
			self::FIELD_INFO_URL         => 'sanitize_text_field',
			self::FIELD_ISA_NOTES        => 'sanitize_text_field',
			self::FIELD_GIA_NOTES        => 'sanitize_text_field',
			self::FIELD_JISA_NOTES       => 'sanitize_text_field',
			self::FIELD_LISA_NOTES       => 'sanitize_text_field',
			self::FIELD_SIPP_NOTES       => 'sanitize_text_field',
			self::FIELD_CHARGES_DETAILS  => 'sanitize_text_field',
			self::FIELD_RATINGS_DETAILS  => 'sanitize_text_field',
			self::FIELD_RESEARCH_DETAILS => 'sanitize_text_field',
			self::FIELD_PRODUCT_DETAILS  => 'sanitize_text_field',
			self::FIELD_REFERRAL         => 'sanitize_text_field',
			self::FIELD_RECOMMENDED      => 'intval',
			self::FIELD_ROBO_VERSION     => 'intval',
			self::FIELD_ROBO_STATUS      => 'intval',
			self::FIELD_ROBO_PUBLISHED   => 'intval',

		];

		return $this->rules;
	}

	public function get_charges_rules() {
		$this->charge_rules = [
			self::FIELD_SUPP_ISA             => 'intval',
			self::FIELD_SUPP_GIA             => 'intval',
			self::FIELD_SUPP_SIPP            => 'intval',
			self::FIELD_SUPP_JISA            => 'intval',
			self::FIELD_SUPP_LISA            => 'intval',
			self::FIELD_SUPP_JSIPP           => 'intval',
			self::FIELD_LUMP_SUM_MIN         => 'robo_sanitize_number',
			self::FIELD_LUMP_SUM_MAX         => 'robo_sanitize_number',
			self::FIELD_LUMP_SUM             => 'intval',
			self::FIELD_LUMP_SUM_NOTES       => 'sanitize_text_field',
			self::FIELD_MONTHLY_SAVING_NOTES => 'sanitize_text_field',
			self::FIELD_MONTHLY_SAVING       => 'intval',
			self::FIELD_MONTHLY_SAVING_MIN   => 'robo_sanitize_number',
			self::FIELD_MONTHLY_SAVING_MAX   => 'robo_sanitize_number',
			self::FIELD_CHARGES              => 'sanitize_fee',
			self::FIELD_ADMIN_CHARGES        => 'sanitize_fee',
			self::FIELD_CUSTODY_CHARGES      => 'sanitize_fee',
			self::FIELD_MIN_INV              => 'sanitize_fee',
			self::FIELD_ACTIVE_FROM          => 'sanitize_text_field',
			self::FIELD_ACTIVE_TO            => 'sanitize_text_field',
			self::FIELD_TELEPHONE_ADVICE     => 'intval',
			self::FIELD_ADVICE_TYPE          => 'intval',
			self::FIELD_INV_TYPE             => 'intval',
			self::FIELD_INVESTMENT_OBJECTIVE => 'intval',
			self::FIELD_MOBILE_APP           => 'intval',
			self::FIELD_ADVISORY             => 'intval',
			self::FIELD_DISCRETIONARY        => 'intval',
			self::FIELD_ETHICAL_INVESTMENT   => 'intval',
			self::FIELD_ESG                  => 'intval',
			self::FIELD_RISK_PROFILING       => 'intval',


		];

		return $this->charge_rules;
	}


	public function __construct( $header = null, $values = null ) {
		if ( $values ) {
			$this->values = $values;
		}
		if ( ! defined( 'ENV' ) ) {
			//$this->api_path = "https://706gkfjg93.execute-api.eu-west-2.amazonaws.com/prod/";
			$this->api_path = "https://uerma4ny9l.execute-api.eu-west-2.amazonaws.com/prod/";
			//$this->api_path = "http://localhost:3002/";
		} else {

			switch ( ENV ) {
				case 'dev':
					$this->api_path = "https://b2luinijs1.execute-api.eu-west-2.amazonaws.com/dev/";
					break;
				case 'local':
					$this->api_path = "http://localhost:3002/";
					break;
				case 'staging':
					//$this->api_path = "https://b2luinijs1.execute-api.eu-west-2.amazonaws.com/dev/";
					$this->api_path = "https://uerma4ny9l.execute-api.eu-west-2.amazonaws.com/prod/";
					break;
				case 'prod':
					$this->api_path = "https://uerma4ny9l.execute-api.eu-west-2.amazonaws.com/prod/";
					break;
			}
		}
		$this->header   = null;
		$this->api_auth = 'M2thbW1ldXAtd2hscnFxcmctdXh2bmgyeXkteGg5ZmVqNHU6NGFiMDdjNTAxMjVmNTMxMDJhNDc2ZWNmMmM5ZDc1Njg=';
	}

	public function get_values() {
		return $this->values;
	}

	public function set_values( $values ) {
		$this->values = $values;
	}


	public function robo_api_curl( $url, $post_data = null, $post = true ) {
		$ch = curl_init();
		global $logger;
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_URL, $url );
		if ( $post ) {
			// post_data
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data );
		}
		if ( ! is_null( $this->header ) ) {
			curl_setopt( $ch, CURLOPT_HEADER, true );
		}
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		$auth = $this->get_robo_auth();
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization:Basic ' . $auth
		) );
		curl_setopt( $ch, CURLOPT_VERBOSE, true );
		$response = curl_exec( $ch );


		$body = null;
		// error
		if ( ! $response ) {
			$body = curl_error( $ch );
			$logger->addError( $body );
		} else {
			//parsing http status code

			if ( ! is_null( $this->header ) ) {
				$header_size = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );
				$header      = substr( $response, 0, $header_size );
				$body        = substr( $response, $header_size );
			} else {
				$body = $response;
			}
		}
		curl_close( $ch );

		return json_decode( $body, true );
	}

	public function get_api_path() {
		return $this->api_path;
	}

	public function get_robo_auth() {
		return $this->api_auth;
	}

	public function get_robo_data( $post_id, $version_id = null, $from_db = false ) {
		$response = false;
		if ( $this->robo_data_exist( $post_id ) ) {
			$key = $post_id . 'robo';
			if ( $version_id ) {
				$key .= $version_id;
			}
			if ( ! $from_db ) {
				$cache    = get_transient( $key );
				$response = $cache['data'];
                /* RSPL #16: Fix version -1 data not display issue Start */
                if(empty($cache['version'])) {
                    /** @var $logger Monolog\Logger */
                    global $logger;
                    $url = $this->get_api_path() . self::GET_ROBO_PATH . $post_id;
                    if ( $version_id !== null ) {
                        $url .= '/' . $version_id;
                    }
                    $ret = $this->robo_api_curl( $url, null, false );
                    if ( $ret['msg'] === 'success' ) {
                        set_transient( $key, $ret );
                        return $ret['data'];
                    } else {
                      if($logger !== null) {
                        $logger->addError( "Robo api error ", [ $response ] );
                      }
                    }

                }
                /* RSPL #16: Fix version -1 data not display issue End */
			}

			if ( ! $response && ! isset( $cache['data'] ) ) {
				/** @var $logger Monolog\Logger */
				global $logger;
				$url = $this->get_api_path() . self::GET_ROBO_PATH . $post_id;
				if ( $version_id !== null ) {
					$url .= '/' . $version_id;
				}
				$ret = $this->robo_api_curl( $url, null, false );
				if ( $ret['msg'] === 'success' ) {
					set_transient( $key, $ret );

					return $ret['data'];
				} else {
          if($logger !== null) {
					 $logger->addError( "Robo api error ", [ $response ] );
          }
				}
			}
		}

		return $response;
	}

	public function get_max_version( $post_id ) {
		global $logger;
		$version = 0;
		$url     = $this->get_api_path() . self::MAX_VER_ROBO_PATH . $post_id;

		$response = $this->robo_api_curl( $url, null, false );
		if ( isset( $response['max'] ) ) {
			$version = $response['max'];
		} else {
			$logger->addError( "Robo api error ", [ $response ] );
		}

		return $version;
	}


	public function get_robo_charges( $post_id, $version ) {

		$url      = $this->get_api_path() . self::GET_ROBO_PATH . $post_id . '/' . $version;
		$response = $this->robo_api_curl( $url, null, false );

		return $response;
	}

	public function update_robo_status( $post_id, $version, $status ) {
		$user     = wp_get_current_user();
		$data     = [ 'user_id' => $user->ID ];
		$url      = $this->get_api_path() . self::UPDATE_ROBO_STATUS_PATH . $post_id . '/' . $version . '/' . $status;
		$response = $this->robo_api_curl( $url, json_encode( $data ) );
		delete_transient( $post_id . 'robo' . $version );
		delete_transient( $post_id . 'robo' );

		return $response;
	}

	public function delete_robo_charge( $post_id, $version ) {
		$user     = wp_get_current_user();
		$data     = [ 'user_id' => $user->ID ];
		$url      = $this->get_api_path() . self::DELETE_ROBO_CHARGE_PATH . $post_id . '/' . $version;
		$response = $this->robo_api_curl( $url, json_encode( $data ) );
		delete_transient( $post_id . 'robo' );
		delete_transient( $post_id . 'robo' . $version );

		return $response;
	}

	public function robo_data_exist( $post_id ) {
		$response_cache = get_transient( $post_id . 'robo_exist' );
		$response       = isset( $response_cache['exist'] ) ? $response_cache['exist'] : false;
		if ( ! isset( $response_cache['exist'] ) ) {
			$url      = $this->get_api_path() . self::EXIST_ROBO_PATH . $post_id;
			$response = $this->robo_api_curl( $url, null, false );
			set_transient( $post_id . 'robo_exist', [ 'exist' => $response ] );
		}

		return $response;
	}

	public function save_robo_data( $data ) {
		$url      = $this->get_api_path() . self::ADD_ROBO_PATH;
		$response = $this->robo_api_curl( $url, json_encode( $data ) );
		delete_transient( $data['robo_id'] . 'robo_exist' );

		return $response;
	}


	public function get_queue( $data, $version, $user_id ) {
		$url           = $this->get_api_path() . self::CALCULATE_PATH;
		$data->version = $version;
		$data->user_id = $user_id;
		$response      = $this->robo_api_curl( $url, json_encode( $data ) );
		$robo          = new Robo_Calculator();
		if ( $response['msg'] == 'success' ) {
			$robo->save_user_results( $response['robos'], $version );

			return $response['robos'];
		} else {
			//save the response
			$robo->save_user_results( $response, $version );
		}
		
		return false;
	}

	public function save_robo_charges( $data, $post_id, $version ) {

		$url      = $this->get_api_path() . self::ADD_ROBO_CHARGES_PATH . $post_id . '/' . $version;
		$response = $this->robo_api_curl( $url, json_encode( $data ) );
		delete_transient( $post_id . 'robo' );

		return $response;
	}

	public function update_robo_data( $data, $post_id ) {
		$version  = isset( $data['version'] ) ? $data['version'] : '';
		$url      = $this->get_api_path() . self::UPDATE_ROBO_PATH . $post_id . '/' . $version;
		$response = $this->robo_api_curl( $url, json_encode( $data ) );
		delete_transient( $post_id . 'robo' );

		return $response;
	}


	public function sanitize_data( $data ) {
		global $post;
		$rules                                        = $this->get_rules();
		$clean_data                                   = $this->sanitize( $data, $rules );
		$clean_data['user_id']                        = wp_get_current_user()->ID;
		$clean_data['robo_id']                        = $post->ID;
		$clean_data['status']                         = 1;
		$clean_data['name']                           = esc_sql( $post->post_title );
		$clean_data['description']                    = esc_sql( $post->post_content );
		$clean_data[ ROBO_API::FIELD_INFO_URL ]       = urlencode( get_permalink( $post->ID ) );
		$clean_data[ ROBO_API::FIELD_ROBO_PUBLISHED ] = ( $post->post_status === 'publish' ) ? 1 : 0;
		$clean_data['img']                            = get_the_post_thumbnail_url( $data['post_ID'] );


		return $clean_data;
	}

	public function sanitize( $data, $rules ) {
		$clean_data = [];
		foreach ( $rules as $field => $callback ) {
			if ( isset( $data[ $field ] ) ) {

				$clean_data[ $field ] = call_user_func( $callback, $data[ $field ] );
				if ( $field == self::FIELD_POST_TITLE ) {
					$clean_data[ self::FIELD_NAME ] = $clean_data[ $field ];
				}
				if ( $field == self::FIELD_CONTENT ) {
					$clean_data[ self::FIELD_DESCRIPTION ] = $clean_data[ $field ];
				}

			}
		}

		return $clean_data;
	}
}
