<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 11-3-2017
 */
?>
<div class="col-md-6 vmargin">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                <div class="card card-signup">
                    <?=form_open()?>
                    <div class="header header-primary text-center">
                        <h4><?=lang('reset_reset')?></h4>
                    </div>
                    <p class="text-divider"></p>
                    <div class="content">

                        <div class="input-group form-group label-floating">
                            <span class="input-group-addon">
                                <i class="fa fa-key fa-lg"></i>
                            </span>
                            <label class="control-label"><?=lang('form_field_new_password')?></label>
                            <input type="password" name="password" class="form-control" />
                        </div>

                        <div class="input-group form-group label-floating">
                            <span class="input-group-addon">
                                <i class="fa fa-key fa-lg"></i>
                            </span>
                            <label class="control-label"><?=lang('form_field_confirm_password')?></label>
                            <input type="password" name="confirm-password" class="form-control" />
                        </div>
                    </div>
                    <div class="footer text-center">
                        <input type="submit" value="<?=lang('form_field_submit')?>" class="btn btn-primary btn-lg">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
