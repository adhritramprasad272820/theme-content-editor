<?php
/**
 * Plugin Name: Theme Content Editor
 * Description: Lightweight content layer for static/custom themes. Enables safe client editing for text, media, links, files, and repeaters from wp-admin.
 * Version: 1.0.0
 * Author: Theme Content Editor
 * Text Domain: theme-content-editor
 * Requires at least: 6.4
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TCE_VERSION', '1.0.0' );
define( 'TCE_PLUGIN_FILE', __FILE__ );
define( 'TCE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TCE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once TCE_PLUGIN_DIR . 'includes/Core/Autoloader.php';

ThemeContentEditor\Core\Autoloader::register();

register_activation_hook( TCE_PLUGIN_FILE, array( 'ThemeContentEditor\\Core\\Plugin', 'activate' ) );

function tce_bootstrap() {
	$plugin = new ThemeContentEditor\Core\Plugin();
	$plugin->run();
}
add_action( 'plugins_loaded', 'tce_bootstrap' );

require_once TCE_PLUGIN_DIR . 'includes/helpers.php';
