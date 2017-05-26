<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
?>

<div class="col-md-12 vmargin col-md-offset-0">
    <div class="container">
        <div class="card card-signup">
            <div class="header header-primary text-center">
                <h4><?=lang('consume_title')?></h4>
                <p><?=lang('consume_description')?></p>
            </div>
            <p class="text-divider"></p>
            <div class="content">
                <div class="input-group form-group label-floating center-block">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?=lang('consume_table_head_name')?></th>
                                <th><?=lang('consume_table_head_credit')?></th>
                                <th><?=lang('consume_table_head_consumptions')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($users as $u) { ?>
                            <tr>
                                <td><?=$u[$fields['first_name']] . ' ' . $u[$fields['last_name']]?></td>
                                <td class="amount"><?=$u[$fields['amount']]?></td>
                                <td>
                                    <a href="javascript:void(0)" data-id="<?=$u[$fields['login_id']]?>" class="buy btn btn-primary btn-no-margin">-1</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="snackbar">
        <div class="error mdl-color--red-400 mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>
        <div class="notice mdl-color--green-400 mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>
    </div>
</div>
