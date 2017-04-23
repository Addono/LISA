<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 29-1-2017
 */
?>

            <footer class="footer">
                <div class="container">
                    <div class="copyright">
                       <?=lang('application_name').' '.lang('application_version')?> &copy; <?=lang('copyright')?>
                    </div>
                </div>
            </footer>
            </div>
        </div>
    </div>

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
