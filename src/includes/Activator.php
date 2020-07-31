<?php
namespace DevignersPlace\CaseTracker\Includes;

/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage CaseTracker/includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class Activator
{
    /**
     * Run these codes during plugin activation
     *
     * This might be used for creating options tables and
     * doing other plugin specific functionalities towards
     * setting posts, pages, options default
     *
     * @since  1.0.0
     * @return void
     */
    public static function activate()
    {

        if (!wp_roles()->is_role('josbiz-casetracker-lawyer')) {
            $args = array(
                'read' => true, 'edit_posts' => true, 'edit_pages' => false, 'edit_others_posts' => false,
                'create_posts' => false, 'publish_posts' => false, 'delete_posts' => false, 'edit_themes' => false,
                'install_plugins' => false, 'update_plugin' => false, 'update_core' => false
            );

            add_role('josbiz-casetracker-lawyer', __('Case Tracker Lawyer'), $args);
        }
        return;
    }
}
