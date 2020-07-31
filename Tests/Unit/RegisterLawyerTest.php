<?php

namespace DevignersPlace\CaseTracker\Tests\Unit;

use DevignersPlace\CaseTracker\Includes\RegisterLawyer;
use DevignersPlace\CaseTracker\Includes\PostGlobalVariableGetter;
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
class RegisterLawyerTest extends TestCase
{
    /**
     * A controller to determine if we want values in post global
     * variable to be empty or filled
     *
     * @var bool
     */
    protected $allow_empty;

    /**
     * A controller to tell if lawyer roles is present or not
     *
     * @var bool
     */
    protected $is_role_present;

    /**
     * A Mock of the WP_Roles class needed for the test
     */
    protected $wp_roles_mock;

    /**
     * A Mock of the WP_Roles class needed for the test
     */
    protected $wp_error_mock;

    /**
     * A Mock for the POST super Global
     */
    protected $super_global_post_mock;

    /**
     * An Instance of the class to be tested
     */
    public static $registration_instance;

    /**
     * Prepares the test environment before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->mockPOSTSuperGlobal();
        $this->mockWPRoles();
        $this->mockWPError();
        self::$registration_instance = new RegisterLawyer(
            $this->super_global_post_mock,
            $this->wp_roles_mock,
            $this->wp_error_mock
        );
    }

    /**
     * Test that the Lawyer is Absent if the role isnt created
     *
     * @covers \DevignersPlace\CaseTracker\Includes\RegisterLawwyer->setFormValuesToClassProp(PostGlobalVariableGetter $form_values)
     */
    public function testLawyerRoleAbsent()
    {
        $WPRolesMock = Mockery::mock(\WP_Roles::class);
        $WPRolesMock
            ->shouldReceive('is_role')
            ->with('josbiz-casetracker-lawyer')
            ->andReturn(false);
        $reg_instance = new RegisterLawyer(
            $this->super_global_post_mock,
            $WPRolesMock,
            $this->wp_error_mock
        );
        $this->assertFalse($reg_instance->isLawyerRolePresent($WPRolesMock));
    }

    /**
     * Test that the Lawyer role is present
     *
     * @covers \DevignersPlace\CaseTracker\Includes\RegisterLawwyer::setFormValuesToClassProp
     */
    public function testLawyerRolePresent()
    {
        $reg_instance = self::$registration_instance;
        $this->assertTrue($reg_instance->isLawyerRolePresent($this->wp_roles_mock));
    }

    /**
     * Test that the Lawyer role is present
     *
     * @covers \DevignersPlace\CaseTracker\Includes\RegisterLawwyer->setFormValuesToClassProp($form_values)
     */
    public function testFormInputValsAssignedToClassProperties()
    {
        $reg_instance = self::$registration_instance;
        $post_global_mock = $this->getMockPostGlobal();
        $this->assertSame($reg_instance::$first_name, $post_global_mock["lawyer-first-name"]);
        $this->assertSame($reg_instance::$last_name, $post_global_mock["lawyer-last-name"]);
        $this->assertSame($reg_instance::$username, $post_global_mock["lawyer-username"]);
        $this->assertSame($reg_instance::$email, $post_global_mock["lawyer-email"]);
        $this->assertSame($reg_instance::$gender, $post_global_mock["lawyer-gender"]);
        $this->assertSame($reg_instance::$password, $post_global_mock["lawyer-password"]);
        $this->assertSame($reg_instance::$confirm_password, $post_global_mock["lawyer-password-confirm"]);
        $this->assertSame($reg_instance::$registration_nonce, $post_global_mock["lawyer-registration-nonce"]);
    }

    /**
     * Test to validate form inputs
     *
     * @return void
     */
    public function testValidateFormValuesPass()
    {
        $reg_instance = self::$registration_instance;

        $this->mockFunctionsForInputs($reg_instance);
        $reg_instance->validateFormValues();
        $wp_errors_handler = ErrorHandler::getErrorsObject();
        $this->assertSame(array(), $wp_errors_handler->get_error_messages());
    }

    /**
     * Test for the addNewLawyer method
     *
     * @return void
     */
    public function testAddNewLawyer()
    {

        $reg_lawyer = self::$registration_instance;

        Monkey\Functions\expect('is_page')
            ->with('add-lawyer')
            ->andReturn(true);

        Monkey\Functions\expect('wp_verify_nonce')
            ->with('Lawyer Registration Nonce, lawyer-registration-nonce')
            ->andReturn(true);

        Monkey\Functions\expect('wp_new_user_notification')
            ->with($reg_lawyer->getCreatedLawyerId())
            ->andReturn(true);

        Monkey\Functions\expect('wp_mail')
        ->with($reg_lawyer->getCreatedLawyerId())
        ->andReturn(true);

        Monkey\Functions\expect('wp_insert_user')->andReturn(10);

        $this->mockFunctionsForInputs($reg_lawyer);

        $reg_lawyer->addNewLawyer();

        $this->AssertEquals(10, $reg_lawyer->getCreatedLawyerId());
    }

    /**
     * Use the Monkey Function to create Mock functions of the
     * Wordpress Functions for some selected form inputs
     *
     * @return void
     */
    private function mockFunctionsForInputs($reg_instance)
    {
        $this->mockFunction('username_exists', $reg_instance::$username, false);
        $this->mockFunction('validate_username', $reg_instance::$username, true);
        $this->mockFunction('is_email', $reg_instance::$email, true);
        $this->mockFunction('email_exists', $reg_instance::$email, false);
    }

    /**
     * Use the Monkey Functions to create Mock Functions
     *
     * @return void
     */
    public function mockFunction($function_name, $argument, $return_val)
    {
        Monkey\Functions\expect($function_name)
            ->once()
            ->with($argument)
            ->andReturn($return_val);
    }

    /**
     * Generates a mock for the WP_Roles Class using Mockery
     *
     * @return void
     */
    public function mockWPRoles()
    {
        $wp_roles_mock = Mockery::mock(\WP_Roles::class);
        $wp_roles_mock
            ->shouldReceive('is_role')
            ->with('josbiz-casetracker-lawyer')
            ->andReturn(true);
        $this->wp_roles_mock = $wp_roles_mock;
    }

    /**
     * Generates a mock for the WP_Error Class using Mockery
     *
     * @return void
     */
    public function mockWPError()
    {
        $wp_error_mock = Mockery::mock('WP_Error');
        $wp_error_mock
            ->shouldReceive('get_error_messages', 'add')
            ->andReturnUsing([$this, 'ErrorMsgReturn']);
        $this->wp_error_mock = $wp_error_mock;
    }

    /**
     * Generates a mock for the POST super global using Mockery
     *
     * @return void
     */
    public function mockPOSTSuperGlobal()
    {
         // Set up the Content Getter mock.
        $global_post_var_mock = Mockery::mock(PostGlobalVariableGetter::class);
        $global_post_var_mock
                ->shouldReceive('getPostSuperGlobal')
                ->andReturnUsing([$this, 'getMockPostGlobal']);
        $this->super_global_post_mock = $global_post_var_mock;
    }

    /**
     * A Substitute for the POST super global
     *
     * @return void
     */
    public function getMockPostGlobal()
    {
        return array(
            'lawyer-username'           => $this->allow_empty ? '' : 'Lawyer Username',
            'lawyer-email'              => $this->allow_empty ? '' : 'mikemike@gmail.com',
            'lawyer-first-name'         => $this->allow_empty ? '' : 'Lawyer First Name',
            'lawyer-last-name'          => $this->allow_empty ? '' : 'Lawyer Last Name',
            'lawyer-gender'             => $this->allow_empty ? '' : 'Lawyer Gender',
            'lawyer-phone'              => $this->allow_empty ? '' : 'Lawyer Phone',
            'lawyer-password'           => $this->allow_empty ? '' : 'Lawyer Password',
            'lawyer-password-confirm'   => $this->allow_empty ? '' : 'Lawyer Password',
            'lawyer-registration-nonce' => $this->allow_empty ? '' : 'Lawyer Registration Nonce'
        );
    }

    /**
     * The Method to give the mock return value when there is an error
     * @param string  $error_id            A Slug for the error to be logged
     * @param string  $error_output_string The error text that will be rendered on the frontend
     *
     * @return void
     */
    public function WPAddErrorReturn($error_id, $error_output_string)
    {
        return [$error_id => $error_output_string];
    }

    /**
     * The Method to give determine the mock return value
     * @param string  $error_id            A Slug for the error to be logged
     * @param string  $error_output_string The error text that will be rendered on the frontend
     *
     * @return void
     */
    public function errorMsgReturn($error_id = "", $error_output_string = "")
    {
        if (!empty($error_id) && !empty($error_id)) {
            throw new \Exception($error_output_string);
            return;
        }
        if (empty($error_id) && !empty($error_id) || !empty($error_id) && empty($error_id)) {
            throw new \Exception("There is a problem with the arguments passed for error logging");
        }
        return [];
    }
}
