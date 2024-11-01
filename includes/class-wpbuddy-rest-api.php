<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPBuddy_Rest_API class.
 */
class WPBuddy_Rest_API
{

	private static $instance;

	/**
	 * Returns the instance of the class.
	 * @since 1.0.4
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 * @since 1.0.4
	 */
	public function __construct() {
		$this->register_hooks();
	}

	/**
	 * Registers the hooks.
	 * @since 1.0.4
	 */
	public function register_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Registers custom rest routes.
	 * @since 1.0
	 */
	public function register_rest_routes() {
		// Register the rest route to create a ticket.
		register_rest_route(
			'wpbuddy/v1',
			'/create-ticket',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'create_ticket_cb' ),
			)
		);

		// Register the rest route to validate license key on external server.
		register_rest_route(
			'wpbuddy/v1',
			'/validate-license',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'validate_license_cb' ),
			)
		);
	}

	/**
	 * Creates a ticket.
	 * @since 1.0
	 */
	public function create_ticket_cb( $request ) {
		// If current user is not logged in, return an error.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => 'You are not logged in.' ) );
		}
	
		// If user is not an admin, return an error.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'You are not allowed to create a ticket.' ) );
		}

		if ( ! get_option( 'wpbuddy_license_key' ) ) {
			wp_send_json_error( array( 'message' => 'Please set your API key.' ) );
		}

		// Validate nonce.
		$nonce = $request->get_header( 'X-WP-Nonce' );
		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			wp_send_json_error( array( 'message' => 'Invalid nonce.' ) );
		}
	
		// Get the form data.
		$form_data = $request->get_params();
	
		// Validate the form data.
		$validation = WPBuddy_Checker::validate_form_data( $form_data );
	
		// Check if the validation is valid.
		if ( ! empty( $validation ) ) {
			wp_send_json_error( [ 'errors' => $validation ] );
		}

		// Sanitize the form data.
		$form_data = WPBuddy_Checker::sanitize_form_data( $form_data );
	
		$title       = $form_data['title'];
		$description = $form_data['description'];
		$priority_id = $form_data['priority_id'];

		// Sanitize and validate the image.
		$image = WPBuddy_Checker::sanitize_image( $_FILES['image'] );

		$license_key = get_option( 'wpbuddy_license_key' );
		$api_url = wpbuddy_get_api_url( '/api/tickets' );
	
		$boundary = '--------------------------' . microtime( true );
	
		$headers = array(
			'Authorization' => 'Bearer ' . $license_key,
			'Content-Type' => 'multipart/form-data; boundary=' . $boundary,
		);

		$image_field = '';
		
		if ( $image ) {

			// Sanitize the file name
			$file_name   = preg_replace( '/[^a-zA-Z0-9_-]/', '', basename( $image['name'] ) );
			$file_name   = uniqid() . '-' . $file_name;
			$image_path  = $image['tmp_name'];
			$image_name  = $file_name;
			$image_field = "Content-Disposition: form-data; name=\"image\"; filename=\"{$image_name}\"\r\n" .
			"Content-Type: image/jpeg\r\n\r\n" .
			file_get_contents( $image_path ) . "\r\n" .
			"--{$boundary}--\r\n";
		}
		
		$body = "--{$boundary}\r\n" .
			"Content-Disposition: form-data; name=\"title\"\r\n\r\n" .
			"{$title}\r\n" .
			"--{$boundary}\r\n" .
			"Content-Disposition: form-data; name=\"body\"\r\n\r\n" .
			"{$description}\r\n" .
			"--{$boundary}\r\n" .
			"Content-Disposition: form-data; name=\"priority_id\"\r\n\r\n" .
			"{$priority_id}\r\n" .
			"--{$boundary}\r\n" .
			$image_field;
		
		$response = wp_remote_post(
			$api_url,
			array(
				'headers' => $headers,
				'body' => $body,
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => 'Something went wrong. Please try again.' ) );
		}

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body );
		if ( is_null( $body ) ) {
			wp_send_json_error( array( 'message' => 'Something went wrong. Please try again.' ) );
		}
		wp_send_json( $body );
	}

	/**
	 * Validates the license key.
	 * @since 1.0.3
	 */
	public function validate_license_cb( $request ) {
		// If current user is not logged in, return an error.
		if ( ! is_user_logged_in() ) {
			wp_send_json_error( array( 'message' => 'You are not logged in.' ) );
		}
	
		// If user is not an admin, return an error.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'You are not allowed to validate the license key.' ) );
		}

		$license_key = $request->get_param( 'wpbuddy_license_key' );
		$license_key = sanitize_text_field( $license_key );
		$api_url     = wpbuddy_get_api_url( '/api/license/validate' );
		$origin_url  = get_site_url();

		$headers = array(
			'Authorization' => 'Bearer ' . $license_key,
			'Accept' => 'application/json',
		);

		$response = wp_remote_post(
			$api_url,
			array(
				'headers' => $headers,
				'body' => array(
					'origin_url' => $origin_url,
				)
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( array( 'message' => 'Something went wrong. Please try again.' ) );
		}
		
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		wp_send_json( $response_body );
	}

	/**
	 * Checks if the file is valid.
	 * @since 1.0
	 */
	public function is_valid_file( $file ) {
		// Check if the file is an array.
		if ( ! is_array( $file ) ) {
			return false;
		}

		// Check if the file name is set.
		if ( ! isset( $file['name'] ) || empty( $file['name'] ) ) {
			return false;
		}

		// Check if the file type is set.
		if ( ! isset( $file['type'] ) || empty( $file['type'] ) ) {
			return false;
		}

		// Check if the file size is set.
		if ( ! isset( $file['size'] ) || empty( $file['size'] ) ) {
			return false;
		}

		// Check if the file path is set.
		if ( ! isset( $file['tmp_name'] ) || empty( $file['tmp_name'] ) ) {
			return false;
		}

		// Check if the file content is set.
		if ( ! isset( $file['content'] ) || empty( $file['content'] ) ) {
			return false;
		}

		return true;
	}

		/**
	 * Sanitizes the settings.
	 * @since 1.0
	 */
	public function sanitize_license_key( $input ) {

		return sanitize_text_field( $input );
	
	}
}

$wpbuddy_rest_api = WPBuddy_Rest_API::get_instance();
