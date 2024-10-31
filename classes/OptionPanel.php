<?php

namespace PostageTrackingCodeSms\classes;

class OptionPanel extends ViSingleton {

	private const POSTAGE_TRACKING_CODE_SMS_OPTIONS = "postage_tracking_code_sms_options";
	private const POSTAGE_TRACKING_CODE_SMS_ORDER_META = "postage_tracking_code_order_meta";

	private const DEFAULT_MESSAGE_TEXT = "جناب آقا/خانم {orderfullname} سفارش شما با شماره سغارش {oredernumber} تحویل پست داده شد.\nشناسه مرسوله  : {trackingcode}				\nاطلاعات تکمیلی: https://tracking.post.ir/search.aspx?id={trackingcode}";

	function init() {
		add_action( 'wp_ajax_postage_tracking_code_sms_get_order_details', [ $this, 'getOrderDetails' ] );
		add_action( 'wp_ajax_postage_tracking_code_sms_send_sms', [ $this, 'sendSmsAndSaveMeta' ] );
		add_action( 'wp_ajax_postage_tracking_code_sms_latest_list', [ $this, 'latestMessages' ] );

		add_action( 'admin_head', [ $this, 'my_custom_favicon' ] );
		add_action( 'admin_menu', [ $this, 'registerOptionPanel' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'loadCssAndJs' ] );
		SmsMetaBox::getInstance();
	}

	function getOrderDetails() {
		$order_number = sanitize_text_field( $_POST['order_number'] );
		$data         = [];
		if ( $order_number = intval( $order_number ) ) {
			$order = wc_get_order( $order_number );
			if ( ! $order ) {
				return;
			}
			$order_data = $order->get_data(); // The Order data
			$data       = [
				'order_number' => $order_data['number'],
				'first_name'   => $order_data['billing']['first_name'],
				'last_name'    => $order_data['billing']['last_name'],
				'phone_number' => $order_data['billing']['phone']
			];
		}
		echo json_encode( $data );
		// this is required to terminate immediately and return a proper response
		wp_die();
	}

	function sendSmsAndSaveMeta() {
		$order_number  = sanitize_text_field( $_POST['order_number'] );
		$phone_number  = sanitize_text_field( $_POST['phone_number'] );
		$tracking_code = sanitize_text_field( $_POST['tracking_code'] );
		$full_name     = sanitize_text_field( $_POST['full_name'] );

		$data = [];
		if ( intval( $order_number ) && ( intval( $phone_number ) ) ) {
			$message = $this->getResultTextMessage( [ $order_number, $full_name, $tracking_code ] );
			$data    = array(
				'type'    => 2,
				'mobile'  => $phone_number,
				'message' => $message
			);

			$result             = PWooSMS()->SendSMS( $data );
			$data['is_success'] = $result;
			if ( $result ) {
				$this->saveOrderMeta( $order_number, $message );
			}
		}
		echo json_encode( $data );

		// this is required to terminate immediately and return a proper response
		wp_die();
	}

	function my_custom_favicon() {
		echo '
    <style>
    .dashicons-woo-tros-ic {
        background-image: url("' . POSTAGE_TRACKING_CODE_SMS_PLUGIN_ICON . '");
        background-repeat: no-repeat;
        background-position: center; 
    }
    </style>';
	}

	public function latestMessages() {
		echo json_encode( $this->get_meta_values( self::POSTAGE_TRACKING_CODE_SMS_ORDER_META ) );
		wp_die();
	}

	function registerOptionPanel() {

		add_menu_page(
			'پیامک کد مرسوله پستی ووکامرس',
			'پیامک کد مرسوله پستی ووکامرس',
			'read',
			'postage-tracking-code-sms',
			[ $this, 'optionsPanelCallback' ],
			'dashicons-woo-tros-ic'
		);

		add_submenu_page(
			'postage-tracking-code-sms',
			'تنظیمات',
			'تنظیمات',
			'activate_plugins',
			'postage-tracking-code-sms-opt',
			[ $this, 'options' ] );
	}

	function optionsPanelCallback() {

		ViewRenderHelper::renderView( 'option-panel', [] );
	}

	function options() {
		if ( isset( $_POST['textMessage'] ) ) {
			update_option( self::POSTAGE_TRACKING_CODE_SMS_OPTIONS, [
				'text_message' => sanitize_textarea_field( $_POST['textMessage'] )
			] );
		}
		ViewRenderHelper::renderView( 'settings', [ 'text' => $this->getMessageText() ] );
	}

	function loadCssAndJs() {

		wp_enqueue_script(
			"postage-tracking-code-sms-bootstrap",
			POSTAGE_TRACKING_CODE_SMS_PLUGIN_URL . "assets/bootstrap.bundle.min.js" );

		wp_enqueue_script(
			"postage-tracking-code-sms-script",
			POSTAGE_TRACKING_CODE_SMS_PLUGIN_URL . "assets/script.js",
			array( 'jquery' ) );

		wp_enqueue_style(
			"postage-tracking-code-sms-bootstrap",
			POSTAGE_TRACKING_CODE_SMS_PLUGIN_URL . "assets/bootstrap.rtl.min.css" );

		wp_enqueue_style(
			"postage-tracking-code-sms-bootstrap",
			POSTAGE_TRACKING_CODE_SMS_PLUGIN_URL . "assets/style.css" );
	}

	public static function getOptions( $key = null ) {
		$opt = get_option( self::POSTAGE_TRACKING_CODE_SMS_OPTIONS );
		if ( ! is_null( $key ) && isset( $opt[ $key ] ) ) {
			return $opt[ $key ];
		}

		return $opt ?? false;
	}

	public function getResultTextMessage( $data ) {
		return str_replace( [ '{oredernumber}', '{orderfullname}', '{trackingcode}' ], $data, $this->getMessageText() );
	}

	private function getMessageText() {
		return self::getOptions( "text_message" ) ?: self::DEFAULT_MESSAGE_TEXT;
	}

	private function saveOrderMeta( $order_id, $data ) {
		add_post_meta( $order_id, 'postage_tracking_code_order_meta', $data );
	}

	public static function getOrderMeta( $order_id ) {
		return get_post_meta( $order_id, self::POSTAGE_TRACKING_CODE_SMS_ORDER_META ) ?? [];
	}

	function get_meta_values( $key ) {
		global $wpdb;

		return $wpdb->get_col( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} pmLEFT JOIN {$wpdb->posts} p ON p.ID = post_id WHERE meta_key = %s", $key ) ) ?? [];
	}

}