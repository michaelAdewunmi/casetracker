<?php

namespace DevignersPlace\CaseTracker\Tests\Unit;

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
class AddCourtCaseTest extends TestCase
{
    /**
     * Prepares the test environment before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

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
     * Controller to determine if the add method in the WP_Error Mock should add an errror for reference
     */
    protected $skip_add_error = true;

    /**
     * To hold the error due to the suite_number input
     */
    protected $empty_suit_number;


    /**
     * To hold the error due to the assigned lawyer input
     */
    protected $empty_lawyer_assigned;

    /**
     * To hold the error due to the court name input
     */
    protected $empty_court_name;

    /**
     * To hold the error due to the court address input
     */
    protected $empty_court_address;

    /**
     * To hold the error due to the case Description
     */
    protected $empty_case_description;

    /**
     * To hold the error due to the case Start Date
     */
    protected $empty_case_start_date;


    /**
     * Test that Current user returns false if not an administrator or mini-administrator
     *
     */
    public function testIsCurrentUserAllowed()
    {
        $court_case_instance_one = $this->getCourtCaseInstance([], true);
        $this->AssertFalse($court_case_instance_one::isCurrentUserAllowed());

        $court_case_instance_two = $this->getCourtCaseInstance(['josbiz-casetracker-lawyer'], true);
        $this->AssertFalse($court_case_instance_two::isCurrentUserAllowed());

        $court_case_instance_three = $this->getCourtCaseInstance(['administrator'], true);
        $this->AssertTrue($court_case_instance_three::isCurrentUserAllowed());

        $court_case_instance_four = $this->getCourtCaseInstance(['mini-administrator'], true);
        $this->AssertTrue($court_case_instance_four::isCurrentUserAllowed());

        $court_case_instance_five = $this->getCourtCaseInstance(['mini-administrator', 'administrator'], true);
        $this->AssertTrue($court_case_instance_five::isCurrentUserAllowed());
    }

    /**
     * Test if input names have been saved as class properties
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\addInputNamesToClassProperties
     */
    public function testInputNamesAreClassProperties()
    {
        $court_case_instance = $this->getCourtCaseInstance(['mini-administrator'], true);
        $post_global_variable = $this->mockPOSTSuperGlobal(true);

        Monkey\Functions\expect('wp_verify_nonce')
        ->with('gdh35854hg96jsg', 'case-adder-nonce')
        ->andReturn(true);

        $court_case_instance->setUp($post_global_variable);
        $input_names_from_input_class = InputNames::$add_case_input_names;

        $this->AssertIsArray(AddCourtCase::$input_names);
        $this->AssertSame($input_names_from_input_class, AddCourtCase::$input_names);
    }


    /**
     * Test if input names have been saved as class properties when empty
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\setFormValuesToClassProp
     */
    public function testsetFormValuesSetToClassPropWhenEmpty()
    {
        $court_case_instance = $this->getCourtCaseInstance(['mini-administrator'], true);

        $post_global_variable = $this->mockPOSTSuperGlobal(true);
        $case_adder_vals = $post_global_variable->getPostSuperGlobal();

        $looped = 'no';
        $count = 0;

        foreach ($case_adder_vals as $key => $val) {
            $this->AssertSame($case_adder_vals[$key], $court_case_instance::$post_global_var[$key]);
            $count++;
            if ($count === count($case_adder_vals)) {
                $looped = 'yes';
            }
        }
        $this->AssertSame($looped, 'yes');
    }

    /**
     * Test if input names have been saved as class properties when filled
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\setFormValuesToClassProp
     */
    public function testsetFormValuesSetToClassPropWhenFilled()
    {
        $court_case_instance = $this->getCourtCaseInstance(['mini-administrator'], false);

        $post_global_variable = $this->mockPOSTSuperGlobal(false);

        Monkey\Functions\expect('wp_verify_nonce')
        ->with('gdh35854hg96jsg', 'case-adder-nonce')
        ->andReturn(true);
        $court_case_instance->setUp($post_global_variable);

        $case_adder_vals = $post_global_variable->getPostSuperGlobal();

        $looped = 'no';
        $count = 0;

        foreach ($case_adder_vals as $key => $val) {
            $post_global_array = $court_case_instance::$post_global_var;
            $this->AssertSame($case_adder_vals[$key], $post_global_array[$key]);
            $count++;
            if ($count === count($case_adder_vals)) {
                $looped = 'yes';
            }
        }
        $this->AssertEquals($looped, 'yes');
    }

    /**
     * Checks if the input error class is set if the value is empty
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\addInputClassErrorIfEmpty
     */
    public function testInputClassErrorSetForEmptyPostValue()
    {
        // Here, we move away from the default AddCourtCase class and we have to
        // Create a partical mock for the class so as to be able to test for a
        // case where the form is being submitted
        $mocked_case_adder = $this->createAddCourtCaseMock(['mini-administrator']);

        $post_global_variable = $this->mockPOSTSuperGlobal(true);

        $mocked_case_adder->setUp($post_global_variable);

        $all_inputs = $mocked_case_adder::$input_names;

        $looped = 'no';
        $count = 0;

        foreach ($all_inputs as $input) {
            $input_errored_class = $mocked_case_adder->getInputErrorClass($input);
            $this->AssertSame("errored", $input_errored_class);
            $count++;
            if ($count === count($all_inputs)) {
                $looped = 'yes';
            }
        }
        $this->AssertEquals($looped, 'yes');
    }

    /**
     * Checks if the input error class is set if the POST is not ser
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\addInputClassErrorIfEmpty
     */
    public function testInputClassErrorNotSetForUnsetPost()
    {
        // We Need a Mock of AddCourtCase here too
        $mocked_case_adder_two = $this->createAddCourtCaseMock(['mini-administrator'], false, true);

        $post_global_variable = $this->mockPOSTSuperGlobal(true);

        $mocked_case_adder_two->setUp($post_global_variable);

        $all_inputs = $mocked_case_adder_two::$input_names;
        $looped = 'no';
        $count = 0;

        foreach ($all_inputs as $input) {
            $input_errored_class = $mocked_case_adder_two->getInputErrorClass($input);
            $this->AssertSame("", $input_errored_class);
            $count++;
            if ($count === count($all_inputs)) {
                $looped = 'yes';
            }
        }
        $this->AssertEquals($looped, 'yes');
    }

    /**
     * Checks if the input error class is set if the POST is not set
     *
     * @covers DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase\addInputClassErrorIfEmpty
     */
    public function testAddCaseAsPost()
    {
        // We Need a Mock of AddCourtCase here too
        $mocked_case_adder = $this->createAddCourtCaseMock(['mini-administrator'], true, false);

        $post_global_variable = $this->mockPOSTSuperGlobal(true);

        $this->skip_add_error = false;

        $mocked_case_adder->setUp($post_global_variable);

        $all_inputs = $mocked_case_adder::$input_names;
        $looped = 'no';
        $count = 0;

        foreach ($all_inputs as $input_name) {
            $error_string = ucwords(str_replace("-", " ", $input_name))." cannot be blank.";
            $prop_name = "empty_".str_replace("-", "_", $input_name);
            $this->AssertSame($error_string, $this->{$prop_name});
            $count++;
            if ($count === count($all_inputs)) {
                $looped = 'yes';
            }
        }
        $this->AssertEquals($looped, 'yes');
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

    /**
     * Creates an Instance of the AddCourtCase class with different roles
     *
     * @param $roles An Array of user roles
     *
     */
    public function getCourtCaseInstance($roles, $allow_empty_post, $unset_post = false) : AddCourtCase
    {
        $this->allow_empty = $allow_empty_post;
        $this->post_is_unset = $unset_post;
        $wp_user = $this->mockWPUser($roles);
        $wp_user->get_user_roles();
        $wp_error_mock = $this->mockWPError();
        return new AddCourtCase($wp_user, $wp_error_mock);
    }

    /**
     * Creates a Mock of the WP_User class
     *
     * @param $roles_array An Array of user roles
     *
     */
    private function mockWPUser($roles_array) : \WP_User
    {
        $WPUser = Mockery::mock('WP_User');
        $WPUser->shouldReceive('get_user_roles')->andSet('roles', $roles_array);
        return $WPUser;
    }

    /**
     * Generates a mock for the POST super global using Mockery
     *
     * @return void
     */
    public function mockPOSTSuperGlobal($allow_empty)
    {
        $this->allow_empty = $allow_empty;
         // Set up the Content Getter mock.
        $global_post_var_mock = Mockery::mock(PostGlobalVariableGetter::class);

        $global_post_var_mock
                ->shouldReceive('getPostSuperGlobal')
                ->andSet('post_is_unset', $this->post_is_unset)
                ->andReturnUsing([$this, 'getMockPostGlobal']);
        return $global_post_var_mock;
    }

    /**
     * A Substitute for the POST super global
     *
     * @return void
     */
    public function getMockPostGlobal()
    {
        if ($this->post_is_unset) {
            return array();
        } else {
            return array(
                'suit-number'       => $this->allow_empty ? '' : 'The Suite Number',
                'lawyer-assigned'   => $this->allow_empty ? '' : 'Demmy Youung',
                'court-name'        => $this->allow_empty ? '' : 'Ogabi',
                'court-address'     => $this->allow_empty ? '' : 'Egbeda',
                'case-description'  => $this->allow_empty ? '' : 'A Thievery case',
                'case-start-date'   => $this->allow_empty ? '' : '20-04-2020',
                'case-adder-nonce'  => $this->allow_empty ? '' : 'gdh35854hg96jsg',
            );
        }
    }
}
