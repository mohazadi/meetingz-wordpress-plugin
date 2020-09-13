<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://meetingz.ir
 * @since             3.0.0
 * @package           Meetingz
 *
 * @wordpress-plugin
 * Plugin Name:       MeetingZ
 * Plugin URI:        https://github.com/blindsidenetworks/wordpress-plugin_meetingz
 * Description:       MeetingZ is an open source web conferencing system. This plugin integrates MeetingZ into WordPress allowing bloggers to create and manage meetings rooms by using a Custom Post Type. For more information on setting up your own MeetingZ server or for using an external hosting provider visit https://meetingz.ir/support.
 * Version:           3.0.0-beta.4
 * Author:            mmd.azadi@gmail.com
 * Author URI:        https://azadiweb.ir.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       meetingz
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 3.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MEETINGZ_VERSION', '3.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-meetingz-activator.php
 */
function activate_meetingz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-meetingz-activator.php';
	Meetingz_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-meetingz-deactivator.php
 */
function deactivate_meetingz() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-meetingz-deactivator.php';
	Meetingz_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_meetingz' );
register_deactivation_hook( __FILE__, 'deactivate_meetingz' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-meetingz.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.0.0
 */
function run_meetingz() {

	$plugin = new Meetingz();
	$plugin->run();

}
run_meetingz();
