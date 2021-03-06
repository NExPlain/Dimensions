Sidebar.Geometry.CubeGeometry = function ( signals, object ) {

	var container = new UI.Panel();

	var geometry = object.geometry;

	// width

	var widthRow = new UI.Panel();
    widthRow.setClass('Panel final-row');
	var width = new UI.Number( geometry.width ).onChange( update );

	widthRow.add( new UI.Text( 'Width' ).setWidth( '90px' ) );
	widthRow.add( width );

	container.add( widthRow );

	// height

	var heightRow = new UI.Panel();
    heightRow.setClass('Panel final-row');
	var height = new UI.Number( geometry.height ).onChange( update );

	heightRow.add( new UI.Text( 'Height' ).setWidth( '90px' ) );
	heightRow.add( height );

	container.add( heightRow );

	// depth

	var depthRow = new UI.Panel();
    depthRow.setClass('Panel final-row');
	var depth = new UI.Number( geometry.depth ).onChange( update );

	depthRow.add( new UI.Text( 'Depth' ).setWidth( '90px' ) );
	depthRow.add( depth );

	container.add( depthRow );

	// widthSegments

	var widthSegmentsRow = new UI.Panel();
    widthSegmentsRow.setClass('Panel final-row');
	var widthSegments = new UI.Integer( geometry.widthSegments ).setRange( 1, Infinity ).onChange( update );

	widthSegmentsRow.add( new UI.Text( 'Width segments' ).setWidth( '90px' ) );
	widthSegmentsRow.add( widthSegments );

	container.add( widthSegmentsRow );

	// heightSegments

	var heightSegmentsRow = new UI.Panel();
    heightSegmentsRow.setClass('Panel final-row');
	var heightSegments = new UI.Integer( geometry.heightSegments ).setRange( 1, Infinity ).onChange( update );

	heightSegmentsRow.add( new UI.Text( 'Height segments' ).setWidth( '90px' ) );
	heightSegmentsRow.add( heightSegments );

	container.add( heightSegmentsRow );

	// depthSegments

	var depthSegmentsRow = new UI.Panel();
    depthSegmentsRow.setClass('Panel final-row');
	var depthSegments = new UI.Integer( geometry.depthSegments ).setRange( 1, Infinity ).onChange( update );

	depthSegmentsRow.add( new UI.Text( 'Height segments' ).setWidth( '90px' ) );
	depthSegmentsRow.add( depthSegments );

	container.add( depthSegmentsRow );

	//

	function update() {

		delete object.__webglInit; // TODO: Remove hack (WebGLRenderer refactoring)

		object.geometry.dispose();

		object.geometry = new THREE.CubeGeometry(
			width.getValue(),
			height.getValue(),
			depth.getValue(),
			widthSegments.getValue(),
			heightSegments.getValue(),
			depthSegments.getValue()
		);

		object.geometry.computeBoundingSphere();

		signals.objectChanged.dispatch( object );

	}

	return container;

}
