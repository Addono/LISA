<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 29-1-2017
 */
?>

                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container" style="color: white">
            <nav class="pull-left">
                <ul>
                    <li>
                        <a href="https://www.sdhd.nl/">
                            <?=lang('footer_hosted_by')?>
                            <img src="<?=base_url('public/img/sdhd/logo.png')?>" style="height:3em"/>
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/Addono/lisa">
                            <?=lang('footer_source')?>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="copyright pull-right">
                <b><?=lang('application_name').' '.lang('application_version')?></b> &copy; <?=lang('copyright')?>
            </div>
        </div>
    </footer>

    </body>

    <!--   Core JS Files   -->
    <script src="<?=base_url('public/js/jquery.min.js')?>" type="text/javascript"></script>
    <script src="<?=base_url('public/js/bootstrap.min.js" type="text/javascript')?>"></script>
    <script src="<?=base_url('public/js/material.min.js')?>"></script>

    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="<?=base_url('public/js/nouislider.min.js')?>" type="text/javascript"></script>

    <!-- Control Center for Material Kit: activating the ripples, parallax effects, scripts from the example pages etc -->
    <script src="<?=base_url('public/js/material-kit.js')?>" type="text/javascript"></script>

    <!--  Google Material Design -->
    <script src="<?=base_url('public/js/mdl/material.min.js')?>" type="text/javascript"></script>

    <script src="<?=base_url('node_modules/moment/moment.js')?>" type="text/javascript"></script>
<?php
$localeCode = $ci->config->config['locale_code'];
if ($localeCode!=='en') {
    ?><script src="<?= base_url('node_modules/moment/locale/' . $localeCode . '.js') ?>" charset="UTF-8"></script>
    <script>
        moment.locale('<?=html_escape($localeCode)?>');
    </script><?php
}
?>
    <script>
        $(function() {
            function setTimeText(element, relative) {
                var time = moment.unix(element.data('time'));
                var timeString;
                if (relative) {
                    timeString = time.fromNow();
                } else {
                    timeString = time.format('llll');
                }
                element.html(timeString);
                element.data('relative', relative);
            }

            $('.moment_relative_time').each(function (i) {
                setTimeText($(this), true);
            }).on('click', function (i) {
                $('.moment_relative_time').each(function (i) {
                    relative = $(this).data('relative');
                    setTimeText($(this), !relative);
                });
            });
        });
    </script>

    <?php foreach ($scripts as $script) {
        echo $script;
    } ?>
</html>
