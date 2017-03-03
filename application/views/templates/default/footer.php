<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 29-1-2017
 */
?>
<<<<<<< HEAD
            <footer>
                <div class="copyright">
                    &copy; <?=lang('copyright')?>
=======

            <footer class="footer">
                <div class="container">
                    <div class="copyright">
                       <?=lang('application_name').' '.lang('application_version')?> &copy; <?=lang('copyright')?>
                    </div>
>>>>>>> upstream/master
                </div>
            </footer>
            </div>
        </div>
    </div>

    </body>

    <!--   Core JS Files   -->
    <script src="<?php echo base_url('resources/js/jquery.min.js')?>" type="text/javascript"></script>
    <script src="<?php echo base_url('resources/js/bootstrap.min.js" type="text/javascript')?>"></script>
    <script src="<?php echo base_url('resources/js/material.min.js')?>"></script>

    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="<?php echo base_url('resources/js/nouislider.min.js')?>" type="text/javascript"></script>

    <!-- Control Center for Material Kit: activating the ripples, parallax effects, scripts from the example pages etc -->
    <script src="<?php echo base_url('resources/js/material-kit.js')?>" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $("input[type=radio").change(function() {
                $(this).parents('ul').children('.active').removeClass('active');
                $(this).parents('li').addClass('active');
            });

            $(".pagination.horizontal-radio").click(function() {
                $(this).find('input[type=radio]').attr('checked', true);
            });
        });
    </script>
</html>
