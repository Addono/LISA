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

class TransactionsPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'transactions'
        ];
    }

    public function hasAccess(): bool
    {
        return isLoggedInAndHasRole([Role::ROLE_ADMIN]);
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
        $transactions = model(User_Transaction::class)->getAll();
        $this->setData('transactions', $transactions);

        $fields = [
            'author' => 'author_name',
            'subject' => 'subject_name',
            'amount' => Consumption::FIELD_AMOUNT,
            'delta' => Transaction::FIELD_DELTA,
            'time' => Transaction::FIELD_TIME,
        ];
        $this->setData('fields', $fields);

        $this->addScript(
            '<script>
                        $(document).ready(function() {
                            $(".data-table-responsive").DataTable({
                                responsive: true,
                                order: [4, "desc"]
                            });
                        });
                    </script>'
        );
    }

    /**
     * Function which is called after the views are rendered.
     */
    public function afterView()
    {
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            Role::class,
            User_Transaction::class,
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