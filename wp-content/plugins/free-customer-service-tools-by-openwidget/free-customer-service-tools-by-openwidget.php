<?php
/**
 * OpenWidget product integration with WordPress.
 *
 * @package OpenWidget
 *
 * Plugin Name:        Free Customer Service Tools by OpenWidget
 * Plugin URI:         https://www.openwidget.com/
 * Description:        Enhance Your Website for Better Customer Relations
 * Version:            1.0.2
 * Requires at least:  4.6
 * Requires PHP:       6.1
 * Author:             text.com
 * Author URI:         https://text.com
 * Text Domain:        free-customer-service-tools-by-openwidget
 * Domain Path:        /languages
 */

namespace FreeCustomerServiceToolsByOpenWidget;

if ( ! defined( 'WPINC' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-page.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-snippet-code.php';

new Plugin( plugin_basename( __FILE__ ) );
new Admin_Page( plugin_basename( __FILE__ ) );
new Snippet_Code( plugin_basename( __FILE__ ) );
