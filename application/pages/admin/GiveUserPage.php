<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

/**
 * Class UserPage
 */
class GiveUserPage extends PageFrame
{

    public function getViews()
    {
        return [
            'give-user'
        ];
    }

    public function isVisible()
    {
        return true;
    }

    public function hasAccess()
    {
        // Check if the user is logged in and has the required rights.
        $hasRights = isLoggedInAndHasRole($this->ci, [Role::ROLE_ADMIN]);

        // Check if the given login id is valid and has a user account connected to it.
        $loginId = $this->params['subpage'];
        $validLoginId = $loginId !== null && $this->ci->Login_User->exists($loginId);

        return $hasRights && $validLoginId;
    }

    public function onFormSuccess()
    {
        $loginId = $this->params['subpage'];
        $name = $this->ci->User->getName($loginId);
        $amount = set_value('amount');

        $success = $this->ci->Consumption->change($loginId, $amount);
        if ($success) {
            $this->addSuccessMessage(sprintf(lang('application_give_user_success'), '<i>' . $name . '</i>', $amount));
        } else {
            $this->addDangerMessage(lang('application_server_error'));
        }
    }

    protected function getFormValidationRules()
    {
        return [
            [
                'field' => 'amount',
                'label' => lang('application_give_user_amount'),
                'rules' => [
                    'required',
                    'integer',
                    'greater_than[0]',
                ],
                'errors' => [
                    'required' => lang('application_give_user_error_amount_required'),
                    'integer' => lang('application_give_user_error_not_integer'),
                    'greater_than[0]' => lang('application_give_user_error_amount_not_positive'),
                ]
            ],
        ];
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $loginId = $this->params['subpage'];

        // Get the users name.
        $name = $this->ci->User->getName($loginId);
        $this->setData('name', $name);

        // Get the current amount of consumptions
        $amount = $this->ci->Consumption->get($loginId);
        $this->setData('amount', $amount);
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels()
    {
        return [
            User::class,
            Role::class,
            Login_User::class,
            Consumption::class,
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