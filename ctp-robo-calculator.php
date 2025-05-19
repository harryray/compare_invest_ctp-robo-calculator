<?php
/**
 * @author    poojaramani
 * @link      http://comparetheplatform.com/
 * Plugin Name: CTP robo calculator
 * Description: Ctp Robo advice calculator
 * Version:     1.0.0
 * Text Domain: cplat
 */
if ( ! defined( 'CTP_ROBO_PLUGIN_DIR' ) ) {
	define( 'CTP_ROBO_PLUGIN_DIR', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
}
if ( ! defined( 'CTP_ROBO_PLUGIN_DIR_URL' ) ) {
	define( 'CTP_ROBO_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'CTP_ROBO_PLUGIN_PATH' ) ) {
	define( 'CTP_ROBO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
include_once CTP_ROBO_PLUGIN_DIR . 'includes/class-ctp-robo.php';
include_once CTP_ROBO_PLUGIN_DIR . 'includes/class-ctp-robo-admin.php';
include_once CTP_ROBO_PLUGIN_DIR . 'includes/class-robo-api.php';
include_once CTP_ROBO_PLUGIN_DIR . 'includes/post-type.php';
include_once CTP_ROBO_PLUGIN_DIR . 'includes/ajax.php';
include_once CTP_ROBO_PLUGIN_DIR . 'templates/robo-calculator.php';

include_once CTP_ROBO_PLUGIN_DIR . 'functions.php';
register_activation_hook( __FILE__, array( 'Robo_Calculator', 'init' ) );