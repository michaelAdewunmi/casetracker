<?php

namespace DevignersPlace\CaseTracker\Tests\Integration;

use DevignersPlace\CaseTracker\Includes\RegisterLawyer;
use DevignersPlace\CaseTracker\Includes\PostGlobalVariableGetter;
use DevignersPlace\CaseTracker\Includes\ErrorHandler;
use DevignersPlace\CaseTracker\Tests\Mock\POSTGlobalMock;

use Mockery;
use Brain\Monkey;

/**
 * Class TestRegisterLawyer
 *
 * Unit tests for the class that registers Lawyer
 *
 * @package DevignersPlace\CaseTracker\Tests\Unit;
 */
class RegisterLawyerTest extends IntegrationTestCase
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
    public function setUp(): void
    {
        parent::setUp();
        add_action('user_register', [$this, 'saveLawyerExtraDataAsMeta']);
        $this->mockPOSTSuperGlobal();
        self::$registration_instance = new RegisterLawyer(
            $this->super_global_post_mock,
            new \WP_Roles,
            new \WP_Error
        );
    }

    /**
     * save all extra data as meta which takes care of the original one
     * which does not fire in the RegisterLwayer class because it needs the
     * page to be a add-lawyer page.
     */
    public function saveLawyerExtraDataAsMeta($user_id)
    {
        $post_object = $this->getMockPostGlobal();
        $gender = $post_object['lawyer-gender'];
        $phone = $post_object['lawyer-phone'];
        update_user_meta($user_id, 'phone_number', $phone);
        update_user_meta($user_id, 'gender', $gender);
    }

    /**
     * Test that the Lawyer is Absent if the role isnt created
     *
     * @covers \DevignersPlace\CaseTracker\Includes\RegisterLawwyer->setFormValuesToClassProp(PostGlobalVariableGetter $form_values)
     */
    public function testLawyerRoleAbsent()
    {
        $registration_instance = self::$registration_instance;
        $this->assertFalse($registration_instance::isLawyerRolePresent(wp_roles()));
    }

    /**
     * Test to validate form inputs
     *
     * @return void
     */
    public function testValidateFormValuesPass()
    {
        $this->allow_empty = false;
        $reg_instance = self::$registration_instance;
        $reg_instance->validateFormValues();
        $wp_errors_handler = ErrorHandler::getErrorsObject();
        $this->assertSame(array(), $wp_errors_handler->get_error_messages());
    }

    /**
     * Test to validate form inputs failure
     *
     * @return void
     */
    public function testValidateFormValuesNoPass()
    {
        $this->allow_empty = true;
        $this->mockPOSTSuperGlobal();
        $reg_instance = new RegisterLawyer(
            $this->super_global_post_mock,
            new \WP_Roles,
            new \WP_Error
        );
        $reg_instance->validateFormValues();
        $wp_errors_handler = ErrorHandler::getErrorsObject();
        $errors = $wp_errors_handler->get_error_messages();

        //Make sure results are an array
        $this->assertTrue(is_array($errors));

        //Make sure the two arrays are the same size
        $this->assertSame(10, count($errors));
    }

    /**
     * Test for the addNewLawyer method
     *
     * @return void
     */
    public function testAddNewLawyer()
    {
        $this->allow_empty = false;
        $this->mockWPRoles();
        $register_lawyer = Mockery::mock(
            'DevignersPlace\CaseTracker\Includes\RegisterLawyer',
            [$this->super_global_post_mock, $this->wp_roles_mock, new \WP_Error]
        )->makePartial();
        $register_lawyer->shouldReceive(['isRegisterPage' => true, 'allowLawyerRegistration' => true]);

        $register_lawyer->addNewLawyer();

        $this->AssertSame(1, did_action('init'));
        $this->AssertSame(1, did_action('user_register'));

        $post_object = $this->getMockPostGlobal();

        $user_object = get_user_by('email', $post_object['lawyer-email']);
        $created_user_id = $user_object->data->ID;
        $created_user_email = $user_object->data->user_email;
        $created_user_first_name = get_the_author_meta('first_name', $created_user_id);
        $created_user_last_name = get_the_author_meta('last_name', $created_user_id);
        $created_user_gender = get_the_author_meta('gender', $created_user_id);
        $created_user_phone = get_the_author_meta('phone_number', $created_user_id);

        $this->AssertEquals($created_user_id, $register_lawyer->getCreatedLawyerId());
        $this->AssertSame($created_user_email, $post_object['lawyer-email']);
        $this->AssertSame($created_user_first_name, $post_object['lawyer-first-name']);
        $this->AssertSame($created_user_last_name, $post_object['lawyer-last-name']);
        $this->AssertSame($created_user_gender, $post_object['lawyer-gender']);
        $this->AssertSame($created_user_phone, $post_object['lawyer-phone']);

    }




    // /**
    //  * Use the Monkey Functions to create Mock Functions
    //  *
    //  * @return void
    //  */
    // public function mockFunction($function_name, $argument, $return_val)
    // {
    //     Monkey\Functions\expect($function_name)
    //         ->once()
    //         ->with($argument)
    //         ->andReturn($return_val);
    // }

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
            'lawyer-username'           => $this->allow_empty ? '' : 'Sleek_Lawyer',
            'lawyer-email'              => $this->allow_empty ? '' : 'michaelme@gmail.com',
            'lawyer-first-name'         => $this->allow_empty ? '' : 'Demilade',
            'lawyer-last-name'          => $this->allow_empty ? '' : 'Young',
            'lawyer-gender'             => $this->allow_empty ? '' : 'Female',
            'lawyer-phone'              => $this->allow_empty ? '' : '08123456789',
            'lawyer-password'           => $this->allow_empty ? '' : 'hereismypasswordforyou',
            'lawyer-password-confirm'   => $this->allow_empty ? '' : 'hereismypasswordforyou',
            'lawyer-registration-nonce' => $this->allow_empty ? '' : 'LawyerRegistrationNonce'
        );
    }
}
