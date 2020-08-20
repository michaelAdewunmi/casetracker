<?php

namespace DevignersPlace\CaseTracker\Includes;

/**
 * The Class to take care of registering Lawyer on the server
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage CaseTracker/Includes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class RegisterLawyer
{
    /**
     * The first name of the Lawyer to be registered
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $first_name;

    /**
     * The last name of the Lawyer to be registered
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $last_name;

    /**
     * The Gender of the Lawyer to be registered
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $gender;

        /**
     * The username of the Lawyer to be registered
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $username;

    /**
     * The email of the Lawyer to be registered
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $email;

    /**
     * The phone of the Lawyer to be registered
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $phone;

    /**
     * The password of the Lawyer to be registered
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $password;

    /**
     * A second password to confirm that the first password is what is intended
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $confirm_password;

    /**
     * A string which is to be used to authenticate the registration request
     *
     * @since  1.0.0
     * @access public
     * @var   string
     */
    public static $registration_nonce;

    /**
     * holds the POST super global variable
     *
     * @since  1.0.0
     * @access private
     * @var   string
     */
    private $the_post_super_global;

    /**
     * An Instance of the WordPress WP_Roles
     *
     * @since  1.0.0
     * @access private
     * @var   bool
     */
    private static $wp_roles;

    /** An Instance of the WordPress WP_Error sitting internally as a property
     *
     * @since  1.0.0
     * @access private
     * @var   bool
     */
    private static $wp_error;

    /**
     * ID of registered user
     *
     * @since  1.0.0
     * @access private
     * @var   int
     */
    private $created_user_id;

    /**
     * Serves as a controller to tell if Error Handler should be reset.
     * This is to enable us clear any previous error
     *
     * @since  1.0.0
     * @access private
     * @var   bool
     */
    private static $reset_error_handler;

    /**
     * Class Construct
     *
     * @param array  $form_values An instance of PostGlobalVaribaleGetter which obtaines
     *                  the post super global variable.
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct(PostGlobalVariableGetter $form_values, \WP_Roles $wp_roles, \WP_Error $wp_error)
    {
        // Make all required classes native to this parent class to enable for easy unit testing.
        $this->the_post_super_global = $form_values::getPostSuperGlobal();
        self::$wp_roles = $wp_roles;
        self::$wp_error = $wp_error;

        // Set Form values to Class properties. Seem Useless but I'm using it anyways
        $this->setFormValuesToClassProp($this->the_post_super_global);
    }

    /**
     * checks if the lawyer role is in the list of WordPress roles.
     *
     * @param array  $wp_roles An instance of WordPress WP_Roles
     *
     * @since  1.0.0
     * @access public
     * @return bool
     */
    public static function isLawyerRolePresent(\WP_Roles $wp_roles)
    {
        return $wp_roles->is_role('josbiz-casetracker-lawyer');
    }

    /**
     * A Method to set the private form_values to the various class properties
     *
     * @param array  $form_values An instance of PostGlobalVaribaleGetter which holds
     *                  the POST super global variable.
     * @since  1.0.0
     * @access private
     * @return void
     */
    private function setFormValuesToClassProp($form_values)
    {
        $input_names = InputNames::$registration_input_names;

        self::$username = $form_values[$input_names[0]] ?? "";
        self::$email = $form_values[$input_names[1]] ?? "";
        self::$first_name = $form_values[$input_names[2]] ?? "";
        self::$last_name = $form_values[$input_names[3]] ?? "";
        self::$gender = $form_values[$input_names[4]] ?? "";
        self::$phone = $form_values[$input_names[5]] ?? "";
        self::$password = $form_values[$input_names[6]] ?? "";
        self::$confirm_password = $form_values[$input_names[7]] ?? "";
        self::$registration_nonce = $form_values["lawyer-registration-nonce"] ?? "";
    }

    /**
     *  A boolean to determine wether the page is the add-lawyers page
     *
     * @since  1.0.0
     * @access public
     * @return bool
     */
    public function isRegisterPage()
    {
        return is_page('register-lawyer');
    }

    /**
     * A Method to Authenticate form inputs
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function validateFormValues()
    {
        $error_handler_instance = ErrorHandler::getInstance(self::$wp_error, self::$reset_error_handler);
        $error_handler = $error_handler_instance::getErrorsObject();

        if (username_exists(self::$username)) {
            $error_handler->add('username_unavailable', __('This Username is already Taken'));
        }

        if (!validate_username(self::$username)) {
            $error_handler->add('username_invalid', __('Invalid Username!'));
        }

        if (!is_email(self::$email)) {
            $error_handler->add('email_invalid', __('Please Input a valid Email'));
        }

        if (email_exists(self::$email)) {
            $error_handler->add('email_used', __('Email already Registered'));
        }

        if (self::$password !== self::$confirm_password) {
            $error_handler->add('passwords_mismatch', __('Passwords do not match'));
        }

        foreach (InputNames::$registration_input_names as $input_name) {
            $form_post = $this->the_post_super_global;
            if (!isset($form_post[$input_name]) || trim($form_post[$input_name])=="") {
                $error_id = "empty_".$input_name;
                $error_handler->add($error_id, InputNames::$reg_input_names_errors[$input_name]);
            }
        }
    }

    /**
     * Handle platform specific authentication decide if user can be registered
     *
     * @since  1.0.0
     * @access public
     * @return boolean
     */
    public function allowLawyerRegistration()
    {
        return wp_verify_nonce(self::$registration_nonce, 'casetracker-lawyer-registration-nonce');
    }

    /**
     * Add lawyer as a New User to the Database
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function addNewLawyer()
    {
        if (!self::isLawyerRolePresent(self::$wp_roles)) {
            return;
        }
        if ($this->allowLawyerRegistration()) {
            self::$reset_error_handler = true;
            $this->validateFormValues();
            $form_errors = (ErrorHandler::getErrorsObject())->get_error_messages();
            if (empty($form_errors)) {
                $this->addUserToDb();
                $this->notifyNewUser();
            }
        }
    }

    /**
     * Add User to wordpress Database
     *
     * @since  1.0.0
     * @access private
     * @return void
     */
    private function addUserToDb()
    {
        $this->created_user_id = wp_insert_user(
            array(
                'user_login'        => self::$username,
                'user_pass'         => self::$password,
                'user_email'        => self::$email,
                'first_name'        => self::$first_name,
                'last_name'         => self::$last_name,
                'user_registered'   => date('Y-m-d H:i:s'),
                'phone'             => self::$phone,
                'role'              => 'josbiz-casetracker-lawyer'
            )
        );
    }

    /**
     * Fired during the user_register hook. For saving extra Lawyer data as User Meta.
     *
     * @param $user_id ID of the created user which is fed in during the do_action for the user_register hook
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function saveLawyerExtraDataAsMeta($user_id)
    {
        $post_values = PostGlobalVariableGetter::getPostSuperGlobal();

        $gender = $post_values['lawyer-gender'] ?? "";
        $phone = $post_values['lawyer-phone'] ?? "";
        $first_name = $post_values['lawyer-first-name'] ?? "";
        $last_name = $post_values['lawyer-last-name'] ?? "";

        if (!empty($phone) && !empty($gender) && !empty($first_name) && !empty($last_name)) {
            update_user_meta($user_id, 'phone_number', $phone);
            update_user_meta($user_id, 'gender', $gender);
            update_user_meta($user_id, 'first_name', $first_name);
            update_user_meta($user_id, 'last_name', $last_name);
        }
    }

    /**
     * Send email to newly registered user
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function notifyNewUser()
    {
        $created_user_id = $this->getCreatedLawyerId();
        if ($created_user_id) {
            wp_new_user_notification($created_user_id);
            wp_mail(
                self::$email,
                "You Have been registered as a Lawyer.",
                "You have been registered as a Lawyer. \n Your login details is as shown below \n\n".
                "Username: ".self::$username."\nPassword: ".self::$password."\n\n"
            );
            $this->resetAllData();
            $this->redirectToSuccessPage($created_user_id);
        }
    }

    /**
     * Redirects to the success Page after user creation and notification  via email
     *
     * @param $user_id ID of the created user which is fed in during the do_action for
     *          the user_register hook
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function redirectToSuccessPage($created_user_id)
    {
        $user_succesful_query = array(
            "done" => 1,
            "u" => $created_user_id,
            "pass" => wp_create_nonce('allow-user-view'),
        );
        $redirect_to = add_query_arg($user_succesful_query, $_SERVER["REQUEST_URI"]);
        wp_redirect($redirect_to);
        exit;
    }

    /**
     * Resets POST data
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function resetAllData()
    {
        unset($_POST);
        self::$wp_error = null;
        ErrorHandler::getInstance(new \WP_Error, true);
    }

    /**
     * Retrieve the user id of the created lawyer
     *
     * @since  1.0.0
     * @access public
     * @return int
     */
    public function getCreatedLawyerId()
    {
        return $this->created_user_id;
    }
}
