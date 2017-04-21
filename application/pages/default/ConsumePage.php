<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
class ConsumePage extends PageFrame
{
    const MIN = 0;
    const MAX = 5;

    /**
     * The views to be shown.
     *
     * @return array|null Array with the names of the views inbetween the header and footer, null if no views should be shown.
     */
    public function getViews(): array
    {
        return [
            'consume-header',
            'intersection'
        ];
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $userRoleId = $this->ci->Role->getRoleIdFromRoleName(Role::ROLE_USER);
        $users = $this->ci->User_Consumption_LoginRole->get($userRoleId);
        $this->setData('users', $users);

        $fields = [
            'login_id' => Login::FIELD_LOGIN_ID,
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
            'amount' => Consumption::FIELD_AMOUNT,
        ];
        $this->setData('fields', $fields);

        $this->setData('min', self::MIN);
        $this->setData('max', self::MAX);
    }

    public function onFormSuccess()
    {
        $amount = set_value('amount');
        $authorId = getLoggedInLoginId($this->ci->session);

        // Check if the given amounts are valid.
        foreach ($amount as $loginId => $delta) {
            if ($delta < self::MIN || $delta > self::MAX) {
                $this->addDangerMessage(lang('consume_form_invalid_amount'));
                return;
            }
        }

        // Check if the given users are all actually useres.
        $userRoleId = $this->ci->Role->getRoleIdFromRoleName(Role::ROLE_USER);
        foreach ($amount as $loginId => $delta) {
            if (! $this->ci->LoginRole->exists($loginId, $userRoleId)) {
                $this->addDangerMessage(lang('consume_form_invalid_user'));
                return;
            }
        }

        foreach($amount as $loginId => $delta) {
            // Skip all users for whom noting changed.
            if ($delta == 0) {
                continue;
            }

            $name = $this->ci->User->getName($loginId);
            $userSuccess = $this->ci->Consumption->change($loginId, $authorId, -$delta);
            if ($userSuccess) {
                $this->addSuccessMessage(sprintf(lang('consume_form_user_success'), $delta, $name));
            } else {
                $this->addDangerMessage(sprintf(lang('consume_form_user_failure'), $delta, $name));
            }
        }
    }

    /**
     * If the current user has access to this page.
     *
     * @return boolean
     */
    public function hasAccess(): bool
    {
        return isLoggedInAndHasRole($this->ci, [Role::ROLE_USER]);
    }

    /**
     * The form validation rules.
     *
     * @return array|bool
     */
    protected function getFormValidationRules()
    {
        return is_array(set_value('amount'));
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
            Consumption::class,
            User::class,
            Role::class,
            LoginRole::class,
            User_Consumption_LoginRole::class,
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