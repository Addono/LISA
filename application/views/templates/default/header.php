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

    <title><?=lang('application_title')?></title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <!--     Fonts and icons     -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" /> -->
    <link rel="stylesheet" type="text/css" href="<?=base_url('resources/css/roboto.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('resources/css/font-awesome.min.css')?>" />

    <!--    CSS Files   -->
    <link href="<?=base_url('resources/css/bootstrap.min.css')?>" rel="stylesheet" />
    <link href="<?=base_url('resources/css/material-kit.css')?>" rel="stylesheet"/>
    <link href="<?=base_url('resources/css/style.css')?>" rel="stylesheet" />
</head>

<body style="background-image: url('<?=base_url('resources/img/bg.jpeg')?>')">

<nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                <span class="sr-only"><?=lang('navigation_toggle')?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=site_url()?>">
                <b><?=lang('application_name')?></b> <?=$loggedIn?ucfirst($username):false?>
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav navbar-right">
            <?php if($loggedIn) { ?>
                <!-- Buttons for logged in users -->
                <li>
                    <a href="<?=site_url('logout')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="fa fa-sign-out"></i> <?=lang('logout_logout')?>
                    </a>
                </li>
                <!-- /Buttons for logged in users -->
            <?php } else { ?>
                <!-- Buttons for users whom are not logged in -->
                <li>
                    <a href="<?=site_url('login')?>" class="btn btn-simple btn-white" target="_self">
                        <i class="fa fa-sign-in"></i> <?=lang('login_login')?>
                    </a>
                </li>
                <!-- /Buttons for users whom are not logged in -->
            <?php } ?>
                <!-- Buttons for everyone -->
                <li>
                    <a href="https://twitter.com/" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.facebook.com/" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                        <i class="fa fa-facebook-square"></i>
                    </a>
                </li>
                <!-- /Buttons for everyone -->
            </ul>
        </div>
    </div>
</nav>

<div class="wrapper">
    <div class="header first">
        <?=showMessages(
                $messages,
            '<div class="alert alert-%s">
                <div class="container-fluid">
                    <div class="alert-icon">
                        <i class="material-icons"></i>
                    </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>',
            '</div>
            </div>'
        );
        ?>

        <div class="container">
            <div class="row">