<?php

namespace Bitkorn\Yawpcf;

class YawpcfHelper {

	public static function activate(): void {
		// ...
	}

	public static function deactivate(): void {
		// ...
	}

	public static function uninstall(): void {
		global $wpdb;
		$wpdb->query( sprintf( 'DROP TABLE IF EXISTS %s', $wpdb->prefix . BITKORN_YAWPCF_TABLE ) );
		delete_option( BITKORN_YAWPCF_DB_INSTALLED);
		delete_option( BITKORN_YAWPCF_RECIPIENT_EMAIL);
		delete_option( BITKORN_YAWPCF_DEBUG_EMAIL_SEND);
	}
}
