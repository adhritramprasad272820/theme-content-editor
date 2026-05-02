<?php

namespace ThemeContentEditor\Core;

use ThemeContentEditor\Admin\AdminPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var Registry
	 */
	private $registry;

	public static function activate() {
		if ( false === get_option( 'tce_content_data', false ) ) {
			add_option( 'tce_content_data', array(), '', false );
		}
	}

	public function __construct() {
		$this->storage  = new Storage();
		$this->registry = new Registry();
	}

	public function run() {
		$this->registry->register_default_fields();

		$permissions = new Permissions();
		$admin_page  = new AdminPage( $this->storage, $this->registry, $permissions );
		$api         = new API( $this->storage, $this->registry, $permissions );

		add_action( 'admin_menu', array( $admin_page, 'register_menu' ) );
		add_action( 'admin_init', array( $admin_page, 'handle_save' ) );
		add_action( 'admin_enqueue_scripts', array( $admin_page, 'enqueue_assets' ) );
		add_action( 'rest_api_init', array( $api, 'register_routes' ) );
	}
}
