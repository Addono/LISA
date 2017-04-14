<?php

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
    public function getViews()
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
        $this->setData('roles', $this->ci->Role->getRoles());
    }

    /**
     * If the current user has access to this page.
     *
     * @return boolean
     */
    public function hasAccess()
    {
        return isLoggedInAndHasRole($this->ci, [Role::ROLE_ADMIN]);
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

        $success = $this->ci->Login_User_LoginRole->add($username, $password, $firstName, $lastName, $email, $userRoles);

        if ($success) {
            $this->addSuccessMessage(sprintf(lang('application_new_user_success'), $username));
        } else {
            $this->addWarningMessage(lang('application_new_user_error_unknown'));
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
                'label' => lang('application_new_user_username'),
                'rules' => [
                    'required',
                    ['usernameNotExists', [$this->ci->Login, 'usernameNotExists']],
                    'min_length[1]'
                ],
                'errors' => [
                    'required' => lang('application_new_user_error_required'),
                    'usernameNotExists' => lang('application_new_user_error_username_exists'),
                    'min_length[1]' => lang('application_new_user_error_short_username'),
                ],
            ],
            [
                'field' => 'password',
                'label' => lang('application_new_user_password'),
                'rules' => [
                    'required',
                    'min_length[8]',
                    'matches[confirm-password]',
                ],
                'errors' => [
                    'required' => lang('application_new_user_error_required'),
                    'min_length[8]' => lang('application_new_user_error_password_not_strong_enough'),
                    'matches[confirm-password]' => lang('application_new_user_error_password_not_equal'),
                ],
            ],
            [
                'field' => 'confirm-password',
                'label' => lang('application_new_user_confirm_password'),
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => lang('application_new_user_error_required'),
                ],
            ],
            [
                'field' => 'email',
                'label' => lang('application_new_user_email'),
                'rules' => [
                    'required',
                    'valid_email',
                ],
                'errors' => [
                    'required' => lang('application_new_user_error_required'),
                    'valid_email' => lang('application_new_user_error_valid_email'),
                ],
            ],
            [
                'field' => 'first-name',
                'label' => lang('application_new_user_first_name'),
                'rules' => 'required',
                'errors' => [
                    'required' => lang('application_new_user_error_required'),
                ],
            ],
            [
                'field' => 'last-name',
                'label' => lang('application_new_user_last_name'),
                'rules' => 'required',
                'errors' => [
                    'required' => lang('application_new_user_error_required'),
                ],
            ],
        ];
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels()
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
    protected function getLibraries()
    {
        return [];
    }

    /**
     * Defines which helpers should be loaded.
     *
     * @return array;
     */
    protected function getHelpers()
    {
        return [];
    }
}