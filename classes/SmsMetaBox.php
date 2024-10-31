<?php

namespace PostageTrackingCodeSms\classes;

class SmsMetaBox extends ViMetaBoxes {

	function initMetaBox(): bool {
		return $this->createMetaBox(
			"postage-tracking-code-sms",
			" پیامک پستی سفارشات ووکامرس",
			'shop_order' );
	}

	function callbackMetaBox() {
		ViewRenderHelper::renderView( 'metabox', OptionPanel::getOrderMeta( get_the_ID() ) );
	}
}
