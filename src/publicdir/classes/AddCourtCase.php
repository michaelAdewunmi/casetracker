<?php
namespace DevignersPlace\CaseTracker\PublicDir\Classes;

use DevignersPlace\CaseTracker\Includes\InputNames;
use DevignersPlace\CaseTracker\Includes\PostGlobalVariableGetter;
use DevignersPlace\CaseTracker\Includes\ErrorHandler;
use DevignersPlace\CaseTracker\Includes\TasksPerformer;

/**
 * Fired in the add-court-case page. Takes care of the business logic for adding a court case by the Admin
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage DevignersPlace\CaseTracker\PublicDir\Classes
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class AddCourtCase
{
    /**
     * The WP_User Object of the user visitng the page
     *
     * @since  1.0.0
     * @access public
     * @var \WP_User
     */
    public static $user_object;

    /**
     * Handler to tell if visiting user is authorised or not
     *
     * @since  1.0.0
     * @access public
     * @var bool
     */
    public static $user_unauthorised;

    /**
     * A Class Property to hold the WP_Error class
     *
     * @since  1.0.0
     * @access public
     * @var \WP_Error
     */
    public static $wp_error;

    /**
     * An Array of input names and the class to add when there is an error;
     *
     * @since  1.0.0
     * @access public
     * @var array
     */
    private static $inputs_errored_classes = array();

    /**
     * The post super global variable saved as a property
     *
     * @since  1.0.0
     * @access public
     * @var $_POST
     */
    public static $post_global_var;

    /**
     * The ID of the course case to be created or edited
     *
     * @since  1.0.0
     * @access public
     * @var int
     */
    public static $court_case_id;

    /**
     * The Input value for the Case's Suite Number (obtained from the POST super global)
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $suit_number;

    /**
     * A property to hold the Error if Suit Number is Empty
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $suit_number_error;

    /**
     * The Input value for the case's assigned lawyer (obtained from the POST super global)
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $lawyer_assigned;

    /**
     * A property to hold the Error if Assigned Lawyer is Empty
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $lawyer_assigned_error;

    /**
     * The Input value for the case's court name (obtained from the POST super global)
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $court_name;

    /**
     * A property to hold the Error if Court Name is Empty
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $court_name_error;

    /**
     * The Input value for the case's court address (obtained from the POST super global)
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $court_address;

    /**
     * A property to hold the Error if Court Address is Empty
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $court_address_error;

    /**
     * The Input value for the description of the case (obtained from the POST super global)
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $case_description;

    /**
     * A property to hold the Error if Court case description is Empty
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $case_description_error;

    /**
     * The Input value for the case's start date (obtained from the POST super global)
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $case_start_date;

    /**
     * A property to hold the Error if Court case description is Empty
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $case_start_date_error;

    /**
     * A property to hold the tags for the court case
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $case_tags;

    /**
     * All Input Names
     *
     * @since  1.0.0
     * @access public
     * @var array
     */
    public static $input_names;

    /**
     * Email Address of the Admin
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $admin_email;

    /**
     * Phone Number of Admin Creating the Course Case
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $admin_phone_number;


    /**
     * Name of the lawyer assigned to the created Court Case
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $lawyer_name;

    /**
     * The email address of the lawyer assigned to the created Court Case
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $lawyer_email_address;

    /**
     * The user ID of the lawyer assigned to the created Court Case
     *
     * @since  1.0.0
     * @access public
     * @var int
     */
    public static $lawyer_user_id;

    /**
     * The body of the email to be sent to the lawyer assigned to the created Court Case
     *
     * @since  1.0.0
     * @access public
     * @var string
     */
    public static $lawyer_email_body;

    /**
     * Class Construct
     *
     * @param $user     A WP_User class which holds the information about the user visiting the page
     * @param $wp_error A WP_Error class which will hold all errors from the form
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function __construct(\WP_User $user, \WP_Error $wp_error)
    {
        self::$user_object = $user;
        self::$wp_error = $wp_error;
    }

    /**
     * Run all Methods required for case addition
     *
     * @param $form_vals_holder Holds the super global POST array
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function setUp(PostGlobalVariableGetter $form_vals_holder)
    {
        if (!self::isCurrentUserAllowed()) {
            self::$user_unauthorised = true;
            return;
        }
        $this->addInputNamesToClassProperties();

        $this->setFormValuesToClassProp($form_vals_holder::getPostSuperGlobal());

        $this->addCaseAsPost();
    }

    /**
     * Checks if User is allowed to visit page. The Page where class is fired is
     * only restricted to administrators and mini-administrators
     *
     * @since  1.0.0
     * @access public
     * @return boolean
     */
    public static function isCurrentUserAllowed() : bool
    {
        $user = self::$user_object;
        return in_array('administrator', $user->roles) || in_array('mini-administrator', $user->roles);
    }

    /**
     * Add the array of input names as a property of the class
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    private function addInputNamesToClassProperties()
    {
        self::$input_names = InputNames::$add_case_input_names;

        //Set the Default Errored class for Inputs as empty string
        // to avoid any php errors of undefined index.
        foreach (self::$input_names as $input_name) {
            self::$inputs_errored_classes[$input_name] = '';
        }
    }

    /**
     * Set the each value in the form obtained from the POST super global
     * as a native class property
     *
     * @param $form_values The super global POST array fed in from class::setUp()
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function setFormValuesToClassProp($form_values)
    {
        self::$post_global_var = $form_values;
    }

    /**
     * Adds the errored class for the input if the input value is empty
     *
     * @param $input_name the input name to check for error
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function addInputClassErrorIfEmpty($input_name)
    {

        if ($this->isFormSubmit()) {
            $error_code = "empty_".str_replace("-", "_", $input_name);
            if (!empty(self::$post_global_var) && in_array($error_code, self::$wp_error->get_error_codes())) {
                self::$inputs_errored_classes[$input_name] = "errored";
            }
        }
    }

    /**
     * Determines if form is submitted by checking if POST super global is filled
     *
     * @since  1.0.0
     * @access public
     * @return bool
     */
    public function isFormSubmit()
    {
        $post_super_global = self::$post_global_var;
        $nonce = $post_super_global["case-adder-nonce"];
        return null !== $post_super_global && count($post_super_global)>0
            && wp_verify_nonce($nonce, 'case-adder-nonce');
    }

    /**
     * Determine if there are erros in the WP_Error global
     *
     * @since  1.0.0
     * @access public
     * @return string A String to tell that there is an error
     *
     */
    public function getFormErrors()
    {
        $errors = self::$wp_error->get_error_codes();
        if ($errors) {
            return '<p class="error-heading">Please Fix the Inputs Marked in Red</p>';
        }
    }

    /**
     * Gets the errored class for a particular input
     *
     * @param $input_name the input name to be checked for errors
     * @since  1.0.0
     * @access public
     * @return string
     */
    public function getInputErrorClass($input_name)
    {
        $this->addInputClassErrorIfEmpty($input_name);
        return self::$inputs_errored_classes[$input_name];
    }

    /**
     * Gets the errored class for a particular input
     *
     * @param $input_name the input name to be checked for errors
     * @since  1.0.0
     * @access public
     * @return string
     */
    public static function getInputErrorString($input_name)
    {
        $error_code = "empty_".str_replace("-", "_", $input_name);
        if (in_array($error_code, self::$wp_error->get_error_codes())) {
            $error_string = self::$wp_error->get_error_message($error_code);
            return '<span class="error-string"><strong>'.$error_string.'</strong></span>';
        }
    }

    /**
     * Validates the form values after submission
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function validateFormValues()
    {
        $error_handler = self::$wp_error;
        $form_post = self::$post_global_var;
        foreach (self::$input_names as $input_name) {
            if (!isset($form_post[$input_name]) || empty(trim($form_post[$input_name]))) {
                $error_id = "empty_".str_replace("-", "_", $input_name);
                $error_handler->add($error_id, ucwords(str_replace("-", " ", $input_name))." cannot be blank.");
            }
        }
    }

    /**
     * Calls the method to add course case as post if validation has been done without errors
     *
     * @since  1.0.0
     * @access public
     * @return string
     */
    private function addCaseAsPost()
    {
        if ($this->isFormSubmit()) {
            $this->validateFormValues();

            if ($this->noWPError()) {
                $this->saveCourtCaseInfoAsPost();
            }
        }
    }

    /**
     * Checks if there is an error in the WP_Error class
     * @param $input_name the input name to be checked for errors
     * @since  1.0.0
     * @access public
     * @return bool
     */
    public function noWPError()
    {
        return count(self::$wp_error->get_error_codes()) == 0;
    }

    /**
     * Get the Input value from POST super global if set
     *
     * @param $input_name the input name to be checked for errors
     * @since  1.0.0
     * @access public
     * @return string
     */
    public static function getInputPostValue($input_name)
    {
        return self::$post_global_var[$input_name] ?? "";
    }

    /**
     * Saves the court case as post into the wordpress database
     *
     * @since  1.0.0
     * @access public
     * @return string
     */
    private function saveCourtCaseInfoAsPost()
    {
        $form_post = self::$post_global_var;
        $sanitized_tag_array = array_map('esc_attr', json_decode($form_post['case-tags']));
        $args = array(
            'ID'                => null,
            'post_author'       => (self::$user_object)->ID,
            'post_title'        => sanitize_text_field($form_post['suit-number']),
            'post_content'      => sanitize_text_field($form_post['case-description']),
            'post_status'       => 'publish',
            'post_type'         => 'josbiz-court-case',
            'tags_input'        => $sanitized_tag_array,
            'meta_input'        => array (
                'case_start_date'     => sanitize_text_field($form_post['case-start-date']),
                'lawyer_assigned'     => sanitize_text_field($form_post['lawyer-assigned']),
                'court_name'          => sanitize_text_field($form_post['court-name']),
                'court_address'       => sanitize_text_field($form_post['court-address']),
                'case_end_date'       => "Case Ongoing",
                'case_closed'         => false,
            )
        );
        $post_id = wp_insert_post($args);
        if (is_wp_error($post_id)) {
            $error_args = array(
                "f" => 1,
                "all" => wp_create_nonce('view-pass')
            );
            $redirect_url = add_query_arg($error_args, $_SERVER["REQUEST_URI"]);
            echo '<script>window.location.href="'.$redirect_url.'"</script>';
        } else {
            self::$court_case_id = $post_id;
            $this->sendAssignedLawyerEmail();
            $error_args = array(
                "done" => 1,
                "all" => wp_create_nonce('view-pass'),
            );
            $redirect_url = add_query_arg($error_args, $_SERVER["REQUEST_URI"]);

            // Note: I Decided to echo a javascript redirect snippet instead of using wordpress
            // default <code>wp_redirect</code>. This is because I am trying to avoid the usual
            // "headers already sent" problem in case a plugin already echoed something. ALso,
            // this seem handy during integration test as it made me see the actual url
            // the user will be redirected to.
            echo '<script>window.location.href="'.$redirect_url.'"</script>';
        }
    }

    /**
     * Creates the email to be sent to the Assigned Lawyer
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function createLawyerEmailBody()
    {
        $admin_id = (self::$user_object)->ID;
        self::$admin_email = (self::$user_object)->user_email;
        self::$admin_phone_number = get_the_author_meta('phone_number', $admin_id);

        $form_post = self::$post_global_var;
        self::$lawyer_user_id = $form_post['lawyer-assigned'];
        self::$lawyer_name = TasksPerformer::getUserFullName(self::$lawyer_user_id);
        self::$lawyer_email_address = TasksPerformer::getUserEmail(self::$lawyer_user_id);

        self::$lawyer_email_body = "Hello ".self::$lawyer_name.",<br /><br />".
            "You have been assigned as the lawyer for the case tagged ".$form_post['case-description'].
            ". You can check the details below for more information;<br /><br />".
            "<strong>Case Suit Number: ".$form_post['suit-number']."</strong><br />".
            "<strong>Case starting Date: ".$form_post['case-start-date']."</strong><br />".
            "<strong>Court Name: ".$form_post['court-name']."</strong><br />".
            "<strong>Court Address: ".$form_post['court-address']."</strong><br /><br />".
            "Please do reach out to the administrator using the number ".self::$admin_phone_number.
            " or via email at ".self::$admin_email." for further clarifications. ".
            "Thanks in advance as you honour this call.";
    }


    /**
     * Send an Email to the Lawyer Assigned to the created case
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function sendAssignedLawyerEmail()
    {
        $this->createLawyerEmailBody();
        TasksPerformer::sendEmail(
            self::$lawyer_email_body,
            "You have been Assigned to a Case",
            "You have been Assigned to a Case",
            self::$lawyer_email_address
        );
    }

    /**
     * Determines whether the page about to be loaded is due to a redirect from the adding of court case.
     *
     * @since  1.0.0
     * @access public
     * @return bool
     */
    public static function isCaseAdderRedirect()
    {
        return isset($_REQUEST["done"]) || isset($_REQUEST["f"]);
    }


    /**
     * Dtermines if a Nonce is valid.
     *
     * @since  1.0.0
     * @access public
     * @return bool
     */
    public static function isAValidNonce()
    {
        return wp_verify_nonce($_REQUEST["all"], 'view-pass');
    }
}
