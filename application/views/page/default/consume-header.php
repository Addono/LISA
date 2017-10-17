<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
?>

<div class="col-md-12 col-md-offset-0">
    <div class="container">
        <!-- Tabs with icons on Card -->
        <div class="card card-nav-tabs">
            <div class="header header-primary">
                <div class="text-center">
                    <h4><?=lang('consume_title')?></h4>
                        <div class="row">
                            <div class="col-md-6">
                                <p><?=lang('consume_description')?></p>
                            </div>
                            <div class="col-md-6" style="border-left: 1px solid white">
                                <?=lang('consume_description_self')?>
                                <a href="javascript:void(0)" data-id="<?= $myId ?>" style="margin: 0 0 0 2em"
                                   class="buy btn btn-white">-1</a>
                            </div>
                        </div>
                </div>
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs" data-tabs="tabs"><?php
                        $count = 0;
                        foreach ($tabs as $id => $tab) {
                            // Skip all empty tabs.
                            if (count($tab['users']) === 0) {
                                continue;
                            }
                            ?><li class="<?=++$count==1?'active':''?>">
                                <a href="#<?=$id?>" data-toggle="tab">
                                    <i class="fa <?=$tab['icon']?>"></i>
                                    <?=$tab['title']?>
                                </a>
                            </li><?php
                        }
                        ?></ul>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="content">
                    <div class="tab-content text-center"><?php
                    $count = 0;
                    foreach ($tabs as $id => $tab) {
                        ?><div class="tab-pane<?= ++$count == 1 ? ' active' : '' ?>" id="<?=$id?>">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><?= lang('consume_table_head_name') ?></th>
                                <th><?= lang('consume_table_head_credit') ?></th>
                                <th><?= lang('consume_table_head_consumptions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($tab['users'] as $u) {
                            $amount = $u[$fields['amount']];
                            ?><tr class="<?=$amount<0?'text-danger':''?>">
                                    <td><?= $u[$fields['first_name']] . ' ' . $u[$fields['last_name']] ?></td>
                                    <td class="amount"><?=$amount?></td>
                                    <td>
                                        <a href="javascript:void(0)" data-id="<?= $u[$fields['login_id']] ?>"
                                           class="buy btn btn-primary btn-no-margin">-1</a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        </div><?php
                    }
                    ?></div>
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
