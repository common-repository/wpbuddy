<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPBuddy Checker class.
 */

class WPBuddy_Checker
{
    /**
	 * Validates the form data.
	 * @since 1.0
	 */
	public static function validate_form_data( $form_data ) {

		$errors = array();
		// Check if the title is set.
		if ( ! isset( $form_data['title'] ) || empty( $form_data['title'] ) ) {
			$errors['title'] = ['Title is required.'];
		}

		// Check if the description is set.
		if ( ! isset( $form_data['description'] ) || empty( $form_data['description'] ) ) {
			$errors['description'] = ['Description is required.'];
		}

		// Check if the priority is set.
		if ( ! isset( $form_data['priority_id'] ) || empty( $form_data['priority_id'] ) ) {
			$errors['priority_id'] = ['Priority is missing.'];
		}

		return $errors;
	}

	/**
	 * Sanitizes the form data.
	 * @since 1.0
	 */
	public static function sanitize_form_data( $form_data ) {
		// Sanitize the title.
		$form_data['title'] = sanitize_text_field( $form_data['title'] );
	
		// Sanitize the description.
		$form_data['description'] = sanitize_textarea_field( $form_data['description'] );
	
		// Sanitize the priority.
		$form_data['priority_id'] = sanitize_text_field( $form_data['priority_id'] );
	
		return $form_data;
	}

	/**
	 * Sanitizes the image.
	 * @since 1.0
	 */
	public static function sanitize_image( $image ) {

		$allowed_extensions = array( 'jpg', 'jpeg', 'png', 'gif' );
		$extension          = pathinfo( $image['name'], PATHINFO_EXTENSION );

		if ( ! in_array( $extension, $allowed_extensions ) ) {
			return false;
		}

		if ( ! isset( $image ) || empty( $image ) ) {
			return false;
		}
	
		if ( ! getimagesize( $image['tmp_name'] ) ) {
			return false;
		}
	
		if ( $image['size'] > 5000000 ) {
			return false;
		}
	
		return $image;
	}
}