<?php

namespace DevignersPlace\CaseTracker\Includes;

/**
 * Set the Global Variable into a class property
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage DevignersPlace\CaseTracker\Includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class PostGlobalVariableGetter
{
    public static $post_variable;

    /**
     * Class Construct
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Set the static form_values properties to the POST super global
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public static function getPostSuperGlobal()
    {
        self::$post_variable = $_POST;
        return $_POST;
    }
}
