<?php  defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-02-2017
 */
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?=lang('application_new_user_title')?></h1>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=lang('application_new_user_form_title')?>
            </div>
            <div class="panel-body">
                <form role="form" action="<?=site_url($group.'/NewUser')?>" method="post">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?=lang('application_new_user_username')?></label>
                                <input type="text" name="username" class="form-control" value="<?=set_value('username')?>">
                                <p class="help-block"><?=lang('application_new_user_username_help')?></p>
                            </div>
                        </div><!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?=lang('application_new_user_email')?></label>
                                <div class="input-group">
                                    <span class="input-group-addon">@</span>
                                    <input type="email" name="email" class="form-control" value="<?=set_value('email')?>">
                                </div><!-- /.input-group -->
                                <p class="help-block"><?=lang('application_new_user_email_help')?></p>
                            </div>
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?=lang('application_new_user_password')?></label>
                                <input type="password" name="password" class="form-control">
                                <p class="help-block"><?=lang('application_new_user_password_help')?></p>
                            </div>
                        </div><!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?=lang('application_new_user_confirm_password')?></label>
                                <input type="password" name="confirm-password" class="form-control">
                                <p class="help-block"><?=lang('application_new_user_confirm_password_help')?></p>
                            </div>
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?=lang('application_new_user_first_name')?></label>
                                <input type="text" name="first-name" class="form-control" value="<?=set_value('first-name')?>">
                                <p class="help-block"><?=lang('application_new_user_first_name_help')?></p>
                            </div>
                        </div><!-- /.col-lg-6 -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label><?=lang('application_new_user_last_name')?></label>
                                <input type="text" name="last-name" class="form-control" value="<?=set_value('last-name')?>">
                                <p class="help-block"><?=lang('application_new_user_last_name_help')?></p>
                            </div>
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label><?=lang('application_name_user_roles')?></label>
                            <?php foreach($roles as $role) { ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="roles_<?=$role[$roleIdKey]?>" value="1"><?=$role[$roleNameKey]?>
                                    </label>
                                </div>
                            <?php } ?>
                            </div><!-- /.form-groupp -->
                        </div><!-- /.col-lg-12 -->
                    </div><!-- /.row -->
                    <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block"><?=lang('application_new_user_submit')?></button>
                </form><!-- /form -->
            </div><!-- /.panel-body -->
        </div><!-- /.panel -->
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->