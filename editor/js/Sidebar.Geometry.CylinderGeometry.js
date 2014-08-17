Sidebar.Geometry.CylinderGeometry = function ( signals, object ) {

	var container = new UI.Panel();

	var geometry = object.geometry;

	// radiusTop

	var radiusTopRow = new UI.Panel();
    radiusTopRow.setClass('Panel final-row');
	var radiusTop = new UI.Number( geometry.radiusTop ).onChange( update );

	radiusTopRow.add( new UI.Text( 'Radius top' ).setWidth( '90px' ) );
	radiusTopRow.add( radiusTop );

	container.add( radiusTopRow );

	// radiusBottom

	var radiusBottomRow = new UI.Panel();
    radiusBottomRow.setClass('Panel final-row');
	var radiusBottom = new UI.Number( geometry.radiusBottom ).onChange( update );

	radiusBottomRow.add( new UI.Text( 'Radius bottom' ).setWidth( '90px' ) );
	radiusBottomRow.add( radiusBottom );

	container.add( radiusBottomRow );

	// height

	var heightRow = new UI.Panel();
    heightRow.setClass('Panel final-row');
	var height = new UI.Number( geometry.height ).onChange( update );

	heightRow.add( new UI.Text( 'Height' ).setWidth( '90px' ) );
	heightRow.add( height );

	container.add( heightRow );

	// radialSegments

	var radialSegmentsRow = new UI.Panel();
    radialSegmentsRow.setClass('Panel final-row');
	var radialSegments = new UI.Integer( geometry.radialSegments ).setRange( 1, Infinity ).onChange( update );

	radialSegmentsRow.add( new UI.Text( 'Radial segments' ).setWidth( '90px' ) );
	radialSegmentsRow.add( radialSegments );

	container.add( radialSegmentsRow );

	// heightSegments

	var heightSegmentsRow = new UI.Panel();
    heightSegmentsRow.setClass('Panel final-row');
	var heightSegments = new UI.Integer( geometry.heightSegments ).setRange( 1, Infinity ).onChange( update );

	heightSegmentsRow.add( new UI.Text( 'Height segments' ).setWidth( '90px' ) );
	heightSegmentsRow.add( heightSegments );

	container.add( heightSegmentsRow );

	// openEnded

	var openEndedRow = new UI.Panel();
    openEndedRow.setClass('Panel final-row');
	var openEnded = new UI.Checkbox( geometry.openEnded ).onChange( update );

	openEndedRow.add( new UI.Text( 'Open ended' ).setWidth( '90px' ) );
	openEndedRow.add( openEnded );

	container.add( openEndedRow );

	//

	function update() {

		delete object.__webglInit; // TODO: Remove hack (WebGLRenderer refactoring)

		object.geometry.dispose();

		object.geometry = new THREE.CylinderGeometry(
			radiusTop.getValue(),
			radiusBottom.getValue(),
			height.getValue(),
			radialSegments.getValue(),
			heightSegments.getValue(),
			openEnded.getValue()
		);

		object.geometry.computeBoundingSphere();

		signals.objectChanged.dispatch( object );

	}

	return container;

}
