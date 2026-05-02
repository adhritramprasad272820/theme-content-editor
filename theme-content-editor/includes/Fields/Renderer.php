<?php

namespace ThemeContentEditor\Fields;

use ThemeContentEditor\Support\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Renderer {
	public function render( $field, $value ) {
		$key   = esc_attr( $field['key'] );
		$label = esc_html( $field['label'] );
		echo '<div class="tce-field"><label><strong>' . $label . '</strong></label>';

		switch ( $field['type'] ) {
			case 'rich_text':
				wp_editor( $value, 'tce_' . $key, array( 'textarea_name' => 'tce_content[' . $key . ']' ) );
				break;
			case 'image':
			case 'background_image':
			case 'file':
				$this->render_media( $key, $value, $field['type'] );
				break;
			case 'button_link':
			case 'link':
				printf( '<input type="url" class="regular-text" name="tce_content[%1$s]" value="%2$s" />', $key, esc_attr( $value ) );
				break;
			case 'repeater':
				Repeater::render( $key, $value );
				break;
			default:
				printf( '<input type="text" class="regular-text" name="tce_content[%1$s]" value="%2$s" />', $key, esc_attr( $value ) );
		}
		echo '</div>';
	}

	private function render_media( $key, $value, $type ) {
		$button = 'file' === $type ? __( 'Select File', 'theme-content-editor' ) : __( 'Select Image', 'theme-content-editor' );
		echo '<div class="tce-media-wrap">';
		printf( '<input type="url" class="regular-text tce-media-url" name="tce_content[%1$s]" value="%2$s" />', esc_attr( $key ), esc_attr( $value ) );
		printf( '<button type="button" class="button tce-media-button" data-type="%1$s">%2$s</button>', esc_attr( $type ), esc_html( $button ) );
		if ( ! empty( $value ) && 'file' !== $type ) {
			echo '<div><img src="' . esc_url( $value ) . '" class="tce-preview" alt=""/></div>';
		}
		echo '</div>';
	}
}
