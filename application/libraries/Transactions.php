<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions {

    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function getJavascript($group): string
    {
        require_once APPPATH . '/pages/default/TransactionApiPage.php';

        ob_start(); ?>
        <script src="<?=base_url('node_modules/visibilityjs/lib/visibility.core.js')?>" type="text/javascript"></script>
        <script src="<?=base_url('node_modules/visibilityjs/lib/visibility.timers.js')?>" type="text/javascript"></script>
        <script>
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

            updateData = function(data = null) {
                if (data==null) {
                    $.ajax({
                        url: "<?=site_url($group . '/TransactionApi')?>",
                        data: {
                            action: 'updateData',
                        <?=$this->ci->security->get_csrf_token_name()?>: "<?=$this->ci->security->get_csrf_hash()?>"
                    },
                        type: "POST",
                        dataType: "json",
                    }).done(function (json) {
                        updateData(json.updated_data);
                    });
                    return;
                }
                for (row of data) {
                    var $ajaxLoginIdClass = $('.ajax-login-id-'+row['login_id']);
                    var $children = $ajaxLoginIdClass.children('.amount');

                    $children.text(row['amount']); // Update the amount for all users
                    if (row['amount'] < 0) { // Mark the user if their balance becomes negative
                        $ajaxLoginIdClass.addClass('text-danger');
                    }
                }
            };

            Visibility.every(10 * 60 * 1000, function() {
                updateData();
            });

            purchase = function ($button) {
                $.ajax({
                    url: "<?=site_url($group . '/TransactionApi')?>",
                    data: {
                        id: $button.data('id'),
                        action: 'buy',
                <?=$this->ci->security->get_csrf_token_name()?>: "<?=$this->ci->security->get_csrf_hash()?>"
            },
                type: "POST",
                dataType: "json"
            }).done(function (json) {
                    var message, status;

                    switch (json.status) {
                        // On success
                        case '<?=ApiFrame::STATUS_SUCCESS?>':
                            status = 'notice';
                            message = '<?=lang('transactions_ajax_message_success')?>'
                                .replace('[name]', json.name)
                                .replace('[newAmount]', json.new_amount)
                                .replace('[amount]', json.amount);

                            updateData(json.updated_data); // Update the amount of all users.
                            break;
                        // Error
                        case '<?=ApiFrame::STATUS_ERROR?>':
                            status = 'error';
                            switch (json.<?=ApiFrame::STATUS_ERROR?>) {
                                case '<?=TransactionApiPage::DATABASE_ERROR?>':
                                    message = '<?=lang('transactions_ajax_message_database_error')?>';
                                    break;
                                case '<?=TransactionApiPage::INVALID_ARGUMENT?>':
                                case '<?=TransactionApiPage::USER_NOT_FOUND?>':
                                    message = '<?=lang('transactions_ajax_message_invalid_request')?>';
                                    break;
                                case '<?=TransactionApiPage::STATUS_ACCESS_DENIED?>':
                                    message = '<?=lang('transactions_ajax_message_access_denied')?>';
                                    break;
                                case '<?=TransactionApiPage::STATUS_INTERNAL_SERVER_ERROR?>':
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
                }).fail(function (xhr, status, errorMessage) {
                    if (xhr.status === 403) {
                        alert("<?=lang('transactions_ajax_message_timed_out')?>");
                        location.reload();
                        return;
                    }

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
        $result = ob_get_contents();
        ob_clean();

        return $result;
    }

    public function getBuyButtonHtml(int $loginId): string
    {
        return  '<a href="javascript:void(0)" data-id="' . $loginId . '" class="buy btn btn-primary btn-no-margin">-1</a>"';
    }

    public function getSnackbarFooterHtml(): string
    {
        return
        '<div id="snackbar">
            <div class="error mdl-color--red-400 mdl-js-snackbar mdl-snackbar">
                <div class="mdl-snackbar__text"></div>
                <button class="mdl-snackbar__action" type="button"></button>
            </div>
            <div class="notice mdl-color--green-400 mdl-js-snackbar mdl-snackbar">
                <div class="mdl-snackbar__text"></div>
                <button class="mdl-snackbar__action" type="button"></button>
            </div>
        </div>';
    }
}