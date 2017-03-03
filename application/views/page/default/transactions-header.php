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
                <h4><?=lang('transactions_title')?></h4>
            </div>
            <p class="text-divider"></p>
            <div class="content">
                <div class="input-group form-group label-floating center-block">
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
                        <?php foreach ($transactions as $t) { ?>
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
