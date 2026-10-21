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
 * @date 5-4-2017
 */

/**
 * Class TransactionApiPage
 */
class ApiResetPage extends ApiFrame
{

    const USER_NOT_FOUND = 'userNotFound';
    const INVALID_ARGUMENT = 'invalidArgument';
    const DATABASE_ERROR = 'databaseError';


    function call()
    {
        $id = set_value('id');

        // Check that id is set.
        if ($id === '' || ! is_numeric($id)) {
            $this->setError(self::INVALID_ARGUMENT);
            $this->setResult('id', $id);
            return;
        }

        // Check that an valid id is passed.
        if (!model(Role_LoginRole::class)->userHasRole($id, Role::ROLE_USER)) {
            // error invalid user specified.
            $this->setError(self::USER_NOT_FOUND);
            return;
        }

        $result = model(LoginReset::class)->add($id);

        if ($result) {
            $this->setStatus(ApiFrame::STATUS_SUCCESS);
            $this->setResult('link', site_url('Reset/'.$result));
        } else {
            $this->setError(self::DATABASE_ERROR);
        }
    }

    function hasAccess(): bool
    {
        // Ensure that the request originates from a admin.
        return isLoggedInAndHasRole([Role::ROLE_ADMIN]);
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            LoginReset::class,
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