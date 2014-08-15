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

        <script src="lib/webgl/Three.js"></script>
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
                <!-- 建议

                1. 不要如此明显的模仿 Sketchfab 好不好...
                2. style-new 是干嘛的？我觉得没啥用，你看看能否删掉
                3. 貌似你没用 Bootstrap 3 的新特性（除了 icon），因此不要再引入 Bootstrap 3 的库了（让用户加载这么多没用的文件没必要）
                4. 其实这个项目根本没有必要用 Bootstrap（除了 icon），Bootstrap 2 的存在仅仅是历史问题（我第一次开发这个是在半年多前）。
                   你做首页和其他页面最好不要依赖 Bootstrap（除了 icon），因为未来我打算把 Bootstrap（除了 icon）清理掉。
                5. 要使用 Bootstrap 3 的 icon 的话，我已经在之前引入了。
                6. 如果你要保留顶栏的话就用 header.php 模版，然后按照你的想法把 header.php 改掉，实现全站统一。否则就不要出现顶栏。
                7. 新 css 和临时 css 一律放到 lib/core 里面，不要再丢到外面了。

                -->
                <ul>
                    <li><a href='x-x-x-x-x.php'>浏览</a></li>
                </ul>
            </div>
            <div class="site-navigation right">
                <ul>
                    <?php if (@$user_controller->logged_in): ?>
                        <li class="create"><a href="editor/create.php">新建</a></li>
                        <li class="<?php echo @$upload_status?>"><a href="<?php if (isset($upload_url)) echo $upload_url; else echo 'upload.php'?>">上传模型</a></li>
                        <li class="dropdown">
                            欢迎，
                            <a data-toggle="dropdown" href="#"><?php echo $user_controller->username ?><span class="glyphicon glyphicon-chevron-down"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php if (isset($profile_url)) echo $profile_url; else echo 'my-profile.php'?>"><span class="glyphicon glyphicon-user"></span>个人中心</a></li>
                                <li><a href="x-x-x-x-x.php"><span class="glyphicon glyphicon-cog"></span>设定</a></li>
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
            <div class="section-one">
                <div class="intro-video" id="intro-video">
                </div>
                <div class="intro-title">
                    <p>创建并分享动态3D模型</p>
                </div>
            </div>

            <div class="section-two">
                <div class="feature-list">
                    <div class="feature">
                        <div class="feature-title">Upload in seconds</div>
                        <div class="feature-content">
                            Sketchfab lets you upload your models without limit, either via your web browser or directly from your 3D editor.
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Upload in seconds</div>
                        <div class="feature-content">
                            Sketchfab lets you upload your models without limit, either via your web browser or directly from your 3D editor.
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Upload in seconds</div>
                        <div class="feature-content">
                            Sketchfab lets you upload your models without limit, either via your web browser or directly from your 3D editor.
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-three">

            </div>

        </div><!-- .site-content -->
    </div><!-- #centrum -->
    <footer id="colophon" class="site-footer">
        <div class="inner">
            &copy; 2014 BUAA-dimensions
        </div>
    </footer>
</div><!-- #wrapper -->

<script src="lib/webgl/index-animation.js"></script>

</body>
</html>