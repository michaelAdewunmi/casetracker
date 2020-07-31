<?php
namespace DevignersPlace\CaseTracker\Admin;

/**
 * The Admin functionality of the plugin.
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage CaseTracker/includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class AdminTasks
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string  $version  The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of the plugin.
     * @param string $version     The version of this plugin.
     *
     * @since 1.0.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since  1.0.0
     * @return void
     */
    public function enqueueStyles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in PluginLoader as all of the hooks are defined
         * in that particular class.
         *
         * The PluginLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/admin-style.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function enqueueScripts()
    {
        /*
        * This function is provided for demonstration purposes only.
        *
        * An instance of this class should be passed to the run() function
        * defined in PluginLoader as all of the hooks are defined
        * in that particular class.
        *
        * The PluginLoader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */
        wp_enqueue_script(
            $this->plugin_name.'-admin-main-script',
            plugin_dir_url(__FILE__) . 'js/admin-script.js',
            array('jquery'),
            $this->version,
            false
        );
    }
}
