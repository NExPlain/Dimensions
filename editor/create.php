<!DOCTYPE html>
<html>
	<head>
		<title>新建模型</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	</head>
	<body>
        <script src="../lib/core/jquery/jquery-1.10.2.min.js"></script>
        <script src="../lib/core/bootstrap/js/bootstrap.min.js"></script>

		<link href="css/types.css" rel="stylesheet">
		<link href="css/light.css" rel="stylesheet" id="theme">
        <link href="css/modal.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

		<script src="../lib/webgl/Three-r65.min.js"></script>
		<script src="js/libs/system.min.js"></script>
		<script src="../lib/webgl/controls/EditorControls.js"></script>
		<script src="../lib/webgl/controls/TransformControls.js"></script>
		<script src="../lib/webgl/loaders/BabylonLoader.js"></script>
		<script src="../lib/webgl/loaders/ColladaLoader.js"></script>
		<script src="../lib/webgl/loaders/OBJLoader.js"></script>
		<script src="../lib/webgl/loaders/PLYLoader.js"></script>
		<script src="../lib/webgl/loaders/STLLoader.js"></script>
		<script src="../lib/webgl/loaders/UTF8Loader.js"></script>
		<script src="../lib/webgl/loaders/VRMLLoader.js"></script>
		<script src="../lib/webgl/loaders/VTKLoader.js"></script>
		<script src="../lib/webgl/loaders/ctm/lzma.js"></script>
		<script src="../lib/webgl/loaders/ctm/ctm.js"></script>
		<script src="../lib/webgl/loaders/ctm/CTMLoader.js"></script>
		<script src="../lib/webgl/exporters/SceneExporter.js"></script>
		<script src="../lib/webgl/exporters/OBJExporter.js"></script>
		<script src="../lib/webgl/renderers/SoftwareRenderer.js"></script>
		<script src="../lib/webgl/renderers/SVGRenderer.js"></script>

		<!-- WIP -->

		<script src="js/BufferGeometryUtils.js"></script>

		<script src="../lib/webgl/exporters/BufferGeometryExporter.js"></script>
		<script src="../lib/webgl/exporters/GeometryExporter.js"></script>
		<script src="../lib/webgl/exporters/MaterialExporter.js"></script>
		<script src="../lib/webgl/exporters/ObjectExporter.js"></script>
		<script src="../lib/webgl/renderers/WebGLRenderer3.js"></script>

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
		</script>
        <script src="uifix.js"></script>
	</body>
</html>