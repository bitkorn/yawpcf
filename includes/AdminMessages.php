<?php

namespace Bitkorn\Yawpcf;

class AdminMessages {

	private array $orderDirecs = [ 'asc', 'desc' ];
	private array $orders = [ 'name', 'email', 'subject', 'message', 'date_create' ];

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'create_menu' ] );
	}

	public function create_menu(): void {
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

	public function render_messages(): void {
		global $wpdb;
		$orderDirec = isset( $_GET['order_direc'] ) && in_array( $_GET['order_direc'], $this->orderDirecs ) ? $_GET['order_direc'] : 'DESC';
		$order      = isset( $_GET['order'] ) && in_array( $_GET['order'], $this->orders ) ? $_GET['order'] : 'date_create';
		$orderBy    = 'ORDER BY ' . $order . ' ' . $orderDirec;
		$messs      = $wpdb->get_results( sprintf( 'SELECT * FROM %s %s', $wpdb->prefix . BITKORN_YAWPCF_TABLE, $orderBy ), ARRAY_A );
		$vr         = new ViewRenderer( bitkorn_yawpcf_plugin_dir() . 'views/admin-messages.php' );
		echo $vr->render( [ 'messs' => $messs ] );
	}

	public function delete_message(): bool {
		global $wpdb;
		$id  = ! empty( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
		$res = $wpdb->delete( BITKORN_YAWPCF_TABLE, [ 'id' => $id ] );

		return $res !== false && $res > 0;
	}
}
