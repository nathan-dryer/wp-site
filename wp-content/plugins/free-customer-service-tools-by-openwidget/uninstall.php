<?php
/**
 * OpenWidget product integration with WordPress.
 *
 * @link https://openwidget.com
 * @package OpenWidget
 * @since 1.0.0
 */

namespace FreeCustomerServiceToolsByOpenWidget;

require_once plugin_dir_path( __FILE__ ) . 'includes/class-config.php';

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( Config::get( 'organization_id_option_name' ) );
