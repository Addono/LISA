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
 * Class ResetPasswordPage
 */
class ResetPasswordPage extends PageFrame
{

    public function getViews(): array
    {
        return [
        ];
    }

    protected function getFormValidationRules()
    {
        return false;
    }

    public function hasAccess(): bool
    {
        return isLoggedIn(session());
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        $id = getLoggedInLoginId(session());

        $key = model(LoginReset::class)->add($id);

        if ($key) {
            return redirect()->to(site_url('Reset/' . $key));
        } else {
            return redirect()->to(site_url('PageNotFound')); // @todo redirect to 500 page instead of 404.
        }
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
        return [];
    }
}