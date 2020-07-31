<?php

namespace DevignersPlace\CaseTracker\Tests\Mock;

/**
 * A Mock value for the Post Global Variable to enable carrying out tests
 *
 * @category   Plugins
 * @package    CaseTracker
 * @subpackage DevignersPlace\CaseTracker\Tests\Unit
 * @author     Michael Adewunmi <d.devignersplace@gmail.com>
 * @license    GPL-2.0+ http://www.gnu.org/licenses/gpl-2.0.txt
 * @link       http://josbiz.com.ng
 * @since      1.0.0
 */
class POSTGlobalMock
{

    /**
     * Class Construct
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public static function getPOSTFormValues($allow_empty)
    {
        return array(
            'lawyer-username'           => $allow_empty ? '' : 'Lawyer Username',
            'lawyer-email'              => $allow_empty ? '' : 'mikemike@gmail.com',
            'lawyer-first-name'         => $allow_empty ? '' : 'Lawyer Firs Name',
            'lawyer-last-name'          => $allow_empty ? '' : 'Lawyer Last Name',
            'lawyer-gender'             => $allow_empty ? '' : 'Male',
            'lawyer-phone'              => $allow_empty ? '' : 'Lawyer Phone',
            'lawyer-password'           => $allow_empty ? '' : 'Lawyer Password',
            'lawyer-password-confirm'   => $allow_empty ? '' : 'Lawyer Password',
            'lawyer-registration-nonce' => $allow_empty ? '' : 'LawyerRegistrationNonce'
        );
    }
}
