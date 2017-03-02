<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */
?>

<div class="col-md-12 vmargin col-md-offset-0">
    <div class="container">
        <div class="card card-signup">
            <?=form_open()?>
            <div class="header header-primary text-center">
                <h4><?=lang('consume_title')?></h4>
            </div>
            <p class="text-divider"></p>
            <div class="content">
                <div class="input-group form-group label-floating center-block">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?=lang('consume_table_head_name')?></th>
                                <th><?=lang('consume_table_head_credit')?></th>
                                <th><?=lang('consume_table_head_consumptions')?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($users as $u) { ?>
                            <tr>
                                <th><?=$u[$fields['first_name']] . ' ' . $u[$fields['last_name']]?></th>
                                <th><?=$u[$fields['amount']]?></th>
                                <th>
                                    <ul class="pagination pagination-primary horizontal-radio">
                                        <?php for($i = $min; $i <= $max; $i++) { ?>
                                        <li <?=$i===0?'class="active"':''?>>
                                            <a>
                                                <label>
                                                    <input type="radio" name="amount[<?=$u[$fields['login_id']]?>]" value="<?=$i?>" <?=$i===0?'checked':''?>>
                                                    <?=$i?>
                                                </label>
                                            </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </th>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="footer text-center">
                <input type="submit" value="<?=lang('login_submit')?>" class="btn btn-simple btn-primary btn-lg">
            </div>
            </form>
        </div>
    </div>
