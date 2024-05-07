<?php

namespace Bitkorn\Yawpcf;

require_once __DIR__ . '/ViewRenderer.php';

class FormHandler {
	protected \WP_Error $errors;
	protected string $messageHtml = '';

	public function __construct() {
		$this->errors = new \WP_Error();
	}

	public function message_validate( $name, $email, $subject, $message ): void {
		if ( empty( $name ) || empty( $subject ) || empty( $message ) ) {
			$this->errors->add( 'field', __( 'All fields must be filled out', BITKORN_YAWPCF_DOMAIN ) );
		}
		if ( ! is_email( $email ) || empty( $email ) ) {
			$this->errors->add( 'email_invalid', __( 'Email is not valid', BITKORN_YAWPCF_DOMAIN ) );
		}
		if ( is_wp_error( $this->errors ) ) {
			foreach ( $this->errors->get_error_messages() as $error ) {
				$this->messageHtml .= '<div><strong>ERROR</strong>: ' . $error . '<br></div>';
			}
		}
	}

	public function save_message( $name, $email, $subject, $message ): void {
		global $wpdb;
		if ( $wpdb->insert( $wpdb->prefix . BITKORN_YAWPCF_TABLE, [
				'name'    => $name,
				'email'   => $email,
				'subject' => $subject,
				'message' => $message,
			] ) == 1 ) {
			$this->messageHtml .= '<div><p>'.__('Thanks for contacting us, expect a response soon.', BITKORN_YAWPCF_DOMAIN).'</p></div>';
		}
	}

	public function message_notice_admin( $name, $email, $subject, $message ): void {
		if ( ( empty( $adminEmail = get_option( BITKORN_YAWPCF_RECIPIENT_EMAIL ) )
		       || empty( sanitize_email( $adminEmail ) ) )
		     &&
		     ( empty( $adminEmail = get_option( 'admin_email' ) )
		       || empty( sanitize_email( $adminEmail ) ) ) ) {
			return;
		}
		if ( isset( $_POST['bitkorn-yawpcf-submitted'] ) && 1 > count( $this->errors->get_error_messages() ) ) {
			$subjectMail = 'Message from Wordpress Plugin Bitkorn_YAWPCF';
			$messageMail = "Hallo Admin,\r\njemand hat dir über das Kontaktformular eine Nachricht geschickt:\r\n
Name: $name\r\n
Email: $email\r\n
Subject: $subject\r\n
Message:$message\r\n";

			wp_mail( $adminEmail, $subjectMail, $messageMail );
		}
	}

	function handle_form(): string {
		if ( isset( $_POST['bitkorn-yawpcf-submitted'] ) ) {
			$name    = sanitize_text_field( $_POST['yawpcf-name'] );
			$email   = sanitize_email( $_POST['yawpcf-email'] );
			$subject = sanitize_text_field( $_POST['yawpcf-subject'] );
			$message = sanitize_textarea_field( $_POST['yawpcf-message'] );

			$this->message_validate( $name, $email, $subject, $message );
			if ( ! $this->errors->has_errors() ) {
				$this->save_message( $name, $email, $subject, $message );
				$this->message_notice_admin( $name, $email, $subject, $message );
			}
		}
		$vr = new ViewRenderer( bitkorn_yawpcf_plugin_dir() . 'views/form-default.php' );

		return $vr->render( [ 'messageHtml' => $this->messageHtml ] );
	}
}
