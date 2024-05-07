<?php
/**
 * Plugin Name: Yet Another WP Contact Form
 * Plugin URI: https://bitkorn.de
 * Description: Yet Another WordPress Contact Form (shortcode=bitkorn_yawpcf_contact_form)
 * Author:            Torsten Brieskorn
 * Author URI:        https://bitkorn.de
 * Text Domain:       bitkorn-yawpcf
 * Domain Path:       /languages
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      8.0
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

use Bitkorn\Yawpcf\AdminMessages;
use Bitkorn\Yawpcf\YawpcfHelper;

require_once __DIR__ . '/includes/YawpcfHelper.php';
require_once __DIR__ . '/includes/FormHandler.php';
const BITKORN_YAWPCF_DOMAIN           = 'bitkorn-yawpcf';
const BITKORN_YAWPCF_DB_INSTALLED     = 'bitkorn_yawpcf_db_installed';
const BITKORN_YAWPCF_TABLE            = 'bitkorn_yawpcf_inbox';
const BITKORN_YAWPCF_RECIPIENT_EMAIL  = 'bitkorn_yawpcf_rcipient_email';
const BITKORN_YAWPCF_DEBUG_EMAIL_SEND = 'bitkorn_yawpcf_debug_email_send';

register_activation_hook( __FILE__, [ YawpcfHelper::class, 'activate' ] );
register_deactivation_hook( __FILE__, [ YawpcfHelper::class, 'deactivate' ] );
register_uninstall_hook( __FILE__, [ YawpcfHelper::class, 'uninstall' ] );

load_plugin_textdomain( BITKORN_YAWPCF_DOMAIN, false, 'yawpcf/languages/' );

add_action( 'wp_enqueue_scripts', 'bitkorn_yawpcf_load_scripts_front' );
function bitkorn_yawpcf_load_scripts_front(): void {
	wp_register_style( 'yawpcf_w3', plugins_url( 'yawpcf/css/w3.css' ), [], '1.0' );
	wp_enqueue_style( 'yawpcf_w3' );
	wp_register_style( 'yawpcf_style_form', plugins_url( 'yawpcf/css/style-form.css' ) );
	wp_enqueue_style( 'yawpcf_style_form' );
	wp_enqueue_script( 'bitkorn-yawpcf-form', plugins_url( 'yawpcf/js/yawpcf-form.js' ), [ 'jquery' ] );
}

add_action( 'admin_enqueue_scripts', 'bitkorn_yawpcf_load_scripts_admin' );
function bitkorn_yawpcf_load_scripts_admin(): void {
	wp_register_style( 'yawpcf_w3', plugins_url( 'yawpcf/css/w3.css' ), [], '1.0' );
	wp_enqueue_style( 'yawpcf_w3' );
	wp_register_style( 'yawpcf_style_admin', plugins_url( 'yawpcf/css/style-admin.css' ) );
	wp_enqueue_style( 'yawpcf_style_admin' );
	wp_enqueue_script( 'bitkorn-yawpcf-admin', plugins_url( 'yawpcf/js/yawpcf-admin.js' ), [ 'jquery' ] );
}

if ( is_admin() ) {
	require_once __DIR__ . '/includes/admin.php';
	require_once __DIR__ . '/includes/AdminMessages.php';
	new AdminMessages();

	add_action( 'admin_menu', 'bitkorn_yawpcf_plugin_admin_add_page' );

	function bitkorn_yawpcf_plugin_admin_add_page(): void {
		add_options_page( 'YAWPCF page', 'YAWPCF', 'manage_options'
			, 'bitkorn_yawpcf_plugin_page', 'bitkorn_yawpcf_plugin_options_page' );
	}

	function bitkorn_yawpcf_plugin_options_page(): void {
		include bitkorn_yawpcf_plugin_dir() . 'views/options.php';
	}
}

if ( WP_DEBUG && get_option( BITKORN_YAWPCF_DEBUG_EMAIL_SEND ) ) {
	add_action( 'wp_mail_failed', 'onMailError', 10, 1 );
	function onMailError( $wp_error ): void {
		echo "<pre>";
		print_r( $wp_error );
		echo "</pre>";
	}
}

/**
 * @return string Plugin directory with trailing slash.
 */
function bitkorn_yawpcf_plugin_dir(): string {
	return plugin_dir_path( __FILE__ );
}

function bitkorn_yawpcf_shortcode( $atts, string $content, string $tag ): string {
	return ( new \Bitkorn\Yawpcf\FormHandler() )->handle_form();
}

add_shortcode( 'bitkorn_yawpcf_contact_form', 'bitkorn_yawpcf_shortcode' );

/**
 * Register Settings.
 *
 * @return void
 */
function bitkorn_yawpcf_register_settings(): void {
	register_setting( 'bitkorn_yawpcf_plugin_options', BITKORN_YAWPCF_RECIPIENT_EMAIL, [
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_email',
	] );
	register_setting( 'bitkorn_yawpcf_plugin_options', BITKORN_YAWPCF_DEBUG_EMAIL_SEND );

	add_settings_section( 'bitkorn_yawpcf_settings_section', 'YAWPCF Settings', 'bitkorn_yawpcf_section_text', 'bitkorn_yawpcf_plugin_page' );

	add_settings_field( 'bitkorn_yawpcf_rcipient_email_input', 'Recipient email address', 'bitkorn_yawpcf_rcipient_email_callback',
		'bitkorn_yawpcf_plugin_page', 'bitkorn_yawpcf_settings_section' );
	add_settings_field( 'bitkorn_yawpcf_debug_email_send_input', 'Debug email sending', 'bitkorn_yawpcf_debug_email_send_callback',
		'bitkorn_yawpcf_plugin_page', 'bitkorn_yawpcf_settings_section' );

	function bitkorn_yawpcf_section_text(): void {
		echo '<p>Settings for YAWPCF</p>';
	}

	function bitkorn_yawpcf_rcipient_email_callback(): void {
		$option = get_option( BITKORN_YAWPCF_RECIPIENT_EMAIL );
		echo "<input id='bitkorn_yawpcf_rcipient_email_input' name='bitkorn_yawpcf_rcipient_email' value='$option'>";
		echo '<br><small>Must be a valid email address.</small>';
	}

	function bitkorn_yawpcf_debug_email_send_callback(): void {
		$option  = get_option( BITKORN_YAWPCF_DEBUG_EMAIL_SEND );
		$checked = ! empty( $option ) ? "checked='checked'" : '';
		echo "<input type='checkbox' id='bitkorn_yawpcf_debug_email_send_input' name='bitkorn_yawpcf_debug_email_send' value='1' $checked>";
	}

}

add_action( 'admin_init', 'bitkorn_yawpcf_register_settings' );
