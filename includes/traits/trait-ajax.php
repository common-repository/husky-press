<?php

namespace HuskyPress\Traits;

defined( 'ABSPATH' ) || exit;

trait Ajax
{
	protected function ajax( $tag, $function_to_add, $priority = 10 )
    {
		\add_action( 'wp_ajax_husky_' . $tag, [ $this, $function_to_add ], $priority );
	}

	/**
	 * Verify request nonce
	 *
	 * @param string $action The nonce action name.
	 */
	public function verify_nonce( $action ) {
		if ( ! isset( $_REQUEST['security'] ) || ! \wp_verify_nonce( $_REQUEST['security'], $action ) ) {
			$this->error(__('Error: Nonce verification failed', 'husky-press'));
		}
	}

	/**
	 * Wrapper function for sending success response
	 *
	 * @param mixed $data Data to send to response.
	 */
	public function success( $data = null ) {
		$this->send( $data );
	}

	/**
	 * Wrapper function for sending error
	 *
	 * @param mixed $data Data to send to response.
	 */
	public function error( $data = null ) {
		$this->send( $data, false );
	}

	/**
	 * Send AJAX response.
	 *
	 * @param array   $data    Data to send using ajax.
	 * @param boolean $success Optional. If this is an error. Defaults: true.
	 */
	private function send( $data, $success = true ) {

		if ( is_string( $data ) ) {
			$data = $success ? [ 'message' => $data ] : [ 'error' => $data ];
		}
		$data['success'] = isset( $data['success'] ) ? $data['success'] : $success;

		\wp_send_json( $data );
	}
}