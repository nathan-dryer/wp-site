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
 * OpenWidget plugin static configuration.
 */
final class Config {
	/**
	 * Configuration.
	 *
	 * @var array
	 */
	private static $config = null;

	/**
	 * Get configuration value.
	 *
	 * @param string $key Configuration key.
	 *
	 * @return string Configuration value.
	 */
	public static function get( $key ) {
		if ( null === self::$config ) {
			$env_config     = getenv( 'OPENWIDGET_CONFIGURATION' );
			$default_config = array(
				'organization_id_option_name' => 'openwidget_organization_id',
				'client_id'                   => '9233ed876f7f393eb2247ea2010bc4ab',
				'tracking_url'                => 'https://cdn.openwidget.com/openwidget.js',
				'openwidget_app_url'          => 'https://app.openwidget.com',
				'openwidget_api_url'          => 'https://api.openwidget.com',
				'livechat_accounts_url'       => 'https://accounts.livechat.com',
			);

			self::$config = $env_config ? json_decode( $env_config, true ) : $default_config;
		}

		return self::$config[ $key ];
	}
}
