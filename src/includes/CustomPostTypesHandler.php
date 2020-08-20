<?php
namespace DevignersPlace\CaseTracker\Includes;

/**
 * The Class to register all custom post types
 *
 * @category   Plugins
 * @package    Mtii_Utilities
 * @subpackage Mtii_Utilities/includes
 * @author     Josbiz - Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class CustomPostTypesHandler
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
     * Initialize the plugin by setting filters and administration functions
     */
    public function __construct($plugin_name, $plugin_version)
    {
        $this->plugin_slug = $plugin_name;
        $this->plugin_version = $plugin_version;
    }

    public function registerCustomPostTypes()
    {
        $this->registerCourtCaseAsCpt();
    }

    /**
     * Register a Custom Post Type for holding users about info
     *
     * @return void
     * @since  1.0
     * @author Michael Adewumi
     */
    public function registerCourtCaseAsCpt()
    {
        $icon_svg = '<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
        width="100.000000pt" height="100.000000pt" viewBox="0 0 100.000000 100.000000"
        preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,100.000000) scale(0.100000,-0.100000)"
        fill="#a0a5aa" stroke="none">
        <path d="M533 882 c-123 -77 -140 -95 -128 -132 14 -44 49 -36 171 41 131 82
        144 93 144 119 0 23 -26 50 -48 50 -9 0 -72 -35 -139 -78z"/>
        <path d="M603 772 c-50 -31 -95 -60 -98 -64 -6 -6 76 -155 108 -195 11 -15 14
        -13 139 64 l87 54 -60 99 c-33 55 -66 100 -72 99 -7 0 -53 -26 -104 -57z"/>
        <path d="M290 494 c-124 -69 -231 -132 -237 -141 -19 -24 -16 -50 8 -74 14
        -14 29 -19 45 -16 30 5 443 278 444 292 0 6 -8 22 -18 37 l-18 27 -224 -125z"/>
        <path d="M753 542 c-123 -77 -140 -95 -128 -132 14 -44 50 -36 163 35 123 77
        152 101 152 126 0 22 -26 49 -48 49 -9 0 -72 -35 -139 -78z"/>
        <path d="M512 128 c-7 -7 -12 -29 -12 -50 l0 -38 230 0 230 0 0 38 c0 21 -5
        43 -12 50 -17 17 -419 17 -436 0z"/></g></svg>';
        $labels = array(
            'name'               => _x('Court Case', 'Post Type General Name', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'singular_name'      => _x('Court Case', 'Post Type Singular Name', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'menu_name'          => __('Court Case', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'parent_item_colon'  => __('Parent Court Case:', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'all_items'          => __('All Court Cases', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'view_item'          => __('View Court Case', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'add_new_item'       => __('Add New Court Case', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'add_new'            => __('New Court Case', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'edit_item'          => __('Edit Court Case', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'update_item'        => __('Update Court Case', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'search_items'       => __('Search Court Cases', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'not_found'          => __('No Court Case found', JOSBIZ_PLUGIN_TEXT_DOMAIN),
            'not_found_in_trash' => __('No Court Case found in Trash', JOSBIZ_PLUGIN_TEXT_DOMAIN)
        );
        $args   = array (
            'labels'            => $labels,
            'map_meta_cap'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'has_archive'       => true,
            'public'            => true,
            'show_ui'           => true,
            'rest_base'         => 'securecase',
            'supports'          => array('title', 'editor', 'author', 'custom-fields'),
            'taxonomies'        => array('category', 'post_tag'),
            'rewrite'           => array('slug' => _x('court-case', 'URL slug', JOSBIZ_PLUGIN_TEXT_DOMAIN)),
            'menu_icon'         => 'data:image/svg+xml;base64,' . base64_encode($icon_svg)
        );
        register_post_type('josbiz-court-case', $args);
        flush_rewrite_rules();
    }
}
