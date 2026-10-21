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
 * @date 26-2-2017
 */

class PageNotFoundPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            '404-header',
        ];
    }

    public function hasAccess(): bool
    {
        return true;
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [];
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