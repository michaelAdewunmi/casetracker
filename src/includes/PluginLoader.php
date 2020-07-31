<?php
namespace DevignersPlace\CaseTracker\Includes;

use DevignersPlace\CaseTracker\Admin\AdminTasks;
use DevignersPlace\CaseTracker\PublicDir\Classes\PublicTasks;

/**
 * Fired during plugin activation.
 * This Class runs different action and filters used in the plugin
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage CaseTracker/includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class PluginLoader
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    HooksLoader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since  1.0.0
     * @access protected
     * @var    string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (defined('PLUGIN_VERSION')) {
            $this->version = PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        if (defined('PLUGIN_PUBLIC_NAME')) {
            $this->plugin_name = PLUGIN_PUBLIC_NAME;
        } else {
            define('PLUGIN_PUBLIC_NAME', 'case-tracker');
            $this->plugin_name = PLUGIN_PUBLIC_NAME;
        }
        $this->loadDependencies();
        $this->setLocale();
        $this->defineAdminHooks();
        $this->definePublicHooks();
    }

    /**
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function loadDependencies()
    {
        $this->loader = new HooksLoader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Plugini18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.0.0
     * @access private
     */
    private function setLocale()
    {
        $plugin_i18n = new Plugini18n();

        $this->loader->addAction('plugins_loaded', $plugin_i18n, 'loadPluginTextdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since 1.0.0
     * @access private
     */
    private function defineAdminHooks()
    {
        $plugin_admin = new AdminTasks($this->getPluginName(), $this->getVersion());

        $this->loader->addAction('admin_enqueue_scripts', $plugin_admin, 'enqueueStyles');
        $this->loader->addAction('admin_enqueue_scripts', $plugin_admin, 'enqueueScripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access private
     */
    private function definePublicHooks()
    {
        $plugin_public = new PublicTasks($this->getPluginName(), $this->getVersion());
        $page_templater = new PageAndPostTemplater($this->getPluginName(), $this->getVersion());
        $page_or_post_creator = new PageOrPostCreator($this->getPluginName(), $this->getVersion());
        $register_lawyer_handler = new RegisterLawyer(
            new PostGlobalVariableGetter,
            wp_roles(),
            new \WP_Error
        );

        $this->loader->addAction('wp_enqueue_scripts', $plugin_public, 'enqueueStyles');
        $this->loader->addAction('wp_enqueue_scripts', $plugin_public, 'enqueueScripts');
        $this->loader->addAction('plugins_loaded', $page_templater, 'loadAllSettingsAndFilters');
        $this->loader->addAction('admin_init', $page_or_post_creator, 'createCasetrackerPostsOrPages');
        $this->loader->addAction('init', $register_lawyer_handler, 'addNewLawyer');
        $this->loader->addAction('user_register', $register_lawyer_handler, 'saveLawyerExtraDataAsMeta');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since 1.0.0
     * @access private
     * @return void
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since  1.0.0
     * @return string    The name of the plugin.
     */
    public function getPluginName()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since  1.0.0
     * @return HooksLoader    Orchestrates the hooks of the plugin.
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since  1.0.0
     * @return string    The version number of the plugin.
     */
    public function getVersion()
    {
        return $this->version;
    }
}
