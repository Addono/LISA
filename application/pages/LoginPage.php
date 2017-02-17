<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class DefaultPage
 * @property    CI_Session          $session
 * @property    Users               $Users
 */
class LoginPage extends PageFrame
{

    public function __construct()
    {
        parent::addModels(['Users']);
        parent::addLibraries(['session']);
        parent::addHelpers(['tables']);
        parent::__construct();

        if(!$this->hasError) {
            $username = set_value('username');
            $password = set_value('password');
            $userId = $this->ci->Users->checkUsernamePasswordCredentials($username, $password);
            if($userId) {
                $this->ci->session->userId = $userId;
                redirect();
            } else {
                $this->dataAppend('errors', lang('login_error_invalid_credentials'));
            }
        }
    }

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
        return true;
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
                    ['usernameExists', [$this->ci->Users, 'usernameExists']],
                ],
                'errors' => [
                    'usernameExists' => lang('login_error_username_does_not_exist'),
                ],
            ],
        ];
    }

    protected function accessibleBy()
    {
        return [
            ROLE_VISITOR,
        ];
    }
}