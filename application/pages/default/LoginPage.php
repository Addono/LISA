<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class DefaultPage
 * @property    CI_Session          $session
 * @property    Login               $Login
 * @property    Role                $Role
 */
class LoginPage extends PageFrame
{

    public function getViews()
    {
        return [
            'login-header',
            'intersection',
        ];
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
                    ['usernameExists', [$this->ci->Login, 'usernameExists']],
                ],
                'errors' => [
                    'required' => lang('login_error_field_required'),
                    'usernameExists' => lang('login_error_username_does_not_exist'),
                ],
            ],
            [
                'field' => 'password',
                'label' => lang('login_password'),
                'rules' => [
                    'trim',
                    'required',
                ],
                'errors' => [
                    'required' => lang('login_error_field_required'),
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
        if ($this->formSuccess) {
            $username = set_value('username');
            $password = set_value('password');
            $validCredentials = $this->ci->Login->checkUsernamePasswordCredentials($username, $password);
            if ($validCredentials) {
                $loginId = $this->ci->Login->getLoginIdFromUsername($username);
                setLoggedIn($this->ci->session, $loginId);
                redirect();
            } else {
                $this->addWarningMessage(lang('login_error_invalid_credentials'));
            }
        }
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array
     */
    protected function getModels()
    {
        return [
            Login::class,
            Role::class,
        ];
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