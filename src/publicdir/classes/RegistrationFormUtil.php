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
     * Class constructor to set error codes to a static property
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct()
    {
        self::$wp_error = $this->getErrorCodes();
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
     * n instance of the WP_Error Class
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
        echo $error_class;
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

            if ($error_code==="username_invalid" && !in_array($error_as_array[0], $error_codes) && count($error_codes)>0) {
                $error_string .= " You can only use @,dash,underscore,and period in you username";
            }
        }

        if ($error_string!="") {
            echo '<span class="error-string"><strong>'.$error_string.'</strong></span>';
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
}
