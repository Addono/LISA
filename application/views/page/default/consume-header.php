<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
?>
<div class="col-md-12 vmargin">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-lg-offset-0">
                <div class="card card-signup">
                    <?=form_open()?>
                    <div class="header header-primary text-center">
                        <h4><?=lang('consume_title')?></h4>
                        <?=showMessages($messages)?>
                    </div>
                    <p class="text-divider"></p>
                    <div class="content">

                        <div class="input-group form-group label-floating">

                        </div>

                        <div class="input-group form-group label-floating">
                            <span class="input-group-addon">
                                <i class="fa fa-key fa-lg"></i>
                            </span>
                            <label class="control-label"><?=lang('login_password')?></label>
                            <input type="password" name="password" class="form-control" />
                        </div>
                    </div>
                    <div class="footer text-center">
                        <input type="submit" value="<?=lang('login_submit')?>" class="btn btn-simple btn-primary btn-lg">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
