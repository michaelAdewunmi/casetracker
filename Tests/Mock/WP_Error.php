<?php

namespace DevignersPlace\CaseTracker\Tests\Mock;

// phpcs:disable
if (! class_exists('\WP_Error')) {
    /**
     * Class WP_Error
     *
     * Mock for WP_Error
     *
     * @package DevignersPlace\CaseTracker\Mock
     */
    class WP_Error extends \stdClass
    {
        /**
         * WP_Error constructor.
         */
        public function __construct()
        {
            //Don't do anything
        }

        public function get_error_messages()
        {
            return [];
        }
    }
}
