<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 6-2-2017
 */

class TestPage extends PageFrame
{

    public function getViews(): array
    {
        return [
            'test-header',
            'intersection',
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

        $('.buy').click(function () {
            $.ajax({
                url: "<?=site_url($this->data['group'] . '/ApiBuy')?>",
                data: {
                    id: this.id
                },
                type: "POST",
                dataType: "json"
            })
                .done(function (json) {
                    var status;
                    var message;

                    switch (json.status) {
                        case '<?=ApiFrame::STATUS_SUCCESS?>':
                            status = 'notice';
                            message = '<?=lang('transactions_ajax_message_success')?>'
                                .replace('[name]', json.name)
                                .replace('[newAmount]', json.newAmount)
                                .replace('[amount]', json.amount);
                            break;
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

                    var snackbarContainer = document.querySelector('#snackbar');

                    if (status === 'notice') {

                    }

                    var data = {
                        timeout: colorType[status].timeout,
                        message: message
                    };

                    snackbarContainer.classList.add(colorType[status].class); // Add the coloring
                    snackbarContainer.MaterialSnackbar.showSnackbar(data); // Show the snackbar
                })
                .fail(function (xhr, status, errorMessage) {
                    alert(errorMessage);
                });
        });

    </script><?php
        $this->addScript(ob_get_contents());
        ob_clean();
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