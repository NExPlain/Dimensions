<?php
/**
 * Temporary solution for automatically generating a screenshot.
 * Requires client compatibility for WebGL.
 *
 * Sample usage: localhost/dimensions/generate-screenshot.php?id=1
 *
 * @author Renfei Song
 */
require_once('define.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Cannot connect to database.");

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "select * from dimensions_models where dimensions_models.id = '$id'";
    $result = mysqli_query($dbc, $query) or die("{'STATUS':'QUERY_FAILURE'}");
    if ($row = mysqli_fetch_array($result)) {
        $scale = $row["scale"] != "" ? $row["scale"] : 1.0;
        $file_model_loc = "upload/".$row["file_stamp"]."/".$row["model_name"];
    } else {
        echo "{'STATUS':'ID_INVALID'}";
        exit;
    }
} else {
    echo "{'STATUS':'ID_NOT_FOUND'}";
    exit;
}

if (isset($_GET['h'])) $height = $_GET['h'];
else $height = 300;
if (isset($_GET['w'])) $width = $_GET['w'];
else $width = 300;
if (isset($_GET['wait']) && $_GET['wait'] != "") $wait = $_GET['wait'];
else $wait = 5000;
if (isset($_GET['factor']) && $_GET['factor'] != "") $factor = $_GET['factor'];
else $factor = 1.5;

?>
<html>
<head>
    <style>
        #status {
            font-family: Consolas, Monaco, 'Courier New', monospace;
            font-size: 14px;
        }
        #ThreeJS {
            position: absolute;
            left: 0;
            top: 0;
            height: <?php echo $height ?>px;
            width: <?php echo $width ?>px;
            visibility: hidden;
        }
        #DataURL {
            word-break: break-all;
            font-family: Consolas, monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div id="status">Loading utilities...</div>
<div id="DataURL"></div>
<div id="ThreeJS"></div>
<script src="lib/core/jquery/jquery-1.10.2.min.js"></script>
<script src="lib/webgl/Three.js"></script>
<script>
    var scene, camera, renderer, model;
    var wait = <?php echo $wait ?>;
    var scale = <?php echo $scale; ?>;
    var clock = new THREE.Clock();
    var container = document.getElementById('ThreeJS');
    var jsonLoader = new THREE.JSONLoader();

    jsonLoader.onLoadStart = function() {
        console.log(time() + "JSON loader will start to work.");
        $("#status").html("JSON loader will start to work.");
    }

    jsonLoader.onLoadComplete = function() {
        jsonLoaderReady = true;
        console.log(time() + "JSON loader reports ready.");
        $("#status").html("JSON loader reports ready.");
        getScreenshot(wait);
    };

    init();
    $("#status").html("Component Initialized.");
    animate();

    function init() {
        // scene
        scene = new THREE.Scene();

        // camera
        var width = <?php echo $width ?>;
        var height = <?php echo $height ?>;
        var near = 1;
        var far = 20000;
        camera = new THREE.OrthographicCamera(width / - 2, width / 2, height / 2, height / -2, near, far);
        scene.add(camera);
        camera.position.set(0, 150, 400);
        camera.lookAt(scene.position);

        // renderer
        renderer = new THREE.WebGLRenderer({ antialias:true, preserveDrawingBuffer: true });
        renderer.setSize(width, height);
        container.appendChild(renderer.domElement);

        // light
        var light = new THREE.PointLight(0xffffff, 1.5);
        light.position.set(-100,200,100);
        scene.add(light);

        // skybox
        var skyBoxGeometry = new THREE.CubeGeometry(10000, 10000, 10000);
        var skyBoxMaterial = new THREE.MeshBasicMaterial({ color: 0x9999ff, side: THREE.BackSide });
        var skyBox = new THREE.Mesh(skyBoxGeometry, skyBoxMaterial);
        scene.add(skyBox);

        // fog
        scene.fog = new THREE.FogExp2(0x9999ff, 0.00025);

        // add
        jsonLoader.load("<?php echo $file_model_loc; ?>", addModelToScene);
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
    }

    function render() {
        renderer.render(scene, camera);
    }

    function time() {
        return "[" + (new Date().toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1")) + "] ";
    }

    function centerize() {
        model.scale.set(1, 1, 1);
        model.geometry.computeBoundingBox();
        var xSemi = model.geometry.boundingBox.max.x;
        var ySemi = model.geometry.boundingBox.max.y;
        var zSemi = model.geometry.boundingBox.max.z;
        model.position.x = 0;// -xSemi;
        model.position.y = -ySemi;
        model.position.z = -zSemi * 2;
        camera.position.x = 0;
        camera.position.y = 0;
        camera.position.z = 5; // anything gte then 0 should work.

        var xyBigger = xSemi > ySemi ? xSemi : ySemi;
        var factor = <?php echo $factor ?>;
        camera.top = camera.right = xyBigger * factor;
        camera.left = camera.bottom = -xyBigger * factor;
        camera.updateProjectionMatrix();
    }

    function getScreenshot(delay) {
        console.log(time() + "Preparing to get screenshot, be patient...");
        $("#status").html("Preparing to get screenshot, be patient...");
        setTimeout(function() {
            console.log(time() + "Centrizing...");
            $("#status").html("Centrizing...");
            centerize();
            setTimeout(function() {
                console.log(time() + "Centerization complete, proceeding...");
                $("#status").html("Centerization complete, proceeding...");
                $("canvas").attr("id", "Canvas");
                var canvas = document.getElementById("Canvas");
                var dataURL = canvas.toDataURL();
                $("#DataURL").html("<a href="+dataURL+">View Image</a>");
                console.log(time() + "Data-URL generated.");
                $("#status").html("Data-URL generated.");
                $("#ThreeJS").remove();
                $("script").remove();
                console.log(time() + "Cleared.");
                $("#status").html("Cleared");
            }, 1000);
        }, delay);
    }
</script>
</body>
</html>