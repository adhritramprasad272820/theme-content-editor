<?php

namespace ThemeContentEditor\Support;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Repeater {
	public static function render( $key, $value ) {
		$rows = is_array( $value ) ? $value : array();
		echo '<div class="tce-repeater" data-key="' . esc_attr( $key ) . '">';
		echo '<div class="tce-repeater-rows">';
		foreach ( $rows as $index => $row ) {
			self::row( $key, $index, $row );
		}
		echo '</div>';
		echo '<button type="button" class="button tce-repeater-add">' . esc_html__( 'Add Item', 'theme-content-editor' ) . '</button>';
		echo '</div>';
		self::template( $key );
	}

	private static function row( $key, $index, $row ) {
		echo '<div class="tce-repeater-row">';
		$fields = array( 'title', 'description', 'image', 'file', 'link' );
		foreach ( $fields as $field ) {
			$name  = 'tce_content[' . $key . '][' . $index . '][' . $field . ']';
			$value = $row[ $field ] ?? '';
			echo '<p><label>' . esc_html( ucfirst( $field ) ) . '</label>';
			if ( 'description' === $field ) {
				echo '<textarea name="' . esc_attr( $name ) . '">' . esc_textarea( $value ) . '</textarea>';
			} else {
				echo '<input type="text" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="regular-text" />';
			}
			echo '</p>';
		}
		echo '<button type="button" class="button-link-delete tce-repeater-remove">' . esc_html__( 'Remove', 'theme-content-editor' ) . '</button>';
		echo '</div>';
	}

	private static function template( $key ) {
		echo '<script type="text/template" id="tmpl-tce-repeater-' . esc_attr( $key ) . '">';
		self::row( $key, '{{data.index}}', array() );
		echo '</script>';
	}
}
