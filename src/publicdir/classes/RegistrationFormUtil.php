<?php

namespace DevignersPlace\CaseTracker\PublicDir\Classes;

use DevignersPlace\CaseTracker\Includes\ErrorHandler;
use DevignersPlace\CaseTracker\Includes\PostGlobalVariableGetter;

class RegistrationFormUtil
{

    /**
     * A property to hold the wp_error string
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $wp_error;

    /**
     * The string added to the first name input class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $fname_error_class;

    /**
     * The value of First name input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $fname_value;

    /**
     * Output String on the event of a First Name Error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $fname_error_string;

    /**
     * The string added to the last name input class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $lname_error_class;

    /**
     * The value of Last name input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $lname_value;

    /**
     * Output String on the event of a Last Name Error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $lname_error_string;

    /**
     * The string added to the Gender select class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $gender_error_class;

    /**
     * The value of Gender select input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $gender_value;

    /**
     * The string added to the username input class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $username_error_class;

    /**
     * The value of Username input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $username_value;

    /**
     * Output String on the event of a Username Error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $username_error_string;

    /**
     * The string added to the Email input class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $email_error_class;

    /**
     * The value of Email input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $email_value;

    /**
     * Output String on the event of an Email Error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $email_error_string;

    /**
     * The string added to the Phone input class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $phone_error_class;

    /**
     * The value of Phone input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $phone_value;

    /**
     * Output String on the event of a Phone Number Error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $phone_error_string;

    /**
     * The string added to the Password input class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $pw_error_class;

    /**
     * The value of Password input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $pw_value;

    /**
     * Output String on the event of a Password Error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $pw_error_string;

    /**
     * The string added to the Password confirm input class when there is an error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $pw_confirm_error_class;

    /**
     * The value of Password input from the POST input
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $pw_confirm_value;

    /**
     * Output String on the event of a confirmation Password Error
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $pw_confirm_error_string;

    /**
     * Email of the Created User (if it is a redirect from the user creation class)
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $created_user_email;

    /**
     * The Full Name of the Created User (if it is a redirect from the user creation class)
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $created_user_full_name;

    /**
     * Phone Number of the Created User (if it is a redirect from the user creation class)
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $created_user_phone;

    /**
     * Username of the Created User (if it is a redirect from the user creation class)
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $created_user_login;

    /**
     * Class constructor to set error codes to a static property
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct()
    {
        self::$wp_error = $this->getErrorCodes();
        $this->handleInputsClassError();
        $this->handlePersistentPostValues();
        if (self::isUserCreationRedirect()) {
            self::setCreatedUserInfo();
        }
    }

    /**
     * Get WP_Error Object from from the ErrorHandler class
     *
     * @since  1.0.0
     * @access private
     * @return \WP_Error An instance of the WP_Error Class
     */
    private function getErrorCodes(): \WP_Error
    {
        ErrorHandler::getInstance(new \WP_Error, false);
        return ErrorHandler::getErrorsObject();
    }


    /**
     * Get Error codes from the
     *
     * @since  1.0.0
     * @access private
     * @return \WP_Error A
     *
     * An instance of the WP_Error Class
     */
    public function getFormErrors()
    {
        $errors = self::$wp_error->get_error_codes();
        //var_dump($errors);
        // echo self::$wp_error->get_error_message("empty_lawyer-password-confirm");
        if ($errors) {
            echo '<p class="error-heading">Please Fix the Inputs Marked in Red</p>';
        }
    }

    /**
     * Get Error class to input when there is an error
     *
     * @param array  $input_name_or_error_id A string containing comma separated error pertaining
     *                  to a certain input.
     * @since  1.0.0
     * @access private
     * @return void
     */
    public function addErrorClassOnError($input_name_or_error_id)
    {
        if (!$input_name_or_error_id) {
            return;
        }
        $error_as_array = explode(", ", $input_name_or_error_id);

        $error_class = "";
        foreach ($error_as_array as $error_code) {
            if (in_array($error_code, self::$wp_error->get_error_codes())) {
                $error_class = "errored";
            }
        }
        return $error_class;
    }

    /**
     * Prints the error string to be displayed on the front end for an input with error value
     *
     * @param array  $input_name_or_error_id A string containing comma separated error pertaining
     *                  to a certain input.
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function handleErrorsIfAny($input_name_or_error_id)
    {
        $error_as_array = explode(", ", $input_name_or_error_id);
        $error_codes = self::$wp_error->get_error_codes();
        $error_string = "";

        foreach ($error_as_array as $error_code) {
            // Notice below that even thought we are going through a loop, We are
            // actually overriding $error_string each time. This is because the higher
            // the string with a higher index in the $error_as_array ARRAY has a higher
            // precedence and can sum up the total errors. This means the order in which
            // the arguments are supplied in registration_form.php matters a lot.
            if (in_array($error_code, $error_codes)) {
                $error_string = self::$wp_error->get_error_message($error_code);
            }

            if ($error_code==="username_invalid" && !in_array($error_as_array[0], $error_codes)
                && in_array($error_code, $error_codes)&& count($error_codes)>0
            ) {
                $error_string .= " You can only use @,dash,underscore,and period in you username";
            }
        }

        if ($error_string!="") {
            return '<span class="error-string"><strong>'.$error_string.'</strong></span>';
        }
    }

    /**
     * Get the values in the POST input handled by the PostGlobalVariableGetter class
     *
     * @param array  $input_name The Name of the input
     * @since  1.0.0
     * @access public
     * @return string value if the input as it is in the $_POST super global
     */
    public function getInputValue($input_name)
    {
        return (PostGlobalVariableGetter::$post_variable)[$input_name] ?? null;
    }

    public function handleInputsClassError()
    {
        self::$fname_error_class = $this->addErrorClassOnError('empty_lawyer-first-name');
        self::$lname_error_class = $this->addErrorClassOnError('empty_lawyer-last-name');
        self::$gender_error_class = $this->addErrorClassOnError('empty_lawyer-gender');
        self::$email_error_class = $this->addErrorClassOnError('email_invalid, email_used, empty_lawyer-email');
        self::$phone_error_class = $this->addErrorClassOnError('empty_lawyer-phone');
        self::$pw_error_class = $this->addErrorClassOnError('passwords_mismatch, empty_lawyer-password');
        self::$pw_confirm_error_class = $this->addErrorClassOnError(
            'passwords_mismatch, empty_lawyer-password-confirm'
        );
        self::$username_error_class = $this->addErrorClassOnError(
            'empty_lawyer-username, username_invalid, username_unavailable'
        );
        self::$fname_error_string = $this->handleErrorsIfAny('empty_lawyer-first-name');
        self::$lname_error_string = $this->handleErrorsIfAny('empty_lawyer-last-name');
        self::$email_error_string = $this->handleErrorsIfAny('empty_lawyer-email, email_invalid, email_used');
        self::$phone_error_string = $this->handleErrorsIfAny('empty_lawyer-phone');
        self::$pw_error_string = $this->handleErrorsIfAny('empty_lawyer-password, passwords_mismatch');
        self::$username_error_string = $this->handleErrorsIfAny(
            'username_invalid, username_unavailable, empty_lawyer-username'
        );
        self::$pw_confirm_error_string = $this->handleErrorsIfAny(
            'empty_lawyer-password-confirm, passwords_mismatch'
        );
    }

    public function handlePersistentPostValues()
    {
        self::$fname_value = $this->getInputValue('lawyer-first-name');
        self::$lname_value = $this->getInputValue('lawyer-last-name');
        self::$gender_value = $this->getInputValue('lawyer-gender');
        self::$username_value = $this->getInputValue('lawyer-username');
        self::$email_value = $this->getInputValue('lawyer-email');
        self::$phone_value = $this->getInputValue('lawyer-phone');
        self::$pw_value = $this->getInputValue('lawyer-password');
        self::$pw_confirm_value = $this->getInputValue('lawyer-password-confirm');
    }

    public static function isUserCreationRedirect()
    {
        return isset($_REQUEST["done"]) && isset($_REQUEST["u"])
            && wp_verify_nonce($_REQUEST["pass"], 'allow-user-view');
    }

    public static function setCreatedUserInfo()
    {
        if (self::isUserCreationRedirect()) {
            $user_object = get_user_by('ID', $_REQUEST["u"]);
            $created_user_id = $user_object->data->ID;
            self::$created_user_email = $user_object->data->user_email;
            $created_user_first_name = get_the_author_meta('first_name', $created_user_id);
            $created_user_last_name = get_the_author_meta('last_name', $created_user_id);
            self::$created_user_full_name = $created_user_first_name." ".$created_user_last_name;
            self::$created_user_phone = get_the_author_meta('phone_number', $created_user_id);
            self::$created_user_login = $user_object->data->user_login;
        }
    }
}
