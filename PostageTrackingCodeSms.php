<?php

namespace PostageTrackingCodeSms;

require_once 'vendor/autoload.php';

use PostageTrackingCodeSms\classes\OptionPanel;

/**
 * @package PostageTrackingCodeSms
 * @version 1.0.1
 */

/*
Plugin Name: Postage Tracking Code Sms
Plugin URI: http://mohammadmalekirad.ir/PostageTrackingCodeSms
Description: این افزونه ارسال کد مرسوله های پستی سفارشات ووکامرس را راحت تر می کند
Author: Mohammad MalekiRad
Version: 1.0.1
Author URI: http://mohammadmalekirad.ir/
Requires at least: 5.2
Requires PHP: 7.0
License URI: https://opensource.org/licenses/MIT
Code Name: PostageTrackingCodeSms
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'POSTAGE_TRACKING_CODE_SMS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'POSTAGE_TRACKING_CODE_SMS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'POSTAGE_TRACKING_CODE_SMS_PLUGIN_FILE', __FILE__ );
define( 'POSTAGE_TRACKING_CODE_SMS_PLUGIN_ICON', plugins_url( "images/ic.png", __FILE__ ) );

$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

if ( in_array( 'woocommerce/woocommerce.php', $active_plugins ) && in_array( 'persian-woocommerce-sms/WoocommerceIR_SMS.php', $active_plugins ) ) {
	OptionPanel::getInstance();
}