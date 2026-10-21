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
 * @date 6-2-2017
 */

/**
 * Class UserPage
 */
class UserPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'user'
        ];
    }

    public function hasAccess(): bool
    {
        // Check if the user is logged in and has the required rights.
        $hasRights = isLoggedInAndHasRole([Role::ROLE_ADMIN]);

        // Check if the given login id is valid and has a user account connected to it.
        $loginId = $this->getDataKey('subpage');
        $validLoginId = $loginId !== null && model(Login_User::class)->exists($loginId);

        return $hasRights && $validLoginId;
    }

    public function onFormSuccess()
    {
        $loginId = $this->getDataKey('subpage');

        switch (set_value('type')) {
            case 'name':
                $success = model(User::class)->updateName(
                    $loginId,
                    set_value('first-name'),
                    set_value('last-name')
                );

                if ($success) {
                    $this->addSuccessMessage(lang('Admin.application_user_name_change_success'));
                }
                break;
            case 'password':
                $success = model(Login::class)->updatePassword(
                    $loginId,
                    set_value('password')
                );

                if ($success) {
                    $this->addSuccessMessage(lang('Admin.application_user_password_change_success'));
                }
                break;
            case 'email':
                $success = model(User::class)->updateEmail(
                    $loginId,
                    set_value('email')
                );

                if ($success) {
                    $this->addSuccessMessage(lang('Admin.application_user_email_change_success'));
                }
                break;
            case 'roles';
                $roles = array_keys(set_value('roles', []));

                $success = model(LoginRole::class)->setRolesForLoginId($loginId, $roles);
                if ($success) {
                    $this->addSuccessMessage(lang('Admin.application_user_roles_change_success'));
                }
                break;
            default:
                $success = 0;
                break;
        }

        if (!$success) {
            $this->addDangerMessage(lang('Admin.application_server_error'));
        }
    }

    protected function getFormValidationRules()
    {
        switch (set_value('type')) {
            case 'name': // If the user submitted the change name form.
                return [
                    [
                        'field' => 'first-name',
                        'label' => lang('Admin.application_user_first_name'),
                        'rules' => 'required',
                        'errors' => [
                            'required' => lang('Admin.application_user_error_required'),
                        ],
                    ],
                    [
                        'field' => 'last-name',
                        'label' => lang('Admin.application_user_last_name'),
                        'rules' => 'required',
                        'errors' => [
                            'required' => lang('Admin.application_user_error_required'),
                        ],
                    ],
                ];
            case 'password': // If the user submitted the change password form.
                return [
                    [
                        'field' => 'password',
                        'label' => lang('Admin.application_new_user_password'),
                        'rules' => [
                            'required',
                            'min_length[6]',
                            'matches[confirm-password]'
                        ],
                        'errors' => [
                            'required' => lang('Admin.application_user_error_required'),
                            'min_length[6]' => lang('Admin.application_user_error_password_not_strong_enough'),
                            'matches[confirm-password]' => lang('Admin.application_user_error_password_not_equal'),
                        ],
                    ],
                    [
                        'field' => 'confirm-password',
                        'label' => lang('Admin.application_user_confirm_password'),
                        'rules' => [
                            'required',
                        ],
                        'errors' => [
                            'required' => lang('Admin.application_user_error_required'),
                        ],
                    ],
                ];
            case 'email': // If the user submitted the change email form.
                return [
                    [
                        'field' => 'email',
                        'label' => lang('Admin.application_user_email'),
                        'rules' => [
                            'required',
                            'valid_email',
                        ],
                        'errors' => [
                            'required' => lang('Admin.application_user_error_required'),
                            'valid_email' => lang('Admin.application_user_error_valid_email'),
                        ],
                    ],
                ];
                break;
            case 'roles':
                return true;
            case '': // If the user didn't submit any form.
                break;
            default: // Something went wrong since it should have been caught by one of the others.
                $this->addWarningMessage(lang('Admin.unknown_form_error'));
                break;
        }

        return [];
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $loginId = $this->getDataKey('subpage');

        // Get the user data.
        $userData = model(Login_User_LoginRole_Role::class)->getUserData($loginId);
        $userDataFields = [
            'email' => User::FIELD_EMAIL,
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
        ];
        $this->setData('userData', $userData);
        $this->setData('userDataFields', $userDataFields);

        // Get the username
        $username = model(Login::class)->getUsername($loginId);
        $this->setData('username', $username);

        // Get all available roles.
        $roles = [];
        $allRoles = model(Role::class)->getRoles();
        $userRoles = array_column(model(LoginRole::class)->getFromLoginId($loginId), Role::FIELD_ROLE_ID);
        foreach ($allRoles as $role) {
            $roleId = $role[Role::FIELD_ROLE_ID];
            $roles[$roleId] = [
                'name' => $role[Role::FIELD_ROLE_NAME],
                'userHas' => array_search($roleId, $userRoles) !== false,
            ];
        }

        $this->setData('roles', $roles);
        $this->setData('roleIdKey', Role::FIELD_ROLE_ID);
        $this->setData('roleNameKey', Role::FIELD_ROLE_NAME);
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            Login::class,
            Role::class,
            User::class,
            Login_User::class,
            Login_User_LoginRole_Role::class,
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
