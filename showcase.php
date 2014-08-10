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
get_header($title . " - 查看模型");
?>

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
        <div id="arena"></div>
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
        <div class="respond-area">
            <h2 class="respond-title">发表看法</h2>
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
        <?php if (!empty($model_controller->description)): ?>
        <div class='widget description'>
            <?php echo $model_controller->description; ?>
        </div>
        <?php endif; ?>

        <div class="widget statistics">
            <ul>
                <li><?php echo $model_controller->likes ?> 位用户喜欢</li>
                <li><?php echo $model_controller->downloads ?> 次下载</li>
                <li><?php echo $model_controller->views ?> 次浏览</li>
            </ul>
        </div>

        <div class="widget share">
            分享
        </div>

        <div class="widget options">
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
        </div>
    </div>


    <script>
        $(".options .like").click(function() {
            if ($(".options .like").hasClass("disabled")) {
                return;
            }
            $.ajax({
                url: "model-operations.php?op=add_like&user_id=<?php echo $user_controller->id ?>&model_id=<?php echo $model_controller->id ?>"
            }).done(function(html) {
                $(".options .like").addClass("disabled").html("我喜欢");
            });
        });

        $(".options .fav").click(function() {
            if ($(".options .fav").hasClass("disabled")) {
                return;
            }
            $.ajax({
                url: "model-operations.php?op=add_fav&user_id=<?php echo $user_controller->id ?>&model_id=<?php echo $model_controller->id ?>"
            }).done(function(html) {
                $(".options .fav").addClass("disabled").html("已收藏");
            });
        });
    </script>


    <script src="lib/core/jquery/jquery-1.10.2.min.js"></script>
    <script src="lib/webgl/Three.js"></script>
    <script src="lib/webgl/FlyControls.js"></script>
    <script src="lib/webgl/Stats.js"></script>
    <script>
        var container, scene, camera, renderer, model, flyControls;
        var scale = <?php echo $model_controller->scale; ?>;
        var clock = new THREE.Clock();

        container = document.getElementById('arena');
        init();
        animate();
        $('#arena').height(450);
        $('#arena').width(650);

        function init() {
            // scene
            scene = new THREE.Scene();

            // camera
            var width = 650;
            var height = 450;
            var viewAngel = 45;
            var aspect = width / height;
            var near = 0.1;
            var far = 20000;
            camera = new THREE.PerspectiveCamera(viewAngel, aspect, near, far);
            scene.add(camera);
            camera.position.set(0, 150, 400);
            camera.lookAt(scene.position);

            // renderer
            renderer = new THREE.WebGLRenderer({ antialias:true });
            renderer.setSize(width, height);
            container.appendChild(renderer.domElement);

            // flyControls
            flyControls = new THREE.FlyControls(camera);
            flyControls.movementSpeed = 100;
            flyControls.domElement = container;
            flyControls.rollSpeed = Math.PI / 24;
            flyControls.autoForward = false;
            flyControls.dragToLook = true;

            // stat
// 				stats = new Stats();
// 				stats.domElement.style.position = 'absolute';
// 				stats.domElement.style.bottom = '0px';
// 				stats.domElement.style.zIndex = 100;
// 				container.appendChild(stats.domElement);

            // light
            var light = new THREE.PointLight(0xffffff);
            light.position.set(-100,200,100);
            scene.add(light);

            // floor
            var floorTexture = new THREE.ImageUtils.loadTexture('lib/img/floor.jpg');
            floorTexture.wrapS = floorTexture.wrapT = THREE.RepeatWrapping;
            floorTexture.repeat.set(10, 10);
            var floorMaterial = new THREE.MeshBasicMaterial({ map: floorTexture, side: THREE.DoubleSide });
            var floorGeometry = new THREE.PlaneGeometry(1000, 1000, 10, 10);
            var floor = new THREE.Mesh(floorGeometry, floorMaterial);
            floor.position.y = -0.5;
            floor.rotation.x = Math.PI / 2;
            scene.add(floor);

            // skybox
            var skyBoxGeometry = new THREE.CubeGeometry(10000, 10000, 10000);
            var skyBoxMaterial = new THREE.MeshBasicMaterial({ color: 0x9999ff, side: THREE.BackSide });
            var skyBox = new THREE.Mesh(skyBoxGeometry, skyBoxMaterial);
            scene.add(skyBox);

            // fog
            scene.fog = new THREE.FogExp2(0x9999ff, 0.00025);

            var jsonLoader = new THREE.JSONLoader();
            jsonLoader.load("<?php echo $model_controller->model_location; ?>", addModelToScene);

            var ambientLight = new THREE.AmbientLight(0x111111);
            scene.add(ambientLight);
        }

        function addModelToScene(geometry, materials) {
            var material = new THREE.MeshFaceMaterial(materials);
            model = new THREE.Mesh(geometry, material);
            model.scale.set(scale, scale, scale);
            scene.add(model);
        }

        function animate() {
            requestAnimationFrame(animate);
            render();
            update();
        }

        function update() {
            flyControls.update(clock.getDelta());
// 			stats.update();
        }

        function render() {
            renderer.render(scene, camera);
        }
    </script>
<?php
endif;
get_footer();