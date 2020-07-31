<?php

namespace DevignersPlace\CaseTracker\Includes;

/**
 * This class handles and hold error in its static properties
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage CaseTracker/includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class ErrorHandler
{
    /**
     * Instances of ErrorHanlder class and its subclasses.
     *
     * @var array
     */
    private static $instances = [];

    /**
     * Holds the WP_ERROR class.
     *
     * @var WP_Error
     */
    private static $wp_error;

    /**
     * Class Concstruct
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * A method used to get the Singleton's instance.
     *
     * @since  1.0.0
     * @access public
     * @return ErrorHandler
     */
    public static function getInstance(\WP_Error $wp_error, $reset_error_handler = false) : ErrorHandler
    {
        if ($reset_error_handler) {
            self::$wp_error = $wp_error;
        } else {
            self::$wp_error = self::$wp_error ?? $wp_error;
        }
        // Note that here we use the "static" keyword instead of the actual
        // class name. In this context, the "static" keyword means "the name
        // of the current class". That detail is important because when the
        // method is called on the subclass, we want an instance of that
        // subclass to be created here.
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static;
        }
        return self::$instances[$subclass];
    }

    /**
     * Get the Errors WP_Error Object which can be used to set and get required errors
     *
     * @since  1.0.0
     * @access public
     * @return WP_Error
     */
    public static function getErrorsObject() : \WP_Error
    {
        return self::$wp_error;
    }
}
