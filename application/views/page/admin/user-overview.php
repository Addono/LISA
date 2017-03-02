<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-02-2017
 */
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?=lang('application_user_overview_title')?></h1>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=lang('application_user_overview_table_title')?>
            </div>
            <div class="panel-body">
                <div class="table-responsive" style="overflow-x:inherit">
                    <table width="100%" class="table table-striped table-bordered table-hover data-table-responsive">
                        <thead>
                            <tr>
                                <th><?=lang('application_user_overview_table_header_username')?></th>
                                <th><?=lang('application_user_overview_table_header_first_name')?></th>
                                <th><?=lang('application_user_overview_table_header_last_name')?></th>
                                <th><?=lang('application_user_overview_table_header_email')?></th>
                                <th><?=lang('application_user_overview_table_header_roles')?></th>
                                <th><?=lang('application_user_overview_table_header_actions')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($userData as $u) { ?>
                            <tr>
                                <td><?=$u[$userDataFields['username']]?></td>
                                <td><?=$u[$userDataFields['first_name']]?></td>
                                <td><?=$u[$userDataFields['last_name']]?></td>
                                <td><?=$u[$userDataFields['email']]?></td>
                                <td>
                                <?php foreach ($u[$userDataFields['roles']] as $role) { ?>
                                    <?=$role[$userDataFields['role_name']]?>
                                <?php } ?>
                                </td>
                                <td style="display:flex;justify-content:center;align-items:center">
                                    <div class="button-tooltip">
                                        <a href="<?=site_url($group.'/GiveUser/'.$u[$userDataFields['id']])?>">
                                            <button type="button" class="btn btn-warning btn-circle">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </a>
                                        <a href="<?=site_url($group.'/User/'.$u[$userDataFields['id']])?>">
                                            <button type="button" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="left" title="<?=lang('application_user_overview_tooltip_edit_user')?>">
                                                <i class="glyphicon glyphicon-edit"></i>
                                            </button>
                                        </a>
                                    </div>
                                </td>
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
