<?php

namespace ThemeContentEditor\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class API {
	private $storage;
	private $registry;
	private $permissions;

	public function __construct( Storage $storage, Registry $registry, Permissions $permissions ) {
		$this->storage     = $storage;
		$this->registry    = $registry;
		$this->permissions = $permissions;
	}

	public function register_routes() {
		register_rest_route(
			'tce/v1',
			'/content/(?P<key>[a-zA-Z0-9_\-]+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_content' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public function get_content( $request ) {
		$key = sanitize_key( $request->get_param( 'key' ) );
		return rest_ensure_response(
			array(
				'key'   => $key,
				'value' => $this->storage->get( $key, '' ),
			)
		);
	}
}
