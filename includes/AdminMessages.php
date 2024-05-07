<?php

namespace Bitkorn\Yawpcf;

class AdminMessages {

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'create_menu' ] );
	}

	public function create_menu() {
		$hook_suffix = add_submenu_page(
			'tools.php',
			'YAWPCF Messages',
			'YAWPCF Messages',
			'install_plugins',
			BITKORN_YAWPCF_DOMAIN,
			[ $this, 'render_messages' ],
			70
		);
	}

	public function render_messages() {
		global $wpdb;
		$order = $_GET['order'] ?? '';
		$messs = $wpdb->get_results( sprintf( 'SELECT * FROM %s', $wpdb->prefix . BITKORN_YAWPCF_TABLE ), ARRAY_A );
		$vr    = new ViewRenderer( bitkorn_yawpcf_plugin_dir() . 'views/admin-messages.php' );
		echo $vr->render( [ 'messs' => $messs ] );
	}
}
