<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=lang('application_title')?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url('./resources/css/bootstrap.min.css')?>" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=base_url('./resources/css/metisMenu.min.css')?>" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?=base_url('./resources/css/dataTables.bootstrap.css')?>" rel="stylesheet" type="text/css">
    <link href="<?=base_url('./resources/css/dataTables.responsive.css')?>" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <link href="<?=base_url('./resources/css/sb-admin-2.css')?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=base_url('./resources/css/font-awesome.min.css')?>" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=site_url($group)?>"><strong><?=lang('application_name')?></strong> - <?=$username?></a>
        </div><!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="<?=site_url($group.'/logout')?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
        </ul><!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <?=$menu?>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav><!-- /Navigation -->

    <div id="page-wrapper">
        <div class="panel-body" style="margin-bott">
            <?=showMessages($messages,
                '<div class="alert alert-%s alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>',
                '</div>')?>
        </div><!-- /.panel-body -->