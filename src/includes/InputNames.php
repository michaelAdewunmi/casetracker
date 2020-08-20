<?php

namespace DevignersPlace\CaseTracker\Includes;

class InputNames
{
    /**
     * Instances of InputNames class and its subclasses.
     *
     * @var array
     */
    private static $instances = [];

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
     * A method used to get the Singleton's instance.
     *
     * @since  1.0.0
     * @access public
     * @return ErrorHandler
     */
    public static function getInstance() : InputNames
    {
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
     * An Array of Input Names in the registration form
     *
     * @since  1.0.0
     * @access public
     * @return ErrorHandler
     */
    public static $registration_input_names = array (
        'lawyer-username', 'lawyer-email', 'lawyer-first-name', 'lawyer-last-name',
        'lawyer-gender', 'lawyer-phone', 'lawyer-password', 'lawyer-password-confirm'
    );

    /**
     * An Array of Input Names for the add case page
     *
     * @since  1.0.0
     * @access public
     * @return array
     */
    public static $add_case_input_names = array (
        'suit-number', 'lawyer-assigned', 'court-name',
        'court-address', 'case-description', 'case-start-date',
    );

    /**
     * An Associative Array of Input Names and Errors in the registration form
     *
     * @since  1.0.0
     * @access public
     * @return ErrorHandler
     */
    public static $reg_input_names_errors = array (
        'lawyer-username'           => 'Username Cannot be Blank.',
        'lawyer-email'              => 'Please Input a valid email.',
        'lawyer-first-name'         => 'First Name Cannot be Blank.',
        'lawyer-last-name'          => 'Last Name Cannot be Blank.',
        'lawyer-gender'             => 'Please Use the Appropriate Gender',
        'lawyer-phone'              => 'Phone Number is Invalid',
        'lawyer-password'           => 'Your Password is Invalid.',
        'lawyer-password-confirm'   => 'Confirmation Password is Invalid.'
    );
}
