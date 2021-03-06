Sidebar.Geometry.PlaneGeometry = function ( signals, object ) {

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


	//

	function update() {

		delete object.__webglInit; // TODO: Remove hack (WebGLRenderer refactoring)

		object.geometry.dispose();

		object.geometry = new THREE.PlaneGeometry(
			width.getValue(),
			height.getValue(),
			widthSegments.getValue(),
			heightSegments.getValue()
		);

		object.geometry.computeBoundingSphere();

		signals.objectChanged.dispatch( object );

	}

	return container;

}
