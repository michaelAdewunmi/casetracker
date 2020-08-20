<?php
namespace DevignersPlace\CaseTracker\Tests\Integration;

use DevignersPlace\CaseTracker\Includes\PostGlobalVariableGetter;

use Mockery;

/**
 * Class IntegrationTestCase
 *
 * All integration tests MUST extend this class
 *
 * @package DevignersPlace\CaseTracker\Tests\Integration
 */
abstract class IntegrationTestCase extends \WP_UnitTestCase
{
    /**
     * Cleans up the test environment after each test.
     */
    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
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
        $this->super_global_post_mock = $global_post_var_mock;
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
            if (isset($this->is_lawyer_reg_test) && $this->is_lawyer_reg_test!==null) {
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
            } else {
                $this->lawyer_id = $this->factory->user->create(
                    array (
                        'role'              => 'josbiz-casetracker-lawyer',
                        'user_login'        => 'Demmy_Young',
                        'user_email'        => 'demmy.young.writes@gmail.com',
                        'first_name'        => 'Demilade',
                        'last_name'         => 'Young',
                    )
                );
                $case_tags = array("thievery", "court");
                return array(
                    'suit-number'       => $this->allow_empty ? '' : '01/ff9DCQthvry/Grands',
                    'lawyer-assigned'   => $this->allow_empty ? '' : $this->lawyer_id,
                    'court-name'        => $this->allow_empty ? '' : 'Ogabi',
                    'court-address'     => $this->allow_empty ? '' : 'Egbeda',
                    'case-description'  => $this->allow_empty ? '' : 'A Thievery case',
                    'case-start-date'   => $this->allow_empty ? '' : '20-04-2020',
                    'case-tags'         => $this->allow_empty ? '' : json_encode($case_tags),
                    'case-adder-nonce'  => wp_create_nonce('case-adder-nonce')
                );
            }
        }
    }

    /**
     * Generates a mock for the WP_Error Class using Mockery
     *
     * @return void
     */
    public function mockWPError()
    {
        $wp_error_mock = \Mockery::mock('WP_Error');
        $wp_error_mock
            ->shouldReceive('get_error_codes', 'add')
            ->andReturnUsing([$this, 'getErrorCodes']);
        return $wp_error_mock;
    }

    /**
     * The Method to give determine the mock return value
     * @param string  $error_id            A Slug for the error to be logged
     * @param string  $error_output_string The error text that will be rendered on the frontend
     *
     * @return void
     */
    public function getErrorCodes($error_id = "", $error_output_string = "")
    {
        if (empty($error_id) && empty($error_output_string)) {
            if ($this->allow_case_track_errors) {
                return array (
                    'empty_suit_number', 'empty_lawyer_assigned', 'empty_court_name',
                    'empty_court_address', 'empty_case_description', 'empty_case_start_date',
                );
            } else {
                return array();
            }
        } else {
            if (!$this->skip_add_error) {
                $this->{$error_id} = $error_output_string;
            }
            return;
        }
    }
}
