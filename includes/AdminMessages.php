<?php

namespace Bitkorn\Yawpcf;

class AdminMessages {

	private array $orderDirecs = [ 'asc', 'desc' ];
	private array $orders = [ 'name', 'email', 'subject', 'message', 'date_create' ];

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'create_menu' ] );
		add_action( 'wp_ajax_bitkorn_yawpcf_delete_message_action', [ $this, 'bitkorn_yawpcf_delete_message' ] );
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

	public function bitkorn_yawpcf_delete_message(): void {
		if ( check_ajax_referer( 'bitkorn_yawpcf_delete_message_action', true, false ) === false ) {
			wp_send_json_error( 'It seems that you are not allowed to do this.', 401 );
		}
		if ( empty( $id = intval( $_REQUEST['id'] ) ) ) {
			wp_send_json_error( 0, 403, JSON_FORCE_OBJECT );
		}
		if ( AdminMessages::delete_message( $id ) ) {
			wp_send_json_success( 1, 200, JSON_FORCE_OBJECT );
		} else {
			wp_send_json_error( 0, 500, JSON_FORCE_OBJECT );
		}
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

	public static function delete_message( int $id ): bool {
		global $wpdb;
		$res = $wpdb->delete( $wpdb->prefix . BITKORN_YAWPCF_TABLE, [ 'id' => $id ] );

		return $res !== false && $res > 0;
	}
}
