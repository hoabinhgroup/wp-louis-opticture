<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://louiscms.com
 * @since             1.0.0
 * @package           Louis_Image_Compressors
 *
 * @wordpress-plugin
 * Plugin Name:       Louis Opticture
 * Plugin URI:        https://louiscms.com
 * Description:       Louis Opticture optimizes images while ensuring quality for your photos. Check in <a href=/wp-admin/options-general.php?page=image-compressors>settings</a> to see how to optimize for images and make your website run faster.
 * Version:           1.8.6
 * Author:            Tuấn Louis
 * Author URI:        https://louiscms.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       https://louiscms.com
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
define( 'LOUIS_IMAGE_COMPRESSORS_VERSION', '1.8.5' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-louis-image-compressors-activator.php
 */
function activate_louis_image_compressors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-louis-image-compressors-activator.php';
	Louis_Image_Compressors_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-louis-image-compressors-deactivator.php
 */
function deactivate_louis_image_compressors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-louis-image-compressors-deactivator.php';
	Louis_Image_Compressors_Deactivator::deactivate();
}



register_activation_hook( __FILE__, 'activate_louis_image_compressors' );
register_deactivation_hook( __FILE__, 'deactivate_louis_image_compressors' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path( __FILE__ ) . 'includes/class-louis-image-compressors.php';
require plugin_dir_path( __FILE__ ) . 'abstracts/base_custom_data.php';
require plugin_dir_path( __FILE__ ) . 'models/louis_opticture_meta.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_louis_image_compressors() {

	$plugin = new Louis_Image_Compressors();
	$plugin->run();

}
run_louis_image_compressors();
