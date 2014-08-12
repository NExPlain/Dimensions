<?php
/**
 * The template for displaying a single model.
 *
 * @author Renfei Song
 */

require_once "functions.php";
require_once "ModelController.class.php";
require_once "CommentController.class.php";

$invalid_id = true;
if (isset($_GET["id"])) {
    $model_controller = new ModelController();
    if ($model_controller->load($_GET['id'])) {
        if (!$model_controller->is_private
            || $model_controller->uploader_id == $user_controller->id) {
            $invalid_id = false;
            $comment_controller = new CommentController();
            $comment_controller->load($model_controller->id);
        }
    }
}
if ($invalid_id)
    $title = "模型不存在";
else
    $title = $model_controller->title;

get_header($title . " - 查看模型"); ?>

<?php if ($invalid_id): ?>
    <h1 class="page-title">查看模型</h1>
    <div class="alert alert-warning"><strong>错误：</strong>你无权查看该模型或该模型不存在。</div>
<?php else: ?>
    <div class="model-caption">
        <h1 class="model-title">
            <?php echo $model_controller->title ?>
        </h1>
        <div class="model-meta">
            <span class="author">作者：<?php echo $model_controller->uploader_username ?></span>
            <span class="date"><?php echo $model_controller->last_update ?></span>
        </div>
    </div>
    <div class="primary">
        <div id="stage"></div>
        <?php if (!empty($model_controller->description)): ?>
        <div class='model-description'>
            <?php echo $model_controller->description; ?>
        </div>
        <?php endif; ?>
        <div class="model-options">
            <div class="operations">
                <?php if ($user_controller->current_user_like($model_controller->id)): ?>
                    <button class="btn like disabled">我喜欢</button>
                <?php else: ?>
                    <button class="btn like">喜欢</button>
                <?php endif; ?>
                <?php if ($user_controller->current_user_fav($model_controller->id)): ?>
                    <button class="btn fav disabled">已收藏</button>
                <?php else: ?>
                    <button class="btn fav">收藏</button>
                <?php endif; ?>
                <a class="btn download" href="<?php echo $model_controller->model_location ?>">下载</a>
                <a class="btn respond" href="#respond">发表评论</a>
            </div>

            <div class="tools">
                <button class="btn auto-rotate">自动旋转</button>
                <button class="btn switch-sky sky-light">切换背景</button>
            </div>
        </div>
        <div class="comment-area">
            <h2 class="comment-title"><?php echo $comment_controller->comment_count ?> 个回应</h2>
            <div class="comment-body">
            <?php foreach ($comment_controller->comments as $comment): ?>
                <div class="comment-item" id="comment-<?php echo $comment["comment_id"] ?>">
                    <div class="comment-author"><?php echo $comment["user_name"] ?></div>
                    <div class="comment-content"><?php echo $comment["comment_content"] ?></div>
                    <div class="comment-date"><?php echo $comment["comment_date"] ?></div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
        <div class="respond-area" id="respond">
            <h2 class="respond-title">发表评论</h2>
            <div class="respond-body">
            <?php if ($user_controller->logged_in): ?>
                <form method="post" action="handle-respond.php" class="respond-form">
                    <label>
                        <textarea name="content"></textarea>
                    </label>
                    <input type="hidden" name="model_id" value="<?php echo $model_controller->id ?>">
                    <input type="hidden" name="user_id" value="<?php echo $user_controller->id ?>">
                    <input type="submit" class="btn" name="respond" value="发表">
                </form>
            <?php else: ?>
                <div class="login-required">您需要先<a href="login.php">登录</a>才能发表评论。</div>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="secondary">
        <div class="widget statistics">
            <ul>
                <li class="likes"><?php echo $model_controller->likes ?> 位用户喜欢</li>
                <li class="downloads"><?php echo $model_controller->downloads ?> 次下载</li>
                <li class="views"><?php echo $model_controller->views ?> 次浏览</li>
            </ul>
        </div>

        <div class="widget share">
            <h3 class="widget-title">分享这个模型</h3>
            <div class="bdsharebuttonbox">
                <a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a>
                <a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
                <a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                <a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a>
            </div>
            <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"2","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
        </div>

        <?php if ($model_controller->has_related_models()): ?>
        <div class="widget related">
            <h3 class="widget-title">该用户的其他上传</h3>
            <?php $model_controller->list_related_models() ?>
        </div>
        <?php endif; ?>
    </div>

    <script src="lib/core/jquery/jquery-1.10.2.min.js"></script>
    <script src="lib/webgl/Three.js"></script>
    <script src="lib/webgl/OrbitControls.js"></script>
    <script type="x-shader/x-vertex" id="vertexShader">
        varying vec3 vWorldPosition;
        void main() {
            vec4 worldPosition = modelMatrix * vec4(position, 1.0);
            vWorldPosition = worldPosition.xyz;
            gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
        }
    </script>
    <script type="x-shader/x-fragment" id="fragmentShader">
        uniform vec3 topColor;
        uniform vec3 bottomColor;
        uniform float offset;
        uniform float exponent;
        varying vec3 vWorldPosition;
        void main() {
            float h = normalize( vWorldPosition + offset ).y;
            gl_FragColor = vec4( mix( bottomColor, topColor, max( pow( h, exponent ), 0.0 ) ), 1.0 );
        }
    </script>
    <script>
        /* WebGL */

        var scene, camera, renderer;
        var controls, hemisphereLight, pointLight;
        var sky;

        init();
        run();

        function init() {
            // Scene & Camera
            scene = new THREE.Scene();
            scene.fog = new THREE.Fog( 0xffffff, 1000, 10000 );
            var width = 650 - 10;
            var height = 450 - 10;
            var viewAngel = 45;
            var aspect = width / height;
            var near = 0.1;
            var far = 20000;
            camera = new THREE.PerspectiveCamera(viewAngel, aspect, near, far);
            camera.position.set(0, 150, 400);
            camera.lookAt(scene.position);
            scene.add(camera);

            // Renderer
            renderer = new THREE.WebGLRenderer({antialias: true});
            renderer.setClearColor(scene.fog.color, 1);
            renderer.setSize(width, height);
            var container = document.getElementById('stage');
            container.appendChild(renderer.domElement);
            renderer.gammaInput = true;
            renderer.gammaOutput = true;

            // Controls
            controls = new THREE.OrbitControls(camera, container);
            controls.keyPanSpeed = 15.0;
            controls.keys = { LEFT: 65, UP: 87, RIGHT: 68, BOTTOM: 83 };
            controls.zoomSpeed = 0.6;
            controls.rotateSpeed = 1.0;
            controls.autoRotateSpeed = 6.5;
            controls.maxDistance = 3000;

            // Hemisphere Light
            hemisphereLight = new THREE.HemisphereLight(0xffffff, 0xffffff, 1.25);
            hemisphereLight.color.setHSL(0.6, 1, 0.75);
            hemisphereLight.groundColor.setHSL(0.1, 0.8, 0.7);
            hemisphereLight.position.y = 1000;
            hemisphereLight.intensity = 0.7;
            scene.add(hemisphereLight);

            // Point Light
            pointLight = new THREE.PointLight(0xFFFFFF);
            pointLight.intensity = 1.15;
            pointLight.position.set(-100, 200, 100);
            scene.add(pointLight);

            // Sky (light)
            setSky(0x0077ff, 0xffffff);

            // Load Model
            var jsonLoader = new THREE.JSONLoader();
            jsonLoader.load("<?php echo $model_controller->model_location; ?>", function(geometry, materials) {
                loadModel(geometry, materials, 0, 0, 0, <?php echo $model_controller->scale ?>);
            });
        }

        function setSky(topColor, bottomColor) {
            scene.remove(sky);
            var vertexShader = document.getElementById('vertexShader').textContent;
            var fragmentShader = document.getElementById('fragmentShader').textContent;
            var uniforms = {
                topColor: 	 { type: "c", value: new THREE.Color( topColor ) },
                bottomColor: { type: "c", value: new THREE.Color( bottomColor ) },
                offset:		 { type: "f", value: 400 },
                exponent:	 { type: "f", value: 0.6 }
            }
            if (topColor == 0x0077ff) {
                // Use hemisphere light's color as light sky's top color.
                uniforms.topColor.value.copy(hemisphereLight.color);
            }
            // Set fog color to sky's bottom color.
            scene.fog.color.copy(uniforms.bottomColor.value);
            var skyGeo = new THREE.SphereGeometry(4000, 32, 15);
            var skyMat = new THREE.ShaderMaterial({vertexShader: vertexShader, fragmentShader: fragmentShader, uniforms: uniforms, side: THREE.BackSide});
            sky = new THREE.Mesh(skyGeo, skyMat);
            scene.add(sky);
        }

        function loadModel(geometry, materials, x, y, z, scale) {
            var mesh = new THREE.Mesh( geometry, new THREE.MeshFaceMaterial( materials ) );
            mesh.position.set( x, y, z );
            mesh.scale.set( scale, scale, scale );
            scene.add( mesh );
        }

        function run() {
            controls.update();
            renderer.render(scene, camera);
            requestAnimationFrame(run);
        }

        /* Miscellaneous */

        $(".operations .like").click(function() {
            if ($(".operations .like").hasClass("disabled")) {
                return;
            }
            $.ajax({
                url: "model-operations.php?op=add_like&user_id=<?php echo $user_controller->id ?>&model_id=<?php echo $model_controller->id ?>"
            }).done(function(html) {
                $(".operations .like").addClass("disabled").html("我喜欢");
            });
        });

        $(".operations .fav").click(function() {
            if ($(".operations .fav").hasClass("disabled")) {
                return;
            }
            $.ajax({
                url: "model-operations.php?op=add_fav&user_id=<?php echo $user_controller->id ?>&model_id=<?php echo $model_controller->id ?>"
            }).done(function(html) {
                $(".operations .fav").addClass("disabled").html("已收藏");
            });
        });

        $(".tools .auto-rotate").click(function() {
            controls.autoRotate = !controls.autoRotate;
            $(this).toggleClass("disabled");
        });
        $(".tools .switch-sky").click(function() {
            if ($(this).hasClass("sky-light"))
                setSky(0x111111, 0x444444);
            else
                setSky(0x0077ff, 0xffffff);
            $(this).toggleClass("sky-light");
        });


    </script>

<?php
endif;
get_footer();