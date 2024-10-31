<?php

namespace PostageTrackingCodeSms\classes;

abstract class ViMetaBoxes extends ViSingleton {

	public function init() {
		add_action( "admin_init", [ $this, 'initMetaBox' ] );
	}

	abstract function initMetaBox();

	abstract function callbackMetaBox();

	function createMetaBox( $id, $title, $screen = 'post', $context = 'side', $priority = 'low', $callback_args = null ): bool {
		add_meta_box(
			$id,
			__( $title ),
			[ $this, 'callbackMetaBox' ],
			$screen,
			$context,
			$priority,
			$callback_args );

		return true;
	}
}