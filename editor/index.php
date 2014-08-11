<?php
/**
 * Initial page for model editor
 *
 * @author Renfei Song & Three.js
 */
require_once "../define.php";
	
	if (isset($_COOKIE['mask']) && isset($_COOKIE['username']) && isset($_COOKIE['email'])
	&& sha1($_COOKIE['email'].$_COOKIE['username']) == $_COOKIE['mask']) {	
		$id = $_GET["id"];
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$query = "SELECT * FROM dimensions_models INNER JOIN dimensions_users ON dimensions_users.id = dimensions_models.uploader_id WHERE dimensions_models.id = '$id'";
		$result = mysqli_query($dbc, $query);
		if ($row = mysqli_fetch_array($result)) {
			if ($row['username'] == $_COOKIE['username']) {
				$fileUri = "/dimensions/upload/".$row["file_stamp"]."/".$row["model_name"];
			} else {
				// Authorization failure.
				exitAbnormally();
			}
		} else {
			// ID not valid.
			exitAbnormally();
		}
	} else {
		// Not logged in, or id not specified.
		exitAbnormally();
	}
	
	function exitAbnormally() {
		require_once('../header.php');
		?>
			<div class="alert alert-error"><strong>错误：</strong>拒绝访问。</div>
		<?php
		require_once('../footer.php');
		exit;
	}
?><!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<title>编辑模型</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	</head>
	<body>
		<link href="css/types.css" rel="stylesheet">
		<link id="theme" href="css/light.css" rel="stylesheet">

		<script src="js/utils/three.min.js"></script>
		<script src="js/utils/jquery-1.10.2.min.js"></script>
		<script src="js/libs/system.min.js"></script>

		<script src="js/utils/controls/EditorControls.js"></script>
		<script src="js/utils/controls/TransformControls.js"></script>
		<script src="js/utils/loaders/BabylonLoader.js"></script>
		<script src="js/utils/loaders/ColladaLoader.js"></script>
		<script src="js/utils/loaders/OBJLoader.js"></script>
		<script src="js/utils/loaders/PLYLoader.js"></script>
		<script src="js/utils/loaders/STLLoader.js"></script>
		<script src="js/utils/loaders/UTF8Loader.js"></script>
		<script src="js/utils/loaders/VRMLLoader.js"></script>
		<script src="js/utils/loaders/VTKLoader.js"></script>
		<script src="js/utils/loaders/ctm/lzma.js"></script>
		<script src="js/utils/loaders/ctm/ctm.js"></script>
		<script src="js/utils/loaders/ctm/CTMLoader.js"></script>
		<script src="js/utils/exporters/SceneExporter.js"></script>
		<script src="js/utils/exporters/OBJExporter.js"></script>
		<script src="js/utils/renderers/SoftwareRenderer.js"></script>
		<script src="js/utils/renderers/SVGRenderer.js"></script>

		<!-- WIP -->

		<script src="js/utils/BufferGeometryUtils.js"></script>

		<script src="js/utils/exporters/BufferGeometryExporter.js"></script>
		<script src="js/utils/exporters/GeometryExporter.js"></script>
		<script src="js/utils/exporters/MaterialExporter.js"></script>
		<script src="js/utils/exporters/ObjectExporter.js"></script>
		<script src="js/utils/renderers/WebGLRenderer3.js"></script>

		<script src="js/libs/signals.min.js"></script>
		<script src="js/libs/ui.js"></script>
		<script src="js/libs/ui.three.js"></script>

		<script src="js/Storage.js"></script>

		<script src="js/Editor.js"></script>
		<script src="js/Config.js"></script>
		<script src="js/Loader.js"></script>
		<script src="js/Menubar.js"></script>
		<script src="js/Menubar.File.js"></script>
		<script src="js/Menubar.Edit.js"></script>
		<script src="js/Menubar.Add.js"></script>
		<script src="js/Menubar.View.js"></script>
		<script src="js/Menubar.Help.js"></script>
		<script src="js/Sidebar.js"></script>
		<script src="js/Sidebar.Renderer.js"></script>
		<script src="js/Sidebar.Scene.js"></script>
		<script src="js/Sidebar.Object3D.js"></script>
		<script src="js/Sidebar.Geometry.js"></script>
		<script src="js/Sidebar.Animation.js"></script>
		<script src="js/Sidebar.Geometry.CircleGeometry.js"></script>
		<script src="js/Sidebar.Geometry.CubeGeometry.js"></script>
		<script src="js/Sidebar.Geometry.CylinderGeometry.js"></script>
		<script src="js/Sidebar.Geometry.IcosahedronGeometry.js"></script>
		<script src="js/Sidebar.Geometry.PlaneGeometry.js"></script>
		<script src="js/Sidebar.Geometry.SphereGeometry.js"></script>
		<script src="js/Sidebar.Geometry.TorusGeometry.js"></script>
		<script src="js/Sidebar.Geometry.TorusKnotGeometry.js"></script>
		<script src="js/Sidebar.Material.js"></script>
		<script src="js/Toolbar.js"></script>
		<script src="js/Viewport.js"></script>

		<script>
			window.URL = window.URL || window.webkitURL;
			window.BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder;

			var editor = new Editor();
			var viewport = new Viewport(editor).setId('viewport');
			document.body.appendChild(viewport.dom);
			var toolbar = new Toolbar(editor).setId('toolbar')
			document.body.appendChild(toolbar.dom);
			var menubar = new Menubar(editor).setId('menubar');
			document.body.appendChild(menubar.dom);
			var sidebar = new Sidebar(editor).setId('sidebar');
			document.body.appendChild(sidebar.dom);
			editor.setTheme(editor.config.getKey('theme'));

			editor.storage.init(function() {
				editor.storage.get(function(state) {
					if (state !== undefined) {
						var loader = new THREE.ObjectLoader();
						var scene = loader.parse(state);
						editor.setScene(scene);
					}
					var selected = editor.config.getKey('selected');
					if (selected !== undefined) {
						editor.selectByUuid(selected);
					}
				});

				var timeout;
				var exporter = new THREE.ObjectExporter();
				var saveState = function (scene) {
					clearTimeout(timeout);
					timeout = setTimeout(function () {
						editor.storage.set(exporter.parse(editor.scene));
					}, 1000);
				};

				var signals = editor.signals;
				signals.objectAdded.add(saveState);
				signals.objectChanged.add(saveState);
				signals.objectRemoved.add(saveState);
				signals.materialChanged.add(saveState);
				signals.sceneGraphChanged.add(saveState);

			});

			document.addEventListener('dragover', function(event) {
				event.preventDefault();
				event.dataTransfer.dropEffect = 'copy';
			}, false);

			document.addEventListener('drop', function(event) {
				event.preventDefault();
				editor.loader.loadFile(event.dataTransfer.files[ 0 ]);
			}, false);

			document.addEventListener('keydown', function(event) {
				switch (event.keyCode) {
					case 8: // prevent browser back 
						event.preventDefault();
						break;
					case 46: // delete
						editor.removeObject(editor.selected);
						editor.deselect();
						break;
				}
			}, false);

			var onWindowResize = function(event) {
				editor.signals.windowResize.dispatch();
			};

			window.addEventListener('resize', onWindowResize, false);
			onWindowResize();
			
			/**
			 *  @brief clear local data
			 *  @param none.
			 *  @return nothing.
			 *  @see Storage.js
			 *  
			 *  @author Renfei Song
			 */			
			
			function clearLocal() {
				localStorage.clear();
				indexedDB.deleteDatabase('threejs-editor');
			}

			/**
			 *  @brief load a model file from given uri to the editor.
			 *  @param String. file uri.
			 *  @return nothing.
			 *  @see Loader.js
			 *  
			 *  @author Renfei Song
			 */

			function loadFile(fileUri) {
				$.ajax({
					url: fileUri,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						console.log("JSON Loaded.");
						var filename = fileUri.replace(/^.*[\\\/]/, '');
						onReceiveJSON(data, filename);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.error("Load Error: " + errorThrown);
					}
				});
			}

			/**
			 *  @brief load a model into the stage.
			 *  @param JSON object
			 *  @param the filename
			 *  @return nothing.
			 *  @see Loader.js
			 *  
			 *  @author adapted from Loader.js
			 */

			function onReceiveJSON(data, filename) {
				if (data.metadata === undefined) {
					data.metadata = { type: 'Geometry' };
				}
				if (data.metadata.type === undefined) {
					data.metadata.type = 'Geometry';
				}
				if (data.metadata.version === undefined) {
					data.metadata.version = data.metadata.formatVersion;
				}
				if (data.metadata.type.toLowerCase() === 'geometry') {
					var loader = new THREE.JSONLoader();
					var result = loader.parse(data);
					var geometry = result.geometry;
					var material = result.materials !== undefined
								? new THREE.MeshFaceMaterial(result.materials)
								: new THREE.MeshPhongMaterial();
					geometry.sourceType = "ascii";
					geometry.sourceFile = filename;
					var mesh = new THREE.Mesh(geometry, material);
					mesh.name = filename;
					editor.addObject(mesh);
				} else if (data.metadata.type.toLowerCase() === 'object') {
					var loader = new THREE.ObjectLoader();
					var result = loader.parse(data);
					if (result instanceof THREE.Scene) {
						editor.setScene(result);
					} else {
						editor.addObject(result);
					}
				} else if (data.metadata.type.toLowerCase() === 'scene') {
					var loader = new THREE.SceneLoader();
					loader.parse(data, function(result) {
						editor.setScene(result.scene);
					}, '');
				}
			}
			
			clearLocal();
			loadFile('<?php echo $fileUri; ?>');
		</script>
	</body>
</html>