Menubar.File = function ( editor ) {

	var container = new UI.Panel();
	container.setClass( 'menu' );

	var title = new UI.Panel();
	title.setTextContent( '文件' );
	title.setMargin( '0px' );
	title.setPadding( '8px' );
	container.add( title );

	//

	var options = new UI.Panel();
	options.setClass( 'options' );
	container.add( options );

	// new

	var option = new UI.Panel();
	option.setClass( 'option' );
	option.setTextContent( '新建' );
	option.onClick( function () {

		if (confirm('新建一个文档将会扔掉您所有尚未储存的修改，请确认此操作。')) {
			editor.config.clear();
			editor.storage.clear(function() {
				location.href = location.pathname;
			});
		}

	});
	options.add( option );

	options.add( new UI.HorizontalRule() );


	// import

	var input = document.createElement( 'input' );
	input.type = 'file';
	input.addEventListener( 'change', function ( event ) {

		editor.loader.loadFile( input.files[ 0 ] );

	} );

	var option = new UI.Panel();
	option.setClass( 'option' );
	option.setTextContent( '导入' );
	option.onClick( function () {

		input.click();

	} );
	options.add( option );

	options.add( new UI.HorizontalRule() );


	// export geometry

	var option = new UI.Panel();
	option.setClass( 'option' );
	option.setTextContent( '导出 Geometry' );
	option.onClick( function () {

		var object = editor.selected;

		if ( object === null ) {

			alert( '尚未选择任何一个 Object' );
			return;

		}

		var geometry = object.geometry;

		if ( geometry === undefined ) {

			alert( 'The selected object doesn\'t have geometry.' );
			return;

		}

		if ( geometry instanceof THREE.BufferGeometry ) {

			exportGeometry( THREE.BufferGeometryExporter );

		} else if ( geometry instanceof THREE.Geometry ) {

			exportGeometry( THREE.GeometryExporter );

		}

	} );
	options.add( option );

	// export object

	var option = new UI.Panel();
	option.setClass( 'option' );
	option.setTextContent( '导出 Object' );
	option.onClick( function () {

		if ( editor.selected === null ) {

			alert( '尚未选择任何一个 Object' );
			return;

		}

		exportObject( THREE.ObjectExporter );

	} );
	options.add( option );

	// export scene

	var option = new UI.Panel();
	option.setClass( 'option' );
	option.setTextContent( '导出 Scene' );
	option.onClick( function () {

		exportScene( THREE.ObjectExporter );

	} );
	options.add( option );

	// export OBJ

	var option = new UI.Panel();
	option.setClass( 'option' );
	option.setTextContent( '导出 OBJ' );
	option.onClick( function () {

		exportGeometry( THREE.OBJExporter );

	} );
	options.add( option );

	var exportGeometry = function ( exporterClass ) {

		var object = editor.selected;
		var exporter = new exporterClass();

		var output;

		if ( exporter instanceof THREE.BufferGeometryExporter ||
		     exporter instanceof THREE.GeometryExporter ) {

			output = JSON.stringify( exporter.parse( object.geometry ), null, '\t' );
			output = output.replace( /[\n\t]+([\d\.e\-\[\]]+)/g, '$1' );

		} else {

			output = exporter.parse( object.geometry );

		}

		var blob = new Blob( [ output ], { type: 'text/plain' } );
		var objectURL = URL.createObjectURL( blob );

		window.open( objectURL, '_blank' );
		window.focus();

	};

	var exportObject = function ( exporterClass ) {

		var object = editor.selected;
		var exporter = new exporterClass();

		var output = JSON.stringify( exporter.parse( object ), null, '\t' );
		output = output.replace( /[\n\t]+([\d\.e\-\[\]]+)/g, '$1' );

		var blob = new Blob( [ output ], { type: 'text/plain' } );
		var objectURL = URL.createObjectURL( blob );

		window.open( objectURL, '_blank' );
		window.focus();

	};

	var exportScene = function ( exporterClass ) {

		var exporter = new exporterClass();

		var output = JSON.stringify( exporter.parse( editor.scene ), null, '\t' );
		output = output.replace( /[\n\t]+([\d\.e\-\[\]]+)/g, '$1' );

		var blob = new Blob( [ output ], { type: 'text/plain' } );
		var objectURL = URL.createObjectURL( blob );

		window.open( objectURL, '_blank' );
		window.focus();

	};

	return container;

}
