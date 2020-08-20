<?php
namespace DevignersPlace\CaseTracker\PublicDir\Classes;

/**
 * The public-facing functionality of the plugin.
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage DevignersPlace\CaseTracker\PublicDir\Classes;
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */

class PublicTasks
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
     * Load the url of the google font to be used
     *
     * @since 1.0.0
     * @return string Url of the google font to fetch
     */
    private function caseTrackerFontsUrl()
    {
        $fonts_url = '';
        /*
         * Translators: If there are characters in your language that are not
         * supported by Comfortaa, please translate this to 'off'.
         *
         * Note: Do not translate into your own language.
         */
        $Comfortaa = _x('on', 'Comfortaa: on or off', JOSBIZ_PLUGIN_TEXT_DOMAIN);

        $font_families = array();
        if ( 'off' !== $Comfortaa ) {
            $font_families[] = 'Comfortaa:wght@300;400;700&display=swap';
        }

        $query_args = array(
            'family' => implode('|', $font_families),
            'subset' => 'latin-ext',
        );
        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css2?family=');

        return esc_url_raw($fonts_url);
    }

    /**
     * Add preconnect for Google Fonts.
     *
     * @since 1.0.0
     *
     * @param array  $urls           URLs to print for resource hints.
     * @param string $relation_type  The relation type the URLs are printed.
     * @return array $urls           URLs to print for resource hints.
     */
    public function caseTrackerResourceHint($urls, $relation_type)
    {
        if (wp_style_is($this->plugin_name.'-font-loader', 'queue') && 'preconnect' === $relation_type) {
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
        }
        return $urls;
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
         * defined in PluginsLoader as all of the hooks are defined
         * in that particular class.
         *
         * The PluginsLoader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            'fontawesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css',
            '',
            '5.14.0',
            'all'
        );

        wp_enqueue_style(
            $this->plugin_name.'-font-loader',
            $this->caseTrackerFontsUrl(),
            array(),
            null
        );
        wp_enqueue_style(
            $this->plugin_name.'-public-main-style',
            JOSBIZ_PLUGIN_PUBLIC_DIR .'css/public-style.css',
            array(),
            101,
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
        wp_register_script(
            $this->plugin_name.'-registration-script',
            JOSBIZ_PLUGIN_PUBLIC_DIR.'js/registration-script.js',
            array('jquery'),
            $this->version,
            true
        );
        wp_register_script(
            $this->plugin_name.'-notifier-script',
            JOSBIZ_PLUGIN_PUBLIC_DIR.'js/notification-modal.js',
            array('jquery'),
            $this->version,
            true
        );
        //if (is_page('register-lawyer')) {
            wp_enqueue_script($this->plugin_name.'-registration-script');
        //}
        if (is_page('add-court-case')) {
            wp_enqueue_script($this->plugin_name.'-notifier-script');
        }
    }
}
