<?php

// phpcs:disable

if (!class_exists('\WP_Roles')) {
    /**
     * A Mock class for WordPress WP_Roles
     *
     * @category   Plugins
     * @package    CaseTracker
     * @subpackage CaseTracker/Tests/Mock
     * @author     Michael Adewunmi <d.devignersplace@gmail.com>
     * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
     * @link       http://josbiz.com.ng
     * @since      1.0.0
     */
    class WP_Roles
    {
        private $lawyer_role_present;

        /**
         * Class Constructor
         */
        public function __construct($role_present)
        {
            $this->lawyer_role_present = $role_present;
        }

        public function is_role($role)
        {
            if ($role!=='josbiz-casetracker-lawyer') {
                return;
            }
            return $this->lawyer_role_present;
        }
    }
}