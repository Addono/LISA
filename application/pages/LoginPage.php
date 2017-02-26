<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class DefaultPage
 * @property    CI_Session          $session
 * @property    Login               $Login
 */
class LoginPage extends PageFrame
{

    public function getHeader()
    {
        return [
            'login-header'
        ];
    }

    public function getBody()
    {
        return false;
    }

    public function getData()
    {
        return false;
    }

    public function isVisible()
    {
        return $this->hasAccess();
    }

    protected function getFormValidationRules()
    {
        return [
            [
                'field' => 'username',
                'label' => lang('login_username'),
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => lang('login_error_field_required'),
                ],
            ],
            [
                'field' => 'password',
                'label' => lang('login_password'),
                'rules' => 'required',
                'errors' => [
                    'required' => lang('login_error_field_required'),
                ],
            ],
            [
                'field' => 'username',
                'label' => lang('login_username'),
                'rules' => [
                    ['usernameExists', [$this->ci->Login, 'usernameExists']],
                ],
                'errors' => [
                    'usernameExists' => lang('login_error_username_does_not_exist'),
                ],
            ],
        ];
    }

    public function hasAccess()
    {
        return !isLoggedIn($this->ci->session);
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        if(!$this->hasError) {
            $username = set_value('username');
            $password = set_value('password');
            $validCredentials = $this->ci->Login->checkUsernamePasswordCredentials($username, $password);
            if($validCredentials) {
                $loginId = $this->ci->Login->getLoginIdFromUsername($username);
                setLoggedIn($this->ci->session, $loginId);
                redirect();
            } else {
                $this->appendData('errors', lang('login_error_invalid_credentials'));
            }
        }
    }

    /**
     * Function which is called after the views are rendered.
     */
    public function afterView()
    {
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array
     */
    protected function getModels()
    {
        return ['Login'];
    }

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    protected function getLibraries()
    {
        return ['session'];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers()
    {
        return ['tables'];
    }
}