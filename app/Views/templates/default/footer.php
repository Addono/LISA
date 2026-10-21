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
                            <?=lang('Lisa.footer_hosted_by')?>
                            <img src="<?=base_url('img/sdhd/logo.png')?>" style="height:3em"/>
                        </a>
                    </li>
                    <li>
                        <a href="https://github.com/Addono/lisa">
                            <?=lang('Lisa.footer_source')?>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="copyright pull-right">
                <b><?=lang('Lisa.application_name').' '.lang('Lisa.application_version')?></b> &copy; <?=lang('Lisa.copyright')?>
            </div>
        </div>
    </footer>

    </body>

    <!--   Core JS Files   -->
    <script src="<?=base_url('js/jquery.min.js')?>" type="text/javascript"></script>
    <script src="<?=base_url('js/bootstrap.min.js')?>" type="text/javascript"></script>
    <script src="<?=base_url('js/material.min.js')?>"></script>

    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="<?=base_url('js/nouislider.min.js')?>" type="text/javascript"></script>

    <!-- Control Center for Material Kit: activating the ripples, parallax effects, scripts from the example pages etc -->
    <script src="<?=base_url('js/material-kit.js')?>" type="text/javascript"></script>

    <!--  Google Material Design -->
    <script src="<?=base_url('js/mdl/material.min.js')?>" type="text/javascript"></script>

    <script src="<?=base_url('node_modules/moment/moment.js')?>" type="text/javascript"></script>
<?php
$localeCode = service('request')->getLocale();
if ($localeCode!=='en') {
    ?><script src="<?= base_url('node_modules/moment/locale/' . $localeCode . '.js') ?>" charset="UTF-8"></script>
    <script>
        moment.locale('<?=esc($localeCode)?>');
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
