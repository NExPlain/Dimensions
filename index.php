<?php
/**
 * The initial page of Dimensions.
 *
 * @author Renfei Song
 */

require_once "functions.php";

get_header("Dimensions"); ?>

    <link href="lib/core/index.css" rel="stylesheet" media="screen">
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
    <script src="lib/webgl/index-animation.js"></script>

<?php
get_footer();