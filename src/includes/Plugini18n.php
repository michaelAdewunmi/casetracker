<?php
namespace DevignersPlace\CaseTracker\Includes;

/**
 * Define the internationalization functionality
 *
 * This class loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage CaseTracker/includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class Plugini18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since  1.0.0
     * @return void
     */
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            'josbiz-case-tracker',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
