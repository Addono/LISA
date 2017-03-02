<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 2-3-2017
 */
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?=sprintf(lang('application_give_user_title'), '<i>'.$name.'</i>')?></h1>
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=sprintf(lang('application_give_user_subtitle'), '<i>'.$name.'</i>')?>
            </div>
            <div class="panel-body">
                <form role="form" method="post">
                    <input type="hidden" name="type" value="name" />
                    <div class="form-group">
                        <label><?=lang('application_give_user_amount')?></label>
                        <input type="number" name="amount" class="form-control" placeholder="<?=sprintf(lang('application_give_user_amount_placeholder'), $amount)?>">
                        <p class="help-block"><?=lang('application_give_user_amount_help')?></p>
                    </div>
                    <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block"><?=lang('application_give_user_submit')?></button>
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
