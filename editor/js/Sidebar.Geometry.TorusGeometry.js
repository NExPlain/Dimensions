Sidebar.Geometry.TorusGeometry = function ( signals, object ) {

	var container = new UI.Panel();

	var geometry = object.geometry;

	// radius

	var radiusRow = new UI.Panel();
    radiusRow.setClass('Panel final-row');
	var radius = new UI.Number( geometry.radius ).onChange( update );

	radiusRow.add( new UI.Text( 'Radius' ).setWidth( '90px' ) );
	radiusRow.add( radius );

	container.add( radiusRow );

	// tube

	var tubeRow = new UI.Panel();
    tubeRow.setClass('Panel final-row');
	var tube = new UI.Number( geometry.tube ).onChange( update );

	tubeRow.add( new UI.Text( 'Tube' ).setWidth( '90px' ) );
	tubeRow.add( tube );

	container.add( tubeRow );

	// radialSegments

	var radialSegmentsRow = new UI.Panel();
    radialSegmentsRow.setClass('Panel final-row');
	var radialSegments = new UI.Integer( geometry.radialSegments ).setRange( 1, Infinity ).onChange( update );

	radialSegmentsRow.add( new UI.Text( 'Radial segments' ).setWidth( '90px' ) );
	radialSegmentsRow.add( radialSegments );

	container.add( radialSegmentsRow );

	// tubularSegments

	var tubularSegmentsRow = new UI.Panel();
    tubularSegmentsRow.setClass('Panel final-row');
	var tubularSegments = new UI.Integer( geometry.tubularSegments ).setRange( 1, Infinity ).onChange( update );

	tubularSegmentsRow.add( new UI.Text( 'Tubular segments' ).setWidth( '90px' ) );
	tubularSegmentsRow.add( tubularSegments );

	container.add( tubularSegmentsRow );

	// arc

	var arcRow = new UI.Panel();
    arcRow.setClass('Panel final-row');
	var arc = new UI.Number( geometry.arc ).onChange( update );

	arcRow.add( new UI.Text( 'Arc' ).setWidth( '90px' ) );
	arcRow.add( arc );

	container.add( arcRow );


	//

	function update() {

		delete object.__webglInit; // TODO: Remove hack (WebGLRenderer refactoring)

		object.geometry.dispose();

		object.geometry = new THREE.TorusGeometry(
			radius.getValue(),
			tube.getValue(),
			radialSegments.getValue(),
			tubularSegments.getValue(),
			arc.getValue()
		);

		object.geometry.computeBoundingSphere();

		signals.objectChanged.dispatch( object );

	}

	return container;

}
