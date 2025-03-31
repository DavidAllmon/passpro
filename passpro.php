<?php
/**
 * Plugin Name:       PassPro
 * Plugin URI:        https://techitdave.com/
 * Description:       Password protect your entire WordPress site with a single password.
 * Version:           1.0.7
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            David Allmon
 * Author URI:        https://techitdave.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       passpro
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/DavidAllmon/passpro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PASSPRO_VERSION', '1.0.7' );
define( 'PASSPRO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PASSPRO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Include the plugin update checker library.
 */
require PASSPRO_PLUGIN_DIR . 'vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5p5\PucFactory;

/**
 * Setup the plugin update checker.
 */
$passpro_update_checker = PucFactory::buildUpdateChecker(
    'https://github.com/DavidAllmon/passpro/',
    __FILE__,
    'passpro'
);

// Set the branch that contains the stable release.
$passpro_update_checker->setBranch('master');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require PASSPRO_PLUGIN_DIR . 'includes/class-passpro.php';

/**
 * Create the database tables on plugin activation.
 */
function passpro_activate() {
    require_once PASSPRO_PLUGIN_DIR . 'includes/class-passpro-db.php';
    PassPro_DB::create_tables();
}
register_activation_hook( __FILE__, 'passpro_activate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_passpro() {

	$plugin = new PassPro();
	$plugin->run();

}
run_passpro(); 