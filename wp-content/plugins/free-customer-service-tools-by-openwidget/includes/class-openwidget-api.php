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
 * OpenWidget API service integration.
 */
final class OpenWidget_API {
	/**
	 * Get endpoint for OpenWidget API to install WordPress plugin.
	 */
	public static function get_wordpress_install_endpoint() {
		$url = Config::get( 'openwidget_api_url' ) . '/v1.0/plugin/wordpress/install';
		$url = add_query_arg( 'location', admin_url( 'admin.php?page=openwidget' ), $url );

		return $url;
	}
}
