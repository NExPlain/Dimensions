<?php
/**
 * The initial page of Dimensions.
 *
 * @author Renfei Song
 */

require_once "functions.php";

?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">
    <head>
        <meta charset="UTF-8">
        <title>Dimensions</title>
        <script src="lib/core/jquery/jquery-1.10.2.min.js"></script>
<!--        <script src="lib/core/bootstrap/js/bootstrap.min.js"></script>-->
<!--        <link href="lib/core/reset.css" rel="stylesheet" media="screen">-->
<!--        <link href="lib/core/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">-->

        <link href="lib/core/bootstrap-3.2.0-dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script src="lib/core/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <link href="lib/core/style-new.css" rel="stylesheet" media="screen">
        <link href="lib/core/index.css" rel="stylesheet" media="screen">
    </head>

<body>

<div id="wrapper" class=index">
    <header id="masthead" class="site-header">
        <div class="inner">
            <div class="site-title">
                <a href="index.php"><img class="site-logo" src="lib/img/logo.png"></a>
                <a href="index.php">Dimensions</a>
            </div>
            <div class="site-search"></div>
            <div class="site-navigation">
                <ul>
                    <li><a href='login.php'>探索<span class="glyphicon glyphicon-chevron-down"></span></a></li>
                    <li><a href='login.php'>社区<span class="glyphicon glyphicon-chevron-down"></span></a></li>
                </ul>
            </div>
            <div class="site-navigation right">
                <ul>
                    <?php if ($user_controller->logged_in): ?>
                        <li>欢迎，<?php echo $user_controller->username ?></li>
                        <li class="<?php echo @$browse_status?>"><a href="<?php if (isset($browse_url)) echo $browse_url; else echo 'browse.php'?>">浏览</a></li>
                        <li class="<?php echo @$profile_status?>"><a href="<?php if (isset($profile_url)) echo $profile_url; else echo 'my-profile.php'?>">个人档案</a></li>
                        <li class="<?php echo @$upload_status?>"><a href="<?php if (isset($upload_url)) echo $upload_url; else echo 'upload.php'?>">上传模型</a></li>
                        <li class="create"><a href="editor/create.php">新建</a></li>
                        <li><a href='logout.php'>登出</a></li>
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
            <div class="section-one">
                <div class="intro-video">
                    我是视频
                </div>
                <div class="intro-title">
                    <p>创建并分享动态3D模型</p>
                </div>
                <!--            <div class="intro-content">-->
                <!--                <ul>-->
                <!--                    <li><span class="glyphicon glyphicon-ok"></span>无需插件</li>-->
                <!--                    <li><span class="glyphicon glyphicon-ok"></span>27种格式</li>-->
                <!--                    <li><span class="glyphicon glyphicon-ok"></span>完全免费</li>-->
                <!--                </ul>-->
                <!--            </div>-->
            </div>

            <div class="section-two">

            </div>
        </div><!-- .site-content -->
    </div><!-- #centrum -->
    <footer id="colophon" class="site-footer">
        <div class="inner">
            &copy; 2014 BUAA-dimensions
        </div>
    </footer>
</div><!-- #wrapper -->

</body>
</html>