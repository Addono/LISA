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
                    <h4><?=lang('leaderboard_title')?></h4>
                    <p><?=lang('leaderboard_subtext')?></p>
                </div>
            </div>
            <div class="content">
                <div class="content">
                    <div class="tab-content text-center"><div class="tab-pane active" id="ordered-first-name">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><?=lang('leaderboard_table_header_name')?></th>
                                <th><?=lang('leaderboard_table_header_sum')?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $count = 0; foreach ($entries as $entry) { ?>
                                <tr>
                                    <td><?=++$count?></td>
                                    <td><?=$entry[$fields['first_name']] . ' ' . $entry[$fields['last_name']]?></td>
                                    <td><?=-$entry[$fields['sum']]?></td>
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
