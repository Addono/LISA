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

        $('.buy').dblclick(function () {
            var $button = $(this);

            $.ajax({
                url: "<?=site_url($this->data['group'] . '/ApiBuy')?>",
                data: {
                    id: $button.data('id')
                },
                type: "POST",
                dataType: "json"
            })
                .done(function (json) {
                    var message;

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
        });

        </script><?php
        $this->addScript(ob_get_contents());
        ob_clean();

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