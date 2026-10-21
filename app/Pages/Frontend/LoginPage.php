<?php
namespace App\Pages\Frontend;

use App\Models\Consumption;
use App\Models\Login;
use App\Models\LoginReset;
use App\Models\LoginReset_User;
use App\Models\LoginRole;
use App\Models\Login_User;
use App\Models\Login_User_LoginRole;
use App\Models\Login_User_LoginRole_Role;
use App\Models\ModelFrame;
use App\Models\Role;
use App\Models\Role_LoginRole;
use App\Models\Transaction;
use App\Models\User;
use App\Models\User_Consumption;
use App\Models\User_Consumption_LoginRole;
use App\Models\User_LoginRole;
use App\Models\User_Transaction;
use App\Pages\{PageFrame, ApiFrame, MenuFrame, MenuItem, MenuPage, MenuLink};

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

    public function getViews(): array
    {
        return [
            'login-header',
        ];
    }

    protected function getFormValidationRules()
    {
        return [
            [
                'field' => 'username',
                'label' => lang('Lisa.login_username'),
                'rules' => [
                    'required',
                    ['usernameExists', [model(Login::class), 'usernameExists']],
                ],
                'errors' => [
                    'required' => lang('Lisa.login_error_field_required'),
                    'usernameExists' => lang('Lisa.login_error_username_does_not_exist'),
                ],
            ],
            [
                'field' => 'password',
                'label' => lang('Lisa.login_password'),
                'rules' => [
                    'trim',
                    'required',
                ],
                'errors' => [
                    'required' => lang('Lisa.login_error_field_required'),
                ],
            ],
        ];
    }

    public function hasAccess(): bool
    {
        return !isLoggedIn(session());
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        if ($this->formSuccess) {
            $username = set_value('username');
            $password = set_value('password');
            $validCredentials = model(Login::class)->checkUsernamePasswordCredentials($username, $password);
            if ($validCredentials) {
                $loginId = model(Login::class)->getLoginIdFromUsername($username);
                setLoggedIn(session(), $loginId);
                return redirect()->to(site_url(''));
            } else {
                $this->addWarningMessage(lang('Lisa.login_error_invalid_credentials'));
            }
        }
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array
     */
    protected function getModels(): array
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
    protected function getLibraries(): array
    {
        return ['session'];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers(): array
    {
        return ['tables'];
    }
}