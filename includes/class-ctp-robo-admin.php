<?php

/**
 * CMB2 Theme Options
 * @version 0.1.0
 */
class Robo_Admin {
	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private $key = 'robo_options';
	/**
	 * Options page metabox id
	 * @var string
	 */
	private $metabox_id = 'robo_questions_metabox';
	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = 'Robo calculator settings';
	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Holds an instance of the object
	 *
	 * @var CTP_Extra_Settings_Admin
	 **/
	private static $instance = null;

	/**
	 * Constructor
	 * @since 0.1.0
	 */
	private function __construct() {
		// Set our title
		$this->title = __( 'Robo questions settings', 'robo' );
	}

	/**
	 * Returns the running object
	 *
	 * @return CTP_Extra_Settings_Admin
	 **/
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Robo_Admin();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	/**
	 * Initiate our hooks
	 * @since 0.1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_questions_settings_metabox' ) );
	}

	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 * @since 0.1.0
	 */
	public function add_options_page() {
		//$this->options_page = add_submenu_page( 'edit.php', $this->title, $this->title, 'manage_options', $this->key, array( $this, 'posts_settings_page_display' ) );
		$this->options_page = add_menu_page( $this->title, $this->title, 'manage_options', $this->key, array(
			$this,
			'questions_settings_page_display'
		), 'dashicons-list-view' );
		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  0.1.0
	 */
	public function questions_settings_page_display() {
		?>
        <div class="wrap cmb2-options-page <?php echo $this->key; ?>">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
        </div>
		<?php
	}

	/**
	 * Add the options metabox to the array of metaboxes
	 * @since  0.1.0
	 */
	function add_questions_settings_metabox() {
		// hook in our save notices
		add_action( "cmb2_save_options-page_fields_{$this->metabox_id}", array( $this, 'settings_notices' ), 10, 2 );
		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->key, )
			),
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Help text
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'            => 'Introduction Text',
			'desc'            => '',
			'id'              => 'intro_text',
			'type'            => 'textarea',
			'default'         => '<div class="row">  <div class="col-lg-12">
        <p>Congratulations! you have landed here because you have decided to get serious about saving, but with over 60
            investment providers we will be able to help you choose which one would be best for you.</p>

        <p>We have independently researched these providers and know what they can do for you as well as how much they
            really
            cost.</p>

        <p>Some are easy to use and helpful, but they may come with high charges. Others are cheap but might lack
            something you
            might need later on. We’ll ask you a few simple questions to help you find the best robo-adviser for you.
            You have decided to save but if you have any short-term debts such as credit cards, then its usually best to
            pay
            these off first as the interest rates on these can be high.</p></div></div>',
			'sanitization_cb' => 'robo_html_sanitize'
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	Error text
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'            => 'Result error Text',
			'desc'            => '',
			'id'              => 'result_error_text',
			'type'            => 'textarea',
			'default'         => '<div class="row">  <div class="col-lg-12">
        <p>Oops something went wrong. Can you please try again <a href="digital-investing-app-comparison-tool/invest">here</a></p></div></div>',
			'sanitization_cb' => 'robo_html_sanitize'
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	FormError text
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'            => 'Form error Text',
			'desc'            => '',
			'id'              => 'form_error_text',
			'type'            => 'textarea',
			'default'         => '<div class="row">  <div class="col-lg-12">
        <p>Can you please enter mandatory fields </p></div></div>',
			'sanitization_cb' => 'robo_html_sanitize'
		) );


		/*-----------------------------------------------------------------------------------*/
		/*	Question 1
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 1 label',
			'desc'    => '',
			'id'      => 'question_1_label',
			'type'    => 'textarea_small',
			'default' => 'Would you be comfortable making your own investment choices and taking responsibility for them?'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 1 help',
			'default' => 'Would you be comfortable making your own investment choices and taking responsibility for them?',
			'id'      => 'question_1_help',
			'type'    => 'textarea_small'
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	Question 1a
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'            => 'Question 1a label',
			'desc'            => '',
			'id'              => 'question_1a_label',
			'type'            => 'textarea_small',
			'default'         => 'As well as robo-advisers, you can also look at DIY platforms where you can pick your own funds. Our <a href="platform-calculator">platform calculator</a> will help you analyse DIY platforms based on price and service. You can also review platforms here. Would you like to continue with the robo calculator?',
			'sanitization_cb' => 'robo_html_sanitize'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 1a help',
			'default' => '',
			'id'      => 'question_1a_help',
			'type'    => 'textarea_small'
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Question 2
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'            => 'Question 2 label',
			'desc'            => '',
			'id'              => 'question_2_label',
			'type'            => 'textarea_small',
			'default'         => 'Do you want somebody to monitor your investment and make adjustments to ensure it performs as you would expect?',
			'sanitization_cb' => 'robo_html_sanitize'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 2 help',
			'desc'    => '',
			'id'      => 'question_2_help',
			'type'    => 'textarea_small',
			'default' => 'Do you want somebody to monitor your investment and make adjustments to ensure it performs as you would expect?'
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	Question 2
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'            => 'Question 2a label',
			'desc'            => '',
			'id'              => 'question_2a_label',
			'type'            => 'textarea_small',
			'default'         => 'Would you prefer someone to manage investments with you or for you? ‘With you’ means a financial adviser whereas ‘for you’ means that you hand over all responsibility to someone who manages your portfolio based on your risk profile and investment objectives. that includes some robo-advisers.',
			'sanitization_cb' => 'robo_html_sanitize'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 2a help',
			'desc'    => '',
			'id'      => 'question_2a_help',
			'type'    => 'textarea_small',
			'default' => 'Would you prefer someone to manage investments with you or for you? ‘With you’ means a financial adviser whereas ‘for you’ means that you hand over all responsibility to someone who manages your portfolio based on your risk profile and investment objectives. that includes some robo-advisers.'
		) );
		$cmb->add_field( array(
			'name'            => 'Question 2a text',
			'desc'            => '',
			'id'              => 'question_2a_text',
			'type'            => 'textarea_small',
			'default'         => 'Very few robos provide advice. Most operate on a ‘Do it for me’ basis. Click here for helping finding an adviser.',
			'sanitization_cb' => 'robo_html_sanitize'
		) );
		$cmb->add_field( array(
			'name'            => 'Calculator text',
			'desc'            => '',
			'id'              => 'calculator_text',
			'type'            => 'textarea',
			'default'         => '<div class="row">  <div class="col-lg-12">
        <span>A robo might not be what you’re looking for. If you want DIY investment platform go here.<strong><a href="platform-calculator">Platform Calculator</a></strong>
</span></div></div>',
			'sanitization_cb' => 'robo_html_sanitize'
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Question 4
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 3 label',
			'desc'    => '',
			'id'      => 'question_3_label',
			'type'    => 'textarea_small',
			'default' => 'Would you like to find a robo that provides solutions for specific investment objectives? Eg saving for retirement, school fees etc.'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 3 help',
			'desc'    => '',
			'id'      => 'question_3_help',
			'type'    => 'textarea_small',
			'default' => 'Would you like to find a robo that provides solutions for specific investment objectives? Eg saving for retirement, school fees etc.'
		) );


		/*-----------------------------------------------------------------------------------*/
		/*	Question 5
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 3a label',
			'desc'    => '',
			'id'      => 'question_3a_label',
			'type'    => 'textarea_small',
			'default' => 'Do you want to save into a pension? '
		) );
		$cmb->add_field( array(
			'name'    => 'Question 3a help',
			'desc'    => '',
			'id'      => 'question_3a_help',
			'type'    => 'textarea_small',
			'default' => 'Do you want to save into a pension? '
		) );


		/*-----------------------------------------------------------------------------------*/
		/*	Question 6
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 3b label',
			'desc'    => '',
			'id'      => 'question_3b_label',
			'type'    => 'textarea_small',
			'default' => ' Are you investing for your children? '
		) );
		$cmb->add_field( array(
			'name'    => 'Question 3b help',
			'desc'    => '',
			'id'      => 'question_3b_help',
			'type'    => 'textarea_small',
			'default' => ' Are you investing for your children? '
		) );


		/*-----------------------------------------------------------------------------------*/
		/*	Question 6a
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 3b(a) label',
			'desc'    => '',
			'id'      => 'question_3ba_label',
			'type'    => 'textarea_small',
			'default' => 'Are you looking for a junior ISA or a junior pensions?'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 3b(a) help',
			'desc'    => '',
			'id'      => 'question_3b(a)_help',
			'type'    => 'textarea_small',
			'default' => 'Are you looking for a junior ISA or a junior pensions?'
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	Question 7
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 4 label',
			'desc'    => '',
			'id'      => 'question_4_label',
			'type'    => 'textarea_small',
			'default' => ' Do you know how much you would like to hold in different investment products such as ISAs and SIPPs? Don’t worry if you don’t, we can make some assumptions for you. '
		) );
		$cmb->add_field( array(
			'name'    => 'Question 4 help',
			'desc'    => '',
			'id'      => 'question_4_help',
			'type'    => 'textarea_small',
			'default' => ' Do you know how much you would like to hold in different investment products such as ISAs and SIPPs? Don’t worry if you don’t, we can make some assumptions for you. '
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Question 8
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 5 label',
			'desc'    => '',
			'id'      => 'question_5_label',
			'type'    => 'textarea_small',
			'default' => 'Do you want to make a lump-sum investment?'
		) );
		$cmb->add_field( array(
			'name' => 'Question 5 help',
			'desc' => '',
			'id'   => 'question_5_help',
			'type' => 'textarea_small'
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	Question 8
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 5 a label',
			'desc'    => '',
			'id'      => 'question_5a_label',
			'type'    => 'textarea_small',
			'default' => 'Do you want to make a lump-sum investment?'
		) );
		// $cmb->add_field( array(
		//     'name'    => 'Question 10 help',
		//     'desc'    => '',
		//     'id'      => 'question_10_help',
		//     'type'    => 'textarea_small'
		// ) );

		/*-----------------------------------------------------------------------------------*/
		/*	Question 8
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 5 b label',
			'desc'    => '',
			'id'      => 'question_5b_label',
			'type'    => 'textarea_small',
			'default' => 'Do you want a monthly savings plan? '
		) );
		// $cmb->add_field( array(
		//     'name'    => 'Question 10 help',
		//     'desc'    => '',
		//     'id'      => 'question_10_help',
		//     'type'    => 'textarea_small'
		// ) );

		/*-----------------------------------------------------------------------------------*/
		/*	Qestion 8
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 5 c label',
			'desc'    => '',
			'id'      => 'question_5c_label',
			'type'    => 'textarea_small',
			'default' => 'How much do you want to invest each month?  '
		) );
		$cmb->add_field( array(
			'name'    => 'Question 5 c help',
			'desc'    => '',
			'id'      => 'question_5c_help',
			'type'    => 'textarea_small',
			'default' => 'How much do you want to invest each month?  '
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Qestion 9
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 6 label',
			'desc'    => '',
			'id'      => 'question_6_label',
			'type'    => 'textarea_small',
			'default' => 'On average how often do you expect to top up and by how much? '
		) );
		$cmb->add_field( array(
			'name'    => 'Question 6 help',
			'desc'    => '',
			'id'      => 'question_6_help',
			'type'    => 'textarea_small',
			'default' => 'On average how often do you expect to top up and by how much? '
		) );

		/*-----------------------------------------------------------------------------------*/
		/*	Qestion 10
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 7 label',
			'desc'    => '',
			'id'      => 'question_7_label',
			'type'    => 'textarea_small',
			'default' => ' Do you want a provider that has a mobile app? '
		) );
		$cmb->add_field( array(
			'name'    => 'Question 7 help',
			'desc'    => '',
			'id'      => 'question_7_help',
			'type'    => 'textarea_small',
			'default' => ' Do you want a provider that has a mobile app? '
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	Qestion 11
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 8 label',
			'desc'    => '',
			'id'      => 'question_8_label',
			'type'    => 'textarea_small',
			'default' => 'Are you looking for ethical investments?  '
		) );
		$cmb->add_field( array(
			'name'    => 'Question 8 help',
			'desc'    => '',
			'id'      => 'question_8_help',
			'type'    => 'textarea_small',
			'default' => 'Are you looking for ethical investments?  '
		) );
		/*-----------------------------------------------------------------------------------*/
		/*	Qestion 12
		/*-----------------------------------------------------------------------------------*/
		$cmb->add_field( array(
			'name'    => 'Question 9 label',
			'desc'    => '',
			'id'      => 'question_9_label',
			'type'    => 'textarea_small',
			'default' => 'Do you want the option of telephone advice?'
		) );
		$cmb->add_field( array(
			'name'    => 'Question 9 help',
			'desc'    => '',
			'id'      => 'question_9_help',
			'type'    => 'textarea_small',
			'default' => 'Do you want the option of telephone advice?'
		) );


	}

	/**
	 * Register settings notices for display
	 *
	 * @param int $object_id Option key
	 * @param array $updated Array of updated fields
	 *
	 * @return void
	 * @since  0.1.0
	 *
	 */
	public function settings_notices( $object_id, $updated ) {
		if ( $object_id !== $this->key || empty( $updated ) ) {
			return;
		}
		add_settings_error( $this->key . '-notices', '', __( 'Settings updated.', 'robo' ), 'updated' );
		settings_errors( $this->key . '-notices' );
	}

	/**
	 * Public getter method for retrieving protected/private variables
	 *
	 * @param string $field Field to retrieve
	 *
	 * @return mixed          Field value or exception is thrown
	 * @since  0.1.0
	 *
	 */
	public function __get( $field ) {
		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'metabox_id', 'title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		throw new Exception( 'Invalid property: ' . $field );
	}
}

/**
 * Helper function to get/return the CTP_Extra_Settings_Admin object
 * @return CTP_Extra_Settings_Admin object
 * @since  0.1.0
 */
function robo_admin() {
	return Robo_Admin::get_instance();
}

/**
 * Wrapper function around cmb2_get_option
 *
 * @param string $key Options array key
 *
 * @return mixed        Option value
 * @since  0.1.0
 *
 */
function ctp_robo_get_questions_option( $key = '' ) {
	return cmb2_get_option( robo_admin()->key, $key );
}

// Get it started
robo_admin();
