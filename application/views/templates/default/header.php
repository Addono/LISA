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
    <?php $tc = '220000'?>
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#<?=$tc?>"><meta name="theme-color" content="#db5945">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#<?=$tc?>">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#<?=$tc?>">

    <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url('resources/img/apple-icon.png')?>">
    <link rel="icon" type="image/png" href="<?=base_url('resources/img/favicon.png')?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title><?=lang('application_title')?></title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

    <!-- Google Material Design -->
    <link href="<?=base_url('./resources/css/mdl/material.min.css')?>" rel="stylesheet" type="text/css">

    <!--     Fonts and icons     -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" /> -->
    <link rel="stylesheet" type="text/css" href="<?=base_url('resources/css/roboto.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=base_url('resources/css/font-awesome.min.css')?>" />

    <!--    CSS Files   -->
    <link href="<?=base_url('resources/css/bootstrap.min.css')?>" rel="stylesheet" />
    <link href="<?=base_url('resources/css/material-kit.css')?>" rel="stylesheet"/>
    <link href="<?=base_url('resources/css/style.css')?>" rel="stylesheet" />

    <!-- Piwik -->
    <script type="text/javascript">
        var _paq = _paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//bar.sdhd.eu/piwik/";
            _paq.push(['setTrackerUrl', u+'piwik.php']);
            _paq.push(['setSiteId', '1']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Piwik Code -->
</head>

<body style="background-image: url('<?=base_url('resources/img/bg.jpeg')?>')">
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=site_url()?>">
                    <b><?=lang('application_name')?></b> <?=$loggedIn?ucfirst($username):''?>
                </a>
            </div>

            <div class="collapse navbar-collapse navbar-right" id="navigation">
                <?=$menu?>
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