<?php
namespace DevignersPlace\CaseTracker\Tests\Mock;


/**
 * Generates a mock for the WP_Error Class using Mockery
 *
 * @return void
 */
function mockWPError()
{
    $wp_error_mock = \Mockery::mock('WP_Error');
    $wp_error_mock
        ->shouldReceive('get_error_messages', 'add')
        ->andReturnUsing('ErrorMsgReturn');
    return $wp_error_mock;
}

/**
 * The Method to give the mock return value when there is an error
 * @param string  $error_id            A Slug for the error to be logged
 * @param string  $error_output_string The error text that will be rendered on the frontend
 *
 * @return void
 */
function WPAddErrorReturn($error_id, $error_output_string)
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
function errorMsgReturn($error_id = "", $error_output_string = "")
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
