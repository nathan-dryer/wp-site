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

require_once plugin_dir_path( __FILE__ ) . 'class-config.php';

/**
 * OpenWidget code snippet rendered in the footer of the website.
 */
final class Snippet_Code {
	/**
	 * Snippet_Code constructor.
	 *
	 * @param string $plugin_name Plugin name.
	 */
	public function __construct( $plugin_name ) {
		add_action( 'wp_footer', array( $this, 'render' ) );
	}

	/**
	 * Render snippet code.
	 */
	public function render() {
		$organization_id = get_option( Config::get( 'organization_id_option_name' ) );

		if ( ! $organization_id ) {
			return;
		}

		?>
			<!-- Start of OpenWidget (www.openwidget.com) code -->
			<script>
			window.__ow = window.__ow || {};
			window.__ow.organizationId = "<?php echo esc_js( $organization_id ); ?>";
			;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[OpenWidget] You can\'t use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="<?php echo esc_js( Config::get( 'tracking_url' ) ); ?>",t.head.appendChild(n)}};!n.__ow.asyncInit&&e.init(),n.OpenWidget=n.OpenWidget||e}(window,document,[].slice))
			</script>
			<!-- End of OpenWidget code -->
		<?php
	}
}
