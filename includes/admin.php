<?php

namespace Bitkorn\Yawpcf;

// https://developer.wordpress.org/reference/hooks/admin_notices/
add_action( 'admin_notices', 'Bitkorn\Yawpcf\check_db_availability' );

function check_db_availability(): void {
	$db_installed = bool_from_yn( get_option( BITKORN_YAWPCF_DB_INSTALLED, 'n' ) );
	if ( $db_installed ) {
		return;
	}
	printf(
		'<div class="notice notice-info notice-alt"><p>%s <a href="%s" class="button">%s</a></p></div>',
		__( 'The Yawpcf Plugin needs to install a database table to work properly. Should this be done now?', BITKORN_YAWPCF_DOMAIN ),
		esc_url( wp_nonce_url(
			admin_url( '?action=bitkorn_yawpcf_install_db' ),
			'bitkorn_yawpcf_install_db'
		) ),
		__( 'Yes', BITKORN_YAWPCF_DOMAIN )
	);
}

add_action( 'admin_action_bitkorn_yawpcf_install_db', 'Bitkorn\Yawpcf\install_db' );

function install_db(): void {
	if ( ! check_admin_referer( 'bitkorn_yawpcf_install_db' ) ) {
		wp_die( __( 'Sorry, you\'re not allowed to do this.', 'fs-er' ) );
	}

	if ( ! function_exists( '\dbDelta' ) ) {
		require ABSPATH . 'wp-admin/includes/upgrade.php';
	}

	global $wpdb;

	$table_name = $wpdb->prefix . BITKORN_YAWPCF_TABLE;

	$sql = sprintf(
		"
        CREATE TABLE `%s` (
            `id` SERIAL,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `subject` VARCHAR(500) NOT NULL,
            `message` VARCHAR(20000) NOT NULL,
            `date_create` TIMESTAMP default current_timestamp not null
        ) %s;
        ",
		$table_name,
		$wpdb->get_charset_collate()
	);

	dbDelta( $sql );

	$table_exists = $table_name === $wpdb->get_var( sprintf( 'SHOW TABLES LIKE "%s"', $table_name ) );

	if ( $table_exists ) {
		update_option( BITKORN_YAWPCF_DB_INSTALLED, 'y', true );

		return;
	}

	add_action( 'admin_notices', function () use ( $table_name ) {
		printf(
			'<div class="notice notice-error notice-alt"><p>%s</p></div>',
			sprintf(
				__( 'Was not able to create database table %s.', BITKORN_YAWPCF_DOMAIN ),
				$table_name
			)
		);
	} );
}
