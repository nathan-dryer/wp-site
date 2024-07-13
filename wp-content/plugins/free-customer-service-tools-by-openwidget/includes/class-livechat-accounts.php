<?php
/**
 * OpenWidget product integration with WordPress.
 *
 * @package OpenWidget
 * @subpackage OpenWidget/includes
 */

namespace FreeCustomerServiceToolsByOpenWidget;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * LiveChat Accounts service integration.
 */
final class LiveChat_Accounts {
	/**
	 * Get sign in URL for LiveChat accounts service.
	 *
	 * @param string $redirect_uri Redirect URI.
	 */
	public static function get_sign_in_url( $redirect_uri ) {
		$url = Config::get( 'livechat_accounts_url' );
		$url = add_query_arg( 'response_type', 'code', $url );
		$url = add_query_arg( 'client_id', Config::get( 'client_id' ), $url );
		$url = add_query_arg( 'redirect_uri', $redirect_uri, $url );

		return $url;
	}
}
