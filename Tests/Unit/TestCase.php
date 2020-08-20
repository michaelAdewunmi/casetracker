<?php

namespace DevignersPlace\CaseTracker\Tests\Unit;

use Brain\Monkey;
//Import PHP unit test case.
//Must be aliased to avoid having two classes of same name in scope.
use PHPUnit\Framework\TestCase as FrameworkTestCase;

/**
 * Class TestCase
 *
 * Default test case for all unit tests
 * @package DevignersPlace\CaseTracker\Tests\Unit
 */
abstract class TestCase extends FrameworkTestCase
{
    /**
     * Prepares the test environment before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    /**
     * Cleans up the test environment after each test.
     */
    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
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
                //echo($error_id."\n".$error_output_string."\n\n");
                $this->{$error_id} = $error_output_string;
            }
            return;
        }
    }
}
