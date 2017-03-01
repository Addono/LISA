<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?=lang('application_user_title')?></h1>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=lang('application_user_change_name')?>
            </div>
            <div class="panel-body">
                <form role="form" method="post">
                    <input type="hidden" name="type" value="name" />
                    <div class="form-group">
                    <label><?=lang('application_user_first_name')?></label>
                    <input type="text" name="first-name" class="form-control" placeholder="<?=$userData[$userDataFields['first_name']]?>">
                    <p class="help-block"><?=lang('application_user_first_name_help')?></p>
                    </div>
                    <div class="form-group">
                        <label><?=lang('application_user_last_name')?></label>
                        <input type="text" name="last-name" class="form-control" placeholder="<?=$userData[$userDataFields['last_name']]?>">
                        <p class="help-block"><?=lang('application_user_last_name_help')?></p>
                    </div>
                    <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block"><?=lang('application_user_submit')?></button>
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=lang('application_user_change_password')?>
            </div>
            <div class="panel-body">
                <form role="form" method="post">
                    <input type="hidden" name="type" value="password" />
                    <div class="form-group">
                        <label><?=lang('application_user_password')?></label>
                        <input type="password" name="password" class="form-control">
                        <p class="help-block"><?=lang('application_user_password_help')?></p>
                    </div>
                    <div class="form-group">
                        <label><?=lang('application_user_confirm_password')?></label>
                        <input type="password" name="confirm-password" class="form-control">
                        <p class="help-block"><?=lang('application_user_confirm_password_help')?></p>
                    </div>
                    <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block"><?=lang('application_user_submit')?></button>
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=lang('application_user_change_email')?>
            </div>
            <div class="panel-body">
                <form role="form" method="post">
                    <input type="hidden" name="type" value="email" />
                    <div class="form-group">
                        <label><?=lang('application_user_email')?></label>
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="email" name="email" class="form-control" placeholder="<?=$userData[$userDataFields['email']]?>">
                        </div><!-- ./input-group -->
                        <p class="help-block"><?=lang('application_user_email_help')?></p>
                    </div>
                    <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block"><?=lang('application_user_submit')?></button>
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>

