<?php

namespace ThemeContentEditor\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Permissions {

	public function get_required_capability() {
		return 'edit_others_posts';
	}

	public function can_manage_content() {
		return current_user_can( $this->get_required_capability() ) || current_user_can( 'manage_options' );
	}
}
