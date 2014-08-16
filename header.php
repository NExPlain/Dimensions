<?php
/**
 * The Header of our site
 *
 * Displays all of the <head> section and everything up till <div id="centrum" class="site-main">
 *
 * @author Renfei Song
 */

require_once "functions.php";
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
    <link href="lib/core/reset.css" rel="stylesheet" media="screen">
    <link href="lib/core/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script src="lib/core/bootstrap/js/bootstrap.min.js"></script>
    <link href="lib/core/bootstrap/v3-icons/bootstrap-fonts.css" rel="stylesheet" media="screen">
    <link href="lib/core/components.css" rel="stylesheet" media="screen">
    <link href="lib/core/style.css" rel="stylesheet" media="screen">
    <script src="lib/webgl/Three.js"></script>
</head>

<body>

<div id="wrapper" class="<?php echo basename($_SERVER['SCRIPT_NAME'], ".php"); ?>">
                <header id="masthead" class="site-header">
<div class="inner">
    <div class="site-title">
        <a href="index.php"><img class="site-logo" src="lib/img/logo.png"></a>
        <a href="index.php" class="site-title-text">Dimensions</a>
    </div>
    <div class="site-search"></div>
    <div class="site-navigation">
        <ul>
            <li><a href='browse.php'>浏览</a></li>
        </ul>
    </div>
    <div class="site-navigation right">
        <ul>
            <?php if ($user_controller->has_user): ?>
                <li class="create"><a href="editor/create.php">新建</a></li>
                <li class="<?php echo @$upload_status?>"><a href="<?php if (isset($upload_url)) echo $upload_url; else echo 'upload.php'?>">上传模型</a></li>
                <li class="dropdown">
                    <a data-toggle="dropdown" href="#"><?php echo $user_controller->username ?><span class="glyphicon glyphicon-chevron-down"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php if (isset($profile_url)) echo $profile_url; else echo 'my-profile.php'?>"><span class="glyphicon glyphicon-user"></span>个人中心</a></li>
                        <li><a href="prefortobemodified.php"><span class="glyphicon glyphicon-cog"></span>设定</a></li>
                        <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span>登出</a></li>
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