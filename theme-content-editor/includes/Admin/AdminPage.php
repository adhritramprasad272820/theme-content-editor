<?php

namespace ThemeContentEditor\Admin;

use ThemeContentEditor\Core\Permissions;
use ThemeContentEditor\Core\Registry;
use ThemeContentEditor\Core\Storage;
use ThemeContentEditor\Fields\Renderer;
use ThemeContentEditor\Fields\Sanitizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminPage {
	private $storage;
	private $registry;
	private $permissions;
	private $renderer;
	private $sanitizer;

	public function __construct( Storage $storage, Registry $registry, Permissions $permissions ) {
		$this->storage     = $storage;
		$this->registry    = $registry;
		$this->permissions = $permissions;
		$this->renderer    = new Renderer();
		$this->sanitizer   = new Sanitizer();
	}

	public function register_menu() {
		add_menu_page(
			__( 'Theme Content Editor', 'theme-content-editor' ),
			__( 'Theme Content Editor', 'theme-content-editor' ),
			$this->permissions->get_required_capability(),
			'theme-content-editor',
			array( $this, 'render_page' ),
			'dashicons-edit-page',
			58
		);
	}

	public function enqueue_assets( $hook ) {
		if ( 'toplevel_page_theme-content-editor' !== $hook ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_style( 'tce-admin', TCE_PLUGIN_URL . 'assets/css/admin.css', array(), TCE_VERSION );
		wp_enqueue_script( 'tce-admin', TCE_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'wp-util' ), TCE_VERSION, true );
	}

	public function handle_save() {
		if ( ! isset( $_POST['tce_save_content'] ) ) {
			return;
		}
		if ( ! $this->permissions->can_manage_content() ) {
			wp_die( esc_html__( 'Unauthorized', 'theme-content-editor' ) );
		}
		check_admin_referer( 'tce_save_content_nonce' );
		$raw    = isset( $_POST['tce_content'] ) ? wp_unslash( $_POST['tce_content'] ) : array();
		$fields = $this->registry->get_fields();
		$data   = array();
		foreach ( $fields as $key => $field ) {
			$val         = $raw[ $key ] ?? $field['default'];
			$data[ $key ] = $this->sanitizer->sanitize( $field['type'], $val );
		}
		$this->storage->update_many( $data );
		wp_safe_redirect( add_query_arg( array( 'page' => 'theme-content-editor', 'updated' => 'true' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	public function render_page() {
		if ( ! $this->permissions->can_manage_content() ) {
			wp_die( esc_html__( 'Unauthorized', 'theme-content-editor' ) );
		}
		$sections = $this->registry->get_sections();
		$data     = $this->storage->all();
		?>
		<div class="wrap tce-admin-wrap">
			<h1><?php esc_html_e( 'Theme Content Editor', 'theme-content-editor' ); ?></h1>
			<?php if ( isset( $_GET['updated'] ) ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'Content updated.', 'theme-content-editor' ); ?></p></div>
			<?php endif; ?>
			<form method="post">
				<?php wp_nonce_field( 'tce_save_content_nonce' ); ?>
				<div class="tce-tabs">
					<?php foreach ( $sections as $section_key => $section_label ) : ?>
						<h2><?php echo esc_html( $section_label ); ?></h2>
						<div class="tce-section">
							<?php
							$fields = $this->registry->get_fields_by_section( $section_key );
							foreach ( $fields as $field ) {
								$value = $data[ $field['key'] ] ?? $field['default'];
								$this->renderer->render( $field, $value );
							}
							?>
						</div>
					<?php endforeach; ?>
				</div>
				<p><button type="submit" name="tce_save_content" class="button button-primary button-large"><?php esc_html_e( 'Save Content', 'theme-content-editor' ); ?></button></p>
			</form>
		</div>
		<?php
	}
}
