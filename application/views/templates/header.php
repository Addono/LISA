<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 29-1-2017
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url('resources/img/apple-icon.png')?>">
    <link rel="icon" type="image/png" href="<?=base_url('resources/img/favicon.png')?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Boerenkoolfuif 2017</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" />
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

    <!-- CSS Files -->
    <link href="<?=base_url('resources/css/bootstrap.min.css')?>" rel="stylesheet" />
    <link href="<?=base_url('resources/css/material-kit.css')?>" rel="stylesheet"/>
    <link href="<?=base_url('resources/css/style.css')?>" rel="stylesheet" />
</head>

<body style="background-image: url('<?=base_url('resources/img/boerenkool.jpg')?>')">

<nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=site_url()?>">
                <b>PROLpa TheWorstChef</b> <?=$loggedIn?ucfirst($username):false?>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?=site_url('top')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">star</i> Top scores
                    </a>
                </li>
            <?php if($loggedIn) {
                if($role === 'admin') {?>
                <!-- Admin specific buttons -->
                <li>
                    <a href="<?=site_url('add/netherlands')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">add</i> Nederland
                    </a>
                </li>
                <li>
                    <a href="<?=site_url('add/belgium')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">add</i> België
                    </a>
                </li>
                <li>
                    <a href="<?=site_url('add/germany')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">add</i> Duitsland
                    </a>
                </li>
                <li>
                    <a href="<?=site_url('add/france')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">add</i> Frankrijk
                    </a>
                </li>
                <?php } elseif($role === 'user') { ?>
                <!-- User specific buttons -->
                <li>
                    <a href="<?=site_url('receipts')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">receipt</i> Mijn recepten
                    </a>
                </li>
                <?php } ?>
                <li>
                    <a href="<?=site_url('logout')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">exit_to_app</i> Uitloggen
                    </a>
                </li>
            <?php } else { ?>
                <li>
                    <a href="<?=site_url('login')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="material-icons">account_box</i> Inloggen
                    </a>
                </li>
            <?php } ?>
                <li>
                    <a href="https://twitter.com/impeesa_afoort" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/impeesa.amersfoort" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="wrapper">
    <div class="header header-filter first">
        <div class="container">
            <div class="row">