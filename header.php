<?php
/**
 * The Header of our site
 *
 * Displays all of the <head> section and everything up till <div id="centrum" class="site-main">
 *
 * @author Renfei Song
 * @author Qin Bingchen
 */

require_once "functions.php";
require_once "UserController.class.php";

global $user_controller;
$user_controller = new UserController();
$user_controller->init();

?><!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__page_title ?></title>
    <script src="lib/core/jquery/jquery-1.10.2.min.js"></script>
    <link href="lib/core/reset.css" rel="stylesheet" media="screen">
    <link href="lib/core/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="lib/core/bootstrap/js/bootstrap.min.js"></script>
    <link href="lib/core/bootstrap/v3-icons/bootstrap-fonts.css" rel="stylesheet" media="screen">
    <link href="lib/core/components.css" rel="stylesheet" media="screen">
    <link href="lib/core/style.css" rel="stylesheet" media="screen">
</head>

<body>
<div id="wrapper" class="<?php echo basename($_SERVER['SCRIPT_NAME'], ".php"); ?>">
    <header id="masthead" class="site-header">
        <div class="inner">
            <div class="site-title">
                <a href="index.php" title="Dimensions">
                    <img class="site-logo" src="lib/img/logo.png">
                </a>
                <a href="index.php" title="Dimensions" class="site-title-text">Dimensions</a>
            </div>
            <div class="site-search"></div>
            <div class="site-navigation">
                <ul>
                    <li><a href="browse.php" title="浏览所有模型">浏览</a></li>
                    <?php if ($user_controller->has_user): ?>
                        <li><a href="editor/create.php">新建</a></li>
                        <li><a href="upload.php">上传</a></li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" data-target="#" href="user.php"><?php echo $user_controller->username ?><span class="glyphicon glyphicon-chevron-down"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="user.php" title="个人中心"><span class="glyphicon glyphicon-user"></span>个人中心</a></li>
                                <li><a href="settings.php" title="用户设定"><span class="glyphicon glyphicon-cog"></span>设定</a></li>
                                <li><a href="logout.php" title="登出"><span class="glyphicon glyphicon-off"></span>登出</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href='login.php'>登录</a></li>
                        <li><a href='signup.php' class="inverted-button">注册</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <div id="centrum" class="site-main">
        <div class="site-content">