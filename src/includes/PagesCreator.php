<?php
namespace DevignersPlace\CaseTracker\Includes;

/**
 * A class to create required pages and assign their templates
 *
 * @category   Plugins
 * @package    PluginNameSpace
 * @subpackage PluginNameSpace/Includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class PageOrPostCreator
{
    /**
     * A Unique Identifier for the Plugin
     */
    protected $plugin_slug;

    /**
     * A Unique Identifier for the Plugin
     */
    protected $plugin_version;


    /**
     * Initialize the class constructor
     */
    public function __construct($plugin_name, $plugin_version)
    {
        $this->plugin_slug = $plugin_name;
        $this->plugin_version = $plugin_version;
    }

    /**
     * Create the page for the Register Lawyer
     *
     * @return void
     */
    public function createCasetrackerPostsOrPages()
    {
        $page = get_page_by_path('register-lawyer');
        $new_page_template = '../publicdir/templates/registration-form.php';

        if (!isset($page)) :
            $new_page_id = wp_insert_post(
                array (
                    'post_type'     => 'page',
                    'post_title'    => 'Lawyer Registration',
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'guid'          => 'register-lawyer',
                    'post_name'     => 'register-lawyer'
                )
            );
            if (!empty($new_page_template)) {
                update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
            }
        endif;
        $this->createCaseAdditionPage();
    }

    /**
     * Create the page for the case addition as cpt
     *
     * @return void
     */
    public function createCaseAdditionPage()
    {
        $page = get_page_by_path('add-court-case');
        $new_page_template = '../publicdir/templates/add-case.php';

        if (!isset($page)) :
            $new_page_id = wp_insert_post(
                array (
                    'post_type'     => 'page',
                    'post_title'    => 'Add a New Case',
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'guid'          => 'add-court-case',
                    'post_name'     => 'add-court-case'
                )
            );
            if (!empty($new_page_template)) {
                update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
            }
        endif;
    }
}
