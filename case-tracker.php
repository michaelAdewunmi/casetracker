<?php
/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       Case Tracker Plugin
 * Plugin URI:        http://github.com/josbiz/boilerplate
 * Description:       A Plugin for tracking cases assigned to Lawyers and Judges
 * Version:           1.0.0
 * Author:            Josbiz
 * Author URI:        http://github.com/michaelAdewunmi
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       josbiz-case-tracker
 * Domain Path:       /languages
 *
 * @category   Plugins
 * @package    WordPress
 * @subpackage WordPress/Plugins
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */

require "vendor/autoload.php";

use DevignersPlace\CaseTracker\Includes\Activator;
use DevignersPlace\CaseTracker\Includes\Deactivator;
use DevignersPlace\CaseTracker\Includes\PluginLoader;

if (!defined('WPINC')) {
    return;
}

/**
 * Current plugin version.
 * Starting at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('JOSBIZ_PLUGIN_VERSION', '1.0.0');

/**
 * Current plugin name.
 * Rename this for your plugin and update it if you change name.
 */
define('JOSBIZ_PLUGIN_NAME', 'case-tracker');

define('JOSBIZ_PLUGIN_MAIN_DIR', plugin_dir_url(__FILE__) . 'src/');

define('JOSBIZ_PLUGIN_PUBLIC_DIR', JOSBIZ_PLUGIN_MAIN_DIR . 'publicdir/');

define('JOSBIZ_PLUGIN_TEXT_DOMAIN', 'josbiz-case-tracker');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/Activator.php
 *
 * @return void
 */
function activate_case_tracker()
{
    Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/Deactivator.php
 * @return void
 */
function deactivate_case_tracker()
{
    Deactivator::deactivate();
}


register_activation_hook(__FILE__, 'activate_case_tracker');
register_deactivation_hook(__FILE__, 'deactivate_case_tracker');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since  1.0.0
 * @return void
 */
function run_case_tracker()
{
    $plugin = new PluginLoader();
    $plugin->run();
}
run_case_tracker();
