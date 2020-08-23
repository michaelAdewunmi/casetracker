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
     * Create a wordpress Page
     *
     * @return void
     */
    private function createPage($page, $page_template, $page_title, $page_guid)
    {
        if (!isset($page)) :
            $page_id = wp_insert_post(
                array (
                    'post_type'     => 'page',
                    'post_title'    => $page_title,
                    'post_content'  => '',
                    'post_status'   => 'publish',
                    'guid'          => $page_guid,
                    'post_name'     => $page_guid
                )
            );
            if (!empty($page_template)) {
                update_post_meta($page_id, '_wp_page_template', $page_template);
            }
        endif;
    }

    /**
     * Create the required pages for the Register Lawyer
     *
     * @return void
     */
    public function createCasetrackerPostsOrPages()
    {
        $this->createPage(
            get_page_by_path('register-lawyer'),
            '../publicdir/templates/registration-form.php',
            'Lawyer Registration',
            'register-lawyer'
        );
        $this->createPage(
            get_page_by_path('add-court-case'),
            '../publicdir/templates/add-case.php',
            'Add a New Case',
            'add-court-case'
        );
        $this->createPage(
            get_page_by_path('case-tracker-calendar'),
            '../publicdir/templates/case-tracker-calendar-view.php',
            'Case Tracker Calendar View',
            'case-tracker-calendar'
        );
    }
}
