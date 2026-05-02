<?php

namespace ThemeContentEditor\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sanitizer {
	public function sanitize( $type, $value ) {
		switch ( $type ) {
			case 'rich_text':
				return wp_kses_post( $value );
			case 'image':
			case 'background_image':
			case 'file':
				return esc_url_raw( $value );
			case 'button_link':
			case 'link':
				return esc_url_raw( $value );
			case 'repeater':
				return $this->sanitize_repeater( $value );
			case 'button_text':
			case 'text':
			default:
				return sanitize_text_field( $value );
		}
	}

	private function sanitize_repeater( $rows ) {
		$sanitized = array();
		if ( ! is_array( $rows ) ) {
			return $sanitized;
		}
		foreach ( $rows as $row ) {
			$sanitized[] = array(
				'title'       => sanitize_text_field( $row['title'] ?? '' ),
				'description' => sanitize_textarea_field( $row['description'] ?? '' ),
				'image'       => esc_url_raw( $row['image'] ?? '' ),
				'file'        => esc_url_raw( $row['file'] ?? '' ),
				'link'        => esc_url_raw( $row['link'] ?? '' ),
			);
		}
		return $sanitized;
	}
}
