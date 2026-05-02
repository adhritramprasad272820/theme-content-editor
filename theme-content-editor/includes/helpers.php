<?php

use ThemeContentEditor\Core\Storage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function tce_storage() {
	static $storage = null;
	if ( null === $storage ) {
		$storage = new Storage();
	}
	return $storage;
}

function theme_content( $key, $default = '' ) {
	$value = tce_storage()->get( sanitize_key( $key ), $default );
	echo wp_kses_post( $value );
}

function theme_image( $key, $default = '' ) {
	$value = tce_storage()->get( sanitize_key( $key ), $default );
	echo esc_url( $value );
}

function theme_file( $key, $default = '' ) {
	$value = tce_storage()->get( sanitize_key( $key ), $default );
	echo esc_url( $value );
}

function theme_link( $key, $default = '' ) {
	$value = tce_storage()->get( sanitize_key( $key ), $default );
	echo esc_url( $value );
}
