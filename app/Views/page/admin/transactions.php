<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-02-2017
 */
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?=lang('Admin.application_transactions_title')?></h1>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=lang('Admin.application_transactions_table_title')?>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="overflow-x:inherit">
                    <table width="100%" class="table table-striped table-bordered table-hover data-table-responsive">
                        <thead>
                            <tr>
                                <th><?=lang('Admin.application_transactions_table_header_author')?></th>
                                <th><?=lang('Admin.application_transactions_table_header_subject')?></th>
                                <th><?=lang('Admin.application_transactions_table_header_amount')?></th>
                                <th><?=lang('Admin.application_transactions_table_header_delta')?></th>
                                <th><?=lang('Admin.application_transactions_table_header_time')?></th>
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
                <!-- /.table-responsive -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
</div>
