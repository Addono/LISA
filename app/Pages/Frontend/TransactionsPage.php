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

class TransactionsPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'transactions-header',
        ];
    }

    public function hasAccess(): bool
    {
        return isLoggedInAndHasRole(Role::ROLE_USER);
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
        $loginId = getLoggedInLoginId(session());

        $limit = $this->getDataKey('subpage') ?? 100;
        if (!(int) $limit  || $limit <= 0) {
            $limit = null;
        }
        $this->setData('limit', $limit);

        $transactions['subject'] = model(User_Transaction::class)->getAllForSubjectId($loginId, $limit);
        $transactions['author'] = model(User_Transaction::class)->getAllForAuthorId($loginId, $limit);
        $this->setData('transactions', $transactions);

        $fields = [
            'author' => 'author_name',
            'subject' => 'subject_name',
            'amount' => Consumption::FIELD_AMOUNT,
            'delta' => Transaction::FIELD_DELTA,
            'time' => Transaction::FIELD_TIME,
            'time_unix' => Transaction::FIELD_TIME.'_unix',
        ];
        $this->setData('fields', $fields);

        $sumDeltaByWeek = model(Transaction::class)->getSumDeltaSubjectIdByWeek($loginId);


        /** @var Graph $graphLibrary */
        $graphLibrary = (new \App\Libraries\Graph());
        $this->addScript($graphLibrary->includeJsLibrary());
        $this->addScript($graphLibrary->getGraphForTransactions($sumDeltaByWeek));

        $this->addScript(
    "<script>
            $(document).ready(function() {
                $(\"input[type=radio\").change(function() {
                    $(this).parents('ul').children('.active').removeClass('active');
                    $(this).parents('li').addClass('active');
                });

                $(\".pagination.horizontal-radio\").click(function() {
                    $(this).find('input[type=radio]').attr('checked', true);
                });
            });
        </script>"
        );
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