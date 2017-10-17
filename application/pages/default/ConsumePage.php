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
        require_once APPPATH . '/pages/default/ApiBuyPage.php';

        ob_start();
        ?><script>
        var colorType = {
            "info": {
                "class": "mdl-color--blue-400",
                "icon": "done",
                "timeout": 2000
            },
            "error": {
                "class": "mdl-color--red-400",
                "icon": "error",
                "timeout": 4000
            },
            "warning": {
                "class": "mdl-color--amber-400",
                "icon": "warning",
                "timeout": 4000
            },
            "notice": {
                "class": "mdl-color--green-400",
                "icon": "done",
                "timeout": 2000
            }
        };

        purchase = function ($button) {
            $.ajax({
                url: "<?=site_url($this->data['group'] . '/ApiBuy')?>",
                data: {
                    id: $button.data('id')
                },
                type: "POST",
                dataType: "json"
            })
                .done(function (json) {
                    var message, status;

                    switch (json.status) {
                        // On success
                        case '<?=ApiFrame::STATUS_SUCCESS?>':
                            status = 'notice';
                            message = '<?=lang('transactions_ajax_message_success')?>'
                                .replace('[name]', json.name)
                                .replace('[newAmount]', json.newAmount)
                                .replace('[amount]', json.amount);

                            $button.closest('tr').children('.amount').text(json.newAmount); // update the amount
                            break;
                        // Error
                        case '<?=ApiFrame::STATUS_ERROR?>':
                            status = 'error';
                            switch (json.<?=ApiFrame::STATUS_ERROR?>) {
                                case '<?=ApiBuyPage::DATABASE_ERROR?>':
                                    message = '<?=lang('transactions_ajax_message_database_error')?>';
                                    break;
                                case '<?=ApiBuyPage::INVALID_ARGUMENT?>':
                                case '<?=ApiBuyPage::USER_NOT_FOUND?>':
                                    message = '<?=lang('transactions_ajax_message_invalid_request')?>';
                                    break;
                                case '<?=ApiBuyPage::STATUS_ACCESS_DENIED?>':
                                    message = '<?=lang('transactions_ajax_message_access_denied')?>';
                                    break;
                                case '<?=ApiBuyPage::STATUS_INTERNAL_SERVER_ERROR?>':
                                    message = '<?=lang('transactions_ajax_message_internal_server_error')?>';
                                    break;
                                default:
                                    message = '<?=lang('transactions_ajax_message_unknown_error')?>';
                                    break;
                            }
                            break;
                        default:
                            status = 'error';
                            message = '<?=lang('transactions_ajax_message_unknown_error')?>';
                            break;
                    }

                    var data = {
                        timeout: colorType[status].timeout,
                        message: message
                    };

                    var snackbarContainer = document.querySelector('#snackbar > .'+status);
                    snackbarContainer.MaterialSnackbar.showSnackbar(data); // Show the snackbar
                })
                .fail(function (xhr, status, errorMessage) {
                    alert(errorMessage);
                });
        };

        var touchTime = 0;
        $('.buy').on('click', function() {
            if(touchTime === 0) {
                //set first click
                touchTime = new Date().getTime();
            } else {
                //compare first click to this click and see if they occurred within double click threshold
                if(((new Date().getTime())-touchTime) < 800) {
                    //double click occurred
                    purchase($(this));
                    touchTime = 0;
                } else {
                    //not a double click so set as a new first click
                    touchTime = new Date().getTime();
                }
            }
        });

        </script><?php
        $this->addScript(ob_get_contents());
        ob_clean();

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

        $this->setData('tabs', $tabs);

        $fields = [
            'login_id' => Login::FIELD_LOGIN_ID,
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
            'amount' => Consumption::FIELD_AMOUNT,
        ];
        $this->setData('fields', $fields);

        $this->setData('myId', getLoggedInLoginId($this->ci->session));
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