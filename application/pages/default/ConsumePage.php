<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
class ConsumePage extends PageFrame
{

    /**
     * The views to be shown.
     *
     * @return array Array with the names of the views inbetween the header and footer, null if no views should be shown.
     */
    public function getViews(): array
    {
        return [
            'consume-header',
        ];
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        // Load the transactions Javascript
        $this->ci->load->library('Transactions');
        $this->addScript($this->ci->transactions->getJavascript($this->data['group']));

        $roles = $this->ci->Role->getRoles();
        foreach ($roles as $role) {
            if ($role[Role::FIELD_ROLE_NAME] !== Role::ROLE_ADMIN) {
                $allUsersByRole[$role[Role::FIELD_ROLE_NAME]] = $this->ci->User_Consumption_LoginRole->get($role[Role::FIELD_ROLE_ID]);
            }
        }

        $byFirstName = $allUsersByRole[Role::ROLE_USER];
        usort($byFirstName, function($a, $b) {
            return strcmp(strtolower($a[User::FIELD_FIRST_NAME]), strtolower($b[User::FIELD_FIRST_NAME]));
        });
        $byLastName = $allUsersByRole[Role::ROLE_USER];
        usort($byLastName, function($a, $b) {
            return strcmp(strtolower($a[User::FIELD_LAST_NAME]), strtolower($b[User::FIELD_LAST_NAME]));
        });
        $byAmount = $allUsersByRole[Role::ROLE_USER];
        usort($byAmount, function($a, $b) {
            return $b['amount'] - $a['amount'];
        });
        $byLatest = $allUsersByRole[Role::ROLE_USER];
        usort($byLatest, function($a, $b) {
            $timeStamp1 = strtotime($a['time']);
            $timeStamp2 = strtotime($b['time']);
            return $timeStamp2 - $timeStamp1;
        });;

        $tabs = [
            'ordered-first-name' => [
                'title' => lang('consume_group_first_name'),
                'icon' => 'fa-sort-alpha-asc',
                'users' => $byFirstName,
            ],
            'ordered-last-name' => [
                'title' => lang('consume_group_last_name'),
                'icon' => 'fa-sort-alpha-asc',
                'users' => $byLastName,
            ],
            'amount' => [
                'title' => lang('consume_group_amount_name'),
                'icon' => 'fa-sort-amount-desc',
                'users' => $byAmount,
            ],
            'recent' => [
                'title' => lang('consume_group_recent_name'),
                'icon' => 'fa-clock-o',
                'users' => $byLatest,
            ],
        ];

        foreach ($allUsersByRole as $roleName => $users) {
            if ($roleName !== Role::ROLE_USER) {
                // Intersect the roles of user and every non-admin or user role. (1)
                $groupUsers = [];
                foreach ($users as $user1) {
                    foreach ($allUsersByRole[Role::ROLE_USER] as $user2) {
                        if ($user1[Login::FIELD_LOGIN_ID] == $user2[Login::FIELD_LOGIN_ID]) {
                            $groupUsers[] = $user1;
                            break;
                        }
                    }
                }

                $tabs[$roleName] = [
                    'title' => $roleName,
                    'icon' => '',
                    'users' => $groupUsers,
                ];
            }
        }

        $fields = [
            'login_id' => Login::FIELD_LOGIN_ID,
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
            'amount' => Consumption::FIELD_AMOUNT,
        ];
        $this->setData('fields', $fields);
        $this->setData('myId', getLoggedInLoginId($this->ci->session));
        $this->setData('tabs', $tabs);
    }

    /**
     * If the current user has access to this page.
     *
     * @return bool
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
        return false;
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

/*
 * 1) Running time is O(n*m) with n the amount of logins with the user role and m the amount of logins with the
 *      other evaluated role, this has to be done for every role (except user and admin). Hence this results in a total
 *      running time of O(n*m*r) with r the amount of roles. A more scalable way would be to sort all role groups
 *      and traverse them, only maintaining the users for each role whom are also present in the user role. Which would
 *      give a running time of O(r*\max(x; roles.has(x); size(x) * log (size(x))), caused by the sorting. The n log n
 *      running time is a lot better than the n^2 running time it now has.
 */
