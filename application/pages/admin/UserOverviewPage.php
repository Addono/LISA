<?php

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-2-2017
 */
class UserOverviewPage extends PageFrame
{

    /**
     * The views to be shown as header.
     *
     * @return array|null
     */
    public function getViews(): array
    {
        return [
            'user-overview'
        ];
    }

    /**
     * Function which is called after construction and before the views are rendered.
     */
    public function beforeView()
    {
        // Get the user information of all users and parse it to the view.
        $userDataFields = [
            'id' => Login::FIELD_LOGIN_ID,
            'username' => Login::FIELD_USERNAME,
            'first_name' => User::FIELD_FIRST_NAME,
            'last_name' => User::FIELD_LAST_NAME,
            'email' => User::FIELD_EMAIL,
            'roles' => 'roles',
            'role_name' => Role::FIELD_ROLE_NAME,
        ];
        $userData = $this->ci->Login_User_LoginRole_Role->getAllUserData();

        $this->setData('userData', $userData);
        $this->setdata('userDataFields', $userDataFields);

        $this->addScript(
            '<script>
                    $(document).ready(function() {
                $(\'.data-table-responsive\').DataTable({
                    responsive: true
                });
            });
            </script>'
        );
        $this->addScript(
            '<script>
            $(".button-tooltip").tooltip({
                selector: "[data-toggle=tooltip]",
                container: "body"
            })
            </script>'
        );
        require_once APPPATH . 'pages/admin/ApiResetPage.php';
        $this->addScript(
    '<script>
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

            reset = function ($button) {
                $.ajax({
                    url: "' . site_url($this->data['group'] . '/ApiReset') . '",
                    data: {
                        id: $button.data("id")
                    },
                    type: "POST",
                    dataType: "json"
                })
                    .done(function (json) {
                    var message, status;

                    switch (json.status) {
                        // On success
                        case "' . ApiFrame::STATUS_SUCCESS . '":
                            status = "notice";
                            message = null;

                            alert(json.link);
                            break;
                        // Error
                        case "' . ApiFrame::STATUS_ERROR . '":
                            status = "error";
                            switch (json.' . ApiFrame::STATUS_ERROR . ') {
                                case "' . ApiResetPage::DATABASE_ERROR . '":
                                    message = "' . lang('transactions_ajax_message_database_error') . '";
                                    break;
                                case "' . ApiResetPage::INVALID_ARGUMENT . '":
                                case "' . ApiResetPage::USER_NOT_FOUND . '":
                                    message = "' . lang('transactions_ajax_message_invalid_request') . '";
                                    break;
                                case "' . ApiResetPage::STATUS_ACCESS_DENIED . '":
                                    message = "' . lang('transactions_ajax_message_access_denied') . '";
                                    break;
                                case "' . ApiResetPage::STATUS_INTERNAL_SERVER_ERROR . '":
                                    message = "' . lang('transactions_ajax_message_internal_server_error') . '";
                                    break;
                                default:
                                    message = "' . lang('transactions_ajax_message_unknown_error') . '";
                                    break;
                            }
                            break;
                        default:
                            status = "error";
                            message = "' . lang('transactions_ajax_message_unknown_error') . '";
                            break;
                    }
                    
                    if (message != null) {
                        var data = {
                            timeout: colorType[status].timeout,
                            message: message
                        };
    
                        var snackbarContainer = document.querySelector("#snackbar > ."+status);
                        snackbarContainer.MaterialSnackbar.showSnackbar(data); // Show the snackbar
                    }
                });
            }
            
            $(".js_reset").on("click", function() {
                reset($(this));
            });
        </script>'
        );
    }

    /**
     * If the current user has access to this page.
     *
     * @return bool
     */
    public function hasAccess(): bool
    {
        return isLoggedInAndHasRole($this->ci, [Role::ROLE_ADMIN]);
    }

    /**
     * The form validation rules.
     *
     * @return array
     */
    protected function getFormValidationRules()
    {
        return null;
    }

    /**
     * Defines which models should be loaded.
     *
     * @return array;
     */
    protected function getModels(): array
    {
        return [
            LoginRole::class,
            Login_User_LoginRole_Role::class,
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