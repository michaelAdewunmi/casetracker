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
    private $allow_empty;

    private $super_global_post_mock;

    public function __construct($allow_empty)
    {
        $this->allow_empty = $allow_empty;
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
            'suit-number'       => $this->allow_empty ? '' : 'The Suite Number',
            'lawyer-assigned'   => $this->allow_empty ? '' : 'Demmy Youung',
            'court-name'        => $this->allow_empty ? '' : 'Ogabi',
            'court-address'     => $this->allow_empty ? '' : 'Egbeda',
            'case-description'  => $this->allow_empty ? '' : 'A Thievery case',
            'case-start-date'   => $this->allow_empty ? '' : '20-04-2020',
        );
    }

    // /**
    //  * A Substitute for the POST super global
    //  *
    //  * @return void
    //  */
    // public function getMockPostGlobal()
    // {
    //     return array(
    //         'lawyer-username'           => $this->allow_empty ? '' : 'Lawyer Username',
    //         'lawyer-email'              => $this->allow_empty ? '' : 'mikemike@gmail.com',
    //         'lawyer-first-name'         => $this->allow_empty ? '' : 'Lawyer First Name',
    //         'lawyer-last-name'          => $this->allow_empty ? '' : 'Lawyer Last Name',
    //         'lawyer-gender'             => $this->allow_empty ? '' : 'Lawyer Gender',
    //         'lawyer-phone'              => $this->allow_empty ? '' : 'Lawyer Phone',
    //         'lawyer-password'           => $this->allow_empty ? '' : 'Lawyer Password',
    //         'lawyer-password-confirm'   => $this->allow_empty ? '' : 'Lawyer Password',
    //         'lawyer-registration-nonce' => $this->allow_empty ? '' : 'Lawyer Registration Nonce'
    //     );
    // }
}
