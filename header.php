<?php
/**
 * The Header of our site
 *
 * Displays all of the <head> section and everything up till <div id="centrum" class="site-main">
 *
 * @author Renfei Song
 */

require_once "define.php";
require_once "UserController.class.php";

global $user_controller;
$user_controller = new UserController();
$user_controller->init();

switch(basename($_SERVER['SCRIPT_NAME'])) {
    case "browse.php":
        $browse_url = "#";
        $browse_status = "current";
        break;
    case "upload.php":
        $upload_url = "#";
        $upload_status = "current";
        break;
    case "my-profile.php":
        $profile_url = "#";
        $profile_status = "current";
        break;
}

?><!DOCTYPE html>
<html lang="zh-Hans-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__page_title ?></title>
    <script src="lib/core/jquery/jquery-1.10.2.min.js"></script>
    <script src="lib/core/bootstrap/js/bootstrap.min.js"></script>
    <link href="lib/core/reset.css" rel="stylesheet" media="screen">
    <link href="lib/core/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="lib/core/bootstrap/v3-icons/bootstrap-fonts.css" rel="stylesheet" media="screen">
    <link href="lib/core/components.css" rel="stylesheet" media="screen">
    <link href="lib/core/style.css" rel="stylesheet" media="screen">
</head>

<body>
<div id="wrapper" class="<?php echo basename($_SERVER['SCRIPT_NAME'], ".php"); ?>">
    <header id="masthead" class="site-header">
        <div class="inner">
            <div class="site-title">
                <img class="site-logo" src="lib/img/logo.png">
                <a href="index.php">Dimensions</a>
            </div>
            <div class="site-search">

            </div>
            <nav class="site-navigation">
                <ul>
                    <?php if ($user_controller->has_user): ?>
                        <li>欢迎，<?php echo $user_controller->username ?></li>
                        <li class="<?php echo @$browse_status?>"><a href="<?php if (isset($browse_url)) echo $browse_url; else echo 'browse.php'?>">浏览</a></li>
                        <li class="<?php echo @$profile_status?>"><a href="<?php if (isset($profile_url)) echo $profile_url; else echo 'user.php'?>">个人档案</a></li>
                        <li class="<?php echo @$upload_status?>"><a href="<?php if (isset($upload_url)) echo $upload_url; else echo 'upload.php'?>">上传模型</a></li>
                        <li class="create"><a href="editor/create.php">新建</a></li>
                        <li><a href='logout.php'>登出</a></li>
                    <?php else: ?>
                        <li><a href='login.php'>登录</a></li>
                        <li><a href='signup.php'>注册</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div id="centrum" class="site-main">
        <div class="site-content">