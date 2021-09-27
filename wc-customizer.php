<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           Wc_Customizer
 *
 * @wordpress-plugin
 * Plugin Name:       WC customizer
 * Plugin URI:        http://easeare.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Md Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-customizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_CUSTOMIZER_VERSION', '1.0.0' );

$wc_errors = '';

if ( !function_exists( 'sp_array_value' ) ) {
	function sp_array_value( $arr = array(), $key = 0, $default = null ) {
		return ( isset( $arr[ $key ] ) ? $arr[ $key ] : $default );
	}
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-customizer-activator.php
 */
function activate_wc_customizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-customizer-activator.php';
	Wc_Customizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-customizer-deactivator.php
 */
function deactivate_wc_customizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-customizer-deactivator.php';
	Wc_Customizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_customizer' );
register_deactivation_hook( __FILE__, 'deactivate_wc_customizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-customizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_customizer() {

	$plugin = new Wc_Customizer();
	$plugin->run();

}
run_wc_customizer();
