<?php

namespace DevignersPlace\CaseTracker\Tests\Integration;

use DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase;
use DevignersPlace\CaseTracker\Includes\PostGlobalVariableGetter;
use DevignersPlace\CaseTracker\Includes\InputNames;
use DevignersPlace\CaseTracker\Includes\ErrorHandler;
use Mockery;
use Brain\Monkey;

/**
 * Class TestRegisterLawyer
 *
 * Unit tests for the class that registers Lawyer
 *
 * @package DevignersPlace\CaseTracker\Tests\Unit;
 */
class AddCourseCaseTest extends IntegrationTestCase
{

    /**
     * controller to tell if we want the POST mock to be empty
     */
    protected $allow_empty;

    /**
     * To be used Internally to control the WPError mock returning a list of error codes
     * when we need to test for a case where there is an error or an empty array when we
     * need to have a case where there are no errors.
     */
    protected $allow_case_track_errors = true;

    /**
     * To be used Internally to control the POST super global mock for when it ia
     * needed to be an empty array and when it is required to have values.
     */
    protected $post_is_unset;

    /**
     * The Lawyer id use for
     */
    protected $lawyer_id;

    /**
     * Prepares the test environment before each test.
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Checks if the input error class is set if the POST is not ser
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\addInputClassErrorIfEmpty
     */
    public function testUnauthorisedUser()
    {
        $this->post_is_unset = false;
        $post_global_variable = $this->mockPOSTSuperGlobal(true);
        $case_adder = new AddCourtCase(wp_get_current_user(), new \WP_Error);

        $case_adder->setUp($post_global_variable);

        $this->AssertTrue($case_adder::$user_unauthorised);
    }

    public function testAddCaseThrowError()
    {
        $user_id = $this->factory->user->create(array( 'role' => 'administrator' ));
        $this->post_is_unset = false;
        $post_global_variable = $this->mockPOSTSuperGlobal(true);
        $case_adder = new AddCourtCase(wp_set_current_user($user_id), new \WP_Error);

        $case_adder->setUp($post_global_variable);

        $all_inputs = $case_adder::$input_names;

        $looped = 'no';
        $count = 0;

        foreach ($all_inputs as $input_name) {
            $error_string = ucwords(str_replace("-", " ", $input_name))." cannot be blank.";
            $error_id = "empty_".str_replace("-", "_", $input_name);
            $this->AssertSame($error_string, $case_adder::$wp_error->get_error_message($error_id));
            $count++;
            if ($count === count($all_inputs)) {
                $looped = 'yes';
            }
        }
        $this->AssertEquals($looped, 'yes');
    }

    /**
     * Test if case is successfully created if all conditions are met
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\setFormValuesToClassProp
     */
    public function testAddCaseAsPost()
    {
        $user_id = $this->factory->user->create(array( 'role' => 'administrator' ));
        $this->post_is_unset = false;
        $post_global_variable = $this->mockPOSTSuperGlobal(false);
        $case_adder = new AddCourtCase(wp_set_current_user($user_id), new \WP_Error);

        $_SERVER["REQUEST_URI"] = "https://case-tracker.local/add-course-case";
        $case_adder->setUp($post_global_variable);

        $all_inputs = $case_adder::$input_names;
        $admin_email = get_the_author_meta('user_email', $user_id);
        $admin_phone =  get_the_author_meta('phone_number', $user_id);
        $lawyer_full_name = get_the_author_meta('first_name', $this->lawyer_id).
            " ".get_the_author_meta('last_name', $this->lawyer_id);
        $lawyer_email =  get_the_author_meta('user_email', $this->lawyer_id);
        $form_post = $case_adder::$post_global_var;

        $this->AssertTrue(is_int($case_adder::$court_case_id));
        $this->AssertSame(4, $case_adder::$court_case_id);

        //check if email to be sent has the right information.
        $this->AssertSame($case_adder::$admin_email, $admin_email);
        $this->AssertSame($case_adder::$admin_phone_number, $admin_phone);
        $this->AssertSame($case_adder::$lawyer_user_id, $this->lawyer_id);
        $this->AssertSame($case_adder::$lawyer_name, $lawyer_full_name);
        $this->AssertSame($case_adder::$lawyer_email_address, $lawyer_email);

        $email_to_send = "Hello ".$lawyer_full_name.",<br /><br />".
        "You have been assigned as the lawyer for the case tagged "
        .$form_post['case-description'].
        ". You can check the details below for more information;<br /><br />".
        "<strong>Case Suit Number: ".$form_post['suit-number']."</strong><br />".
        "<strong>Case starting Date: ".$form_post['case-start-date']."</strong><br />".
        "<strong>Court Name: ".$form_post['court-name']."</strong><br />".
        "<strong>Court Address: ".$form_post['court-address']."</strong><br /><br />".
        "Please do reach out to the administrator using the number ".$admin_phone.
        " or via email at ".$admin_email." for further clarifications. ".
        "Thanks in advance as you honour this call.";
        $this->AssertSame($case_adder::$lawyer_email_body, $email_to_send);
    }

    public function createAddCourtCaseMock($role, $allow_case_track_errors = true, $unset_post = false)
    {
        $wp_user = $this->mockWPUser($role);
        $wp_user->get_user_roles();

        $this->allow_case_track_errors = $allow_case_track_errors;
        $this->post_is_unset = $unset_post;
        $wp_error_mock = $this->mockWPError();

        $add_court_case_mock = Mockery::mock(
            'DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase[isFormSubmit, noWPError]',
            [$wp_user, $wp_error_mock]
        )->makePartial();

        $add_court_case_mock->shouldReceive([
            'isFormSubmit' => true,
            'noWPError' => false
        ]);

        return $add_court_case_mock;
    }


}
