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
                            <img src="<?=base_url('resources/img/sdhd/logo.png')?>" style="height:3em"/>
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
    <script src="<?=base_url('resources/js/jquery.min.js')?>" type="text/javascript"></script>
    <script src="<?=base_url('resources/js/bootstrap.min.js" type="text/javascript')?>"></script>
    <script src="<?=base_url('resources/js/material.min.js')?>"></script>

    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="<?=base_url('resources/js/nouislider.min.js')?>" type="text/javascript"></script>

    <!-- Control Center for Material Kit: activating the ripples, parallax effects, scripts from the example pages etc -->
    <script src="<?=base_url('resources/js/material-kit.js')?>" type="text/javascript"></script>

    <!--  Google Material Design -->
    <script src="<?=base_url('resources/js/mdl/material.min.js')?>" type="text/javascript"></script>

    <?php foreach ($scripts as $script) {
        echo $script;
    } ?>
</html>
