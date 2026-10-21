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
class GiveUserPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'give-user'
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
        $authorId = getLoggedInLoginId(session());
        $name = model(User::class)->getName($loginId);
        $amount = set_value('amount');

        $success = model(Consumption::class)->change($loginId, $authorId, $amount, Transaction::TYPE_UPGRADE);
        if ($success) {
            $this->addSuccessMessage(sprintf(lang('Admin.application_give_user_success'), '<i>' . $name . '</i>', $amount));
        } else {
            $this->addDangerMessage(lang('Admin.application_server_error'));
        }
    }

    protected function getFormValidationRules()
    {
        return [
            [
                'field' => 'amount',
                'label' => lang('Admin.application_give_user_amount'),
                'rules' => [
                    'required',
                    'integer',
                    'greater_than[0]',
                ],
                'errors' => [
                    'required' => lang('Admin.application_give_user_error_amount_required'),
                    'integer' => lang('Admin.application_give_user_error_not_integer'),
                    'greater_than[0]' => lang('Admin.application_give_user_error_amount_not_positive'),
                ]
            ],
        ];
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $loginId = $this->getDataKey('subpage');

        // Get the users name.
        $name = model(User::class)->getName($loginId);
        $this->setData('name', $name);

        // Get the current amount of consumptions
        $amount = model(Consumption::class)->get($loginId);
        $this->setData('amount', $amount);
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
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