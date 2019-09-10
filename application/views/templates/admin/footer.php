<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 26-2-2017
 */
?>

        <!-- jQuery -->
        <script src="<?=base_url('./public/js/jquery.min.js')?>"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="<?=base_url('./public/js/bootstrap.min.js')?>"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="<?=base_url('./public/js/metisMenu.min.js')?>"></script>

        <!-- Custom Theme JavaScript -->
        <script src="<?=base_url('./public/js/sb-admin-2.js')?>"></script>

        <!-- DataTables JavaScript -->
        <script src="<?=base_url('./public/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?=base_url('./public/js/dataTables.bootstrap.min.js')?>"></script>
        <script src="<?=base_url('./public/js/dataTables.responsive.js')?>"></script>

        <?php foreach ($scripts as $script) {
            echo $script;
        } ?>

    </body>

</html>
