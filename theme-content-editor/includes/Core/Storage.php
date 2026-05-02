<?php

namespace ThemeContentEditor\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Storage {

	const OPTION_KEY = 'tce_content_data';

	public function all() {
		$data = get_option( self::OPTION_KEY, array() );
		return is_array( $data ) ? $data : array();
	}

	public function get( $key, $default = '' ) {
		$data = $this->all();
		return isset( $data[ $key ] ) ? $data[ $key ] : $default;
	}

	public function update_many( array $items ) {
		$data = $this->all();
		foreach ( $items as $key => $value ) {
			$data[ $key ] = $value;
		}
		return update_option( self::OPTION_KEY, $data, false );
	}
}
