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
require_once plugin_dir_path( __FILE__ ) . 'class-livechat-accounts.php';
require_once plugin_dir_path( __FILE__ ) . 'class-openwidget-api.php';

/**
 * OpenWidget admin page.
 */
final class Admin_Page {
	/**
	 * Plugin name.
	 *
	 *  @var string $plugin_name Plugin name.
	 */
	public $plugin_name;
	/**
	 * Admin_Page constructor.
	 *
	 * @param string $plugin_name Plugin name.
	 */
	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;

		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		add_action( 'admin_init', array( $this, 'handle_query_args' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Add menu item.
	 */
	public function add_menu_item() {
		add_menu_page( 'OpenWidget', 'OpenWidget', 'administrator', 'openwidget', array( $this, 'render' ), plugin_dir_url( __FILE__ ) . '../assets/img/icon.svg' );
	}

	/**
	 * Handle query args.
	 */
	public function handle_query_args() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['organization_id'] ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$organization_id = sanitize_text_field( $_GET['organization_id'] );

		if ( ! $organization_id ) {
			return;
		}

		if ( ! current_user_can( 'administrator' ) ) {
			wp_safe_redirect( admin_url() );
			return;
		}

		update_option( Config::get( 'organization_id_option_name' ), $organization_id );
		wp_safe_redirect( admin_url( 'admin.php?page=openwidget' ) );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param string $hook_suffix Hook name.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		wp_enqueue_style( 'openwidget-admin-menu', plugin_dir_url( __FILE__ ) . '../assets/css/admin-menu.css', array(), '1.0.0', 'all' );

		if ( 'toplevel_page_openwidget' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style( 'openwidget-admin-page', plugin_dir_url( __FILE__ ) . '../assets/css/admin-page.css', array(), '1.0.0', 'all' );

		wp_enqueue_script( 'openwidget-admin-page', plugin_dir_url( __FILE__ ) . '../assets/js/admin-page.js', array(), '1.0.0', true );
	}

	/**
	 * Render admin page.
	 */
	public function render() {
		$organization_id = get_option( Config::get( 'organization_id_option_name' ) );

		$openwidget_app_link        = Config::get( 'openwidget_app_url' );
		$wordpress_install_endpoint = OpenWidget_API::get_wordpress_install_endpoint();
		$connect_link               = LiveChat_Accounts::get_sign_in_url( $wordpress_install_endpoint );

		$plug_url  = plugin_dir_url( __FILE__ ) . '../assets/img/plug.svg';
		$check_url = plugin_dir_url( __FILE__ ) . '../assets/img/check.svg';
		$logo_url  = plugin_dir_url( __FILE__ ) . '../assets/img/logo-full.svg';

		$status_icon          = $organization_id ? $check_url : $plug_url;
		$status_icon_alt_text = __( 'Status icon', 'free-customer-service-tools-by-openwidget' );
		$heading              = $organization_id ? __( 'OpenWidget is connected', 'free-customer-service-tools-by-openwidget' ) : __( 'Connect OpenWidget to your website', 'free-customer-service-tools-by-openwidget' );
		$description          = $organization_id ? __( 'Customize your widget and boost your customer engagement.', 'free-customer-service-tools-by-openwidget' ) : __( 'Log in or create an account to add OpenWidget to your website.', 'free-customer-service-tools-by-openwidget' );
		$button_label         = $organization_id ? __( 'Customize widget', 'free-customer-service-tools-by-openwidget' ) : __( 'Continue', 'free-customer-service-tools-by-openwidget' );
		$button_link          = $organization_id ? $openwidget_app_link : $connect_link;
		$button_target        = $organization_id ? '_blank' : '_self';

		?>
			<main class="wrap wpbody-content openwidget_admin_page--content">
				<div class="openwidget_admin_page--logo-full" style="background-image: url(<?php echo esc_html( $logo_url ); ?>);"></div>
				<div class="openwidget_admin_page--container">
					<div class="openwidget_admin_page--section">
						<img class="openwidget_admin_page--status-icon" src="<?php echo esc_html( $status_icon ); ?>" alt="<?php echo esc_attr( $status_icon_alt_text ); ?>">
					</div>
					<div class="openwidget_admin_page--section">
						<h1><?php echo esc_html( $heading ); ?></h1>
						<p><?php echo esc_html( $description ); ?></p>
					</div>
					<div class="openwidget_admin_page--section">
							<a class="openwidget_admin_page--button" href="<?php echo esc_attr( $button_link ); ?>" target="<?php echo esc_attr( $button_target ); ?>"><?php echo esc_html( $button_label ); ?></a>
					</div>
				</div>
			</main>
		<?php
	}
}
