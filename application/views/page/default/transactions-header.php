<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
?>

<div class="col-md-12 vmargin col-md-offset-0">
    <div class="container">
        <!-- Tabs with icons on Card -->
        <div class="card card-nav-tabs">
            <div class="header header-primary">
                <div class="text-center">
                    <h4><?=lang('transactions_title')?></h4>
                </div>
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs" data-tabs="tabs"><li class="active">
                                <a href="#ordered-first-name" data-toggle="tab">
                                    <?=lang('transactions_subtitle_subject')?>
                                </a>
                            </li><li class="">
                                <a href="#ordered-last-name" data-toggle="tab">
                                    <?=lang('transactions_subtitle_author')?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="content">
                    <div class="tab-content text-center"><div class="tab-pane active" id="ordered-first-name">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><?=lang('transactions_table_header_author')?></th>
                                <th><?=lang('transactions_table_header_subject')?></th>
                                <th><?=lang('transactions_table_header_amount')?></th>
                                <th><?=lang('transactions_table_header_delta')?></th>
                                <th><?=lang('transactions_table_header_time')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($transactions['subject'] as $t) { ?>
                                <tr>
                                    <td><?=$t[$fields['author']]?></td>
                                    <td><?=$t[$fields['subject']]?></td>
                                    <td><?=$t[$fields['amount']]?></td>
                                    <td><?=($d=$t[$fields['delta']])>0?'+'.$d:$d?></td>
                                    <td><?=$t[$fields['time']]?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="ordered-last-name">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th><?=lang('transactions_table_header_author')?></th>
                                <th><?=lang('transactions_table_header_subject')?></th>
                                <th><?=lang('transactions_table_header_amount')?></th>
                                <th><?=lang('transactions_table_header_delta')?></th>
                                <th><?=lang('transactions_table_header_time')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($transactions['author'] as $t) { ?>
                                <tr>
                                    <td><?=$t[$fields['author']]?></td>
                                    <td><?=$t[$fields['subject']]?></td>
                                    <td><?=$t[$fields['amount']]?></td>
                                    <td><?=($d=$t[$fields['delta']])>0?'+'.$d:$d?></td>
                                    <td><?=$t[$fields['time']]?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
