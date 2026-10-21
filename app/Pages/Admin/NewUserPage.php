<?php
namespace App\Pages\Admin;

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
 * @date 26-2-2017
 */
class NewUserPage extends PageFrame
{

    /**
     * The views to be shown as header.
     *
     * @return array|null
     */
    public function getViews(): array
    {
        return [
            'new-user'
        ];
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $this->setData('roleNameKey', Role::FIELD_ROLE_NAME);
        $this->setData('roleIdKey', Role::FIELD_ROLE_ID);
        $this->setData('roles', model(Role::class)->getRoles());
    }

    /**
     * If the current user has access to this page.
     *
     * @return bool
     */
    public function hasAccess(): bool
    {
        return isLoggedInAndHasRole([Role::ROLE_ADMIN]);
    }

    public function onFormSuccess()
    {
        $username   = set_value('username');
        $password   = set_value('password');
        $firstName  = set_value('first-name');
        $lastName   = set_value('last-name');
        $email      = set_value('email');
        $userRoles  = set_value('roles');

        if (is_array($userRoles)) {
            $userRoles = array_keys($userRoles);
        } else {
            $userRoles = [];
        }

        $success = model(Login_User_LoginRole::class)->add($username, $password, $firstName, $lastName, $email, $userRoles);

        if ($success) {
            $this->addSuccessMessage(sprintf(lang('Admin.application_new_user_success'), $username));
        } else {
            $this->addWarningMessage(lang('Admin.application_new_user_error_unknown'));
        }
    }

    /**
     * The form validation rules.
     *
     * @return array
     */
    protected function getFormValidationRules()
    {
        return [
            // All fields are required.
            [
                'field' => 'username',
                'label' => lang('Admin.application_new_user_username'),
                'rules' => [
                    'required',
                    ['usernameNotExists', [model(Login::class), 'usernameNotExists']],
                    'min_length[1]'
                ],
                'errors' => [
                    'required' => lang('Admin.application_new_user_error_required'),
                    'usernameNotExists' => lang('Admin.application_new_user_error_username_exists'),
                    'min_length[1]' => lang('Admin.application_new_user_error_short_username'),
                ],
            ],
            [
                'field' => 'password',
                'label' => lang('Admin.application_new_user_password'),
                'rules' => [
                    'required',
                    'min_length[6]',
                    'matches[confirm-password]',
                ],
                'errors' => [
                    'required' => lang('Admin.application_new_user_error_required'),
                    'min_length[6]' => lang('Admin.application_new_user_error_password_not_strong_enough'),
                    'matches[confirm-password]' => lang('Admin.application_new_user_error_password_not_equal'),
                ],
            ],
            [
                'field' => 'confirm-password',
                'label' => lang('Admin.application_new_user_confirm_password'),
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => lang('Admin.application_new_user_error_required'),
                ],
            ],
            [
                'field' => 'email',
                'label' => lang('Admin.application_new_user_email'),
                'rules' => [
                    'required',
                    'valid_email',
                ],
                'errors' => [
                    'required' => lang('Admin.application_new_user_error_required'),
                    'valid_email' => lang('Admin.application_new_user_error_valid_email'),
                ],
            ],
            [
                'field' => 'first-name',
                'label' => lang('Admin.application_new_user_first_name'),
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Admin.application_new_user_error_required'),
                ],
            ],
            [
                'field' => 'last-name',
                'label' => lang('Admin.application_new_user_last_name'),
                'rules' => 'required',
                'errors' => [
                    'required' => lang('Admin.application_new_user_error_required'),
                ],
            ],
        ];
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            Login_User_LoginRole::class,
        ];
    }

    /**
     * Defines which libraries should be loaded.
     *
     * @return array;
     */
    protected function getLibraries(): array
    {
        return [];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers(): array
    {
        return [];
    }
}