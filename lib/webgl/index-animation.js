var container;
var camera, scene, projector, renderer;
var width, height;

var PI2 = Math.PI * 2;

var programFill = function ( context ) {

    context.beginPath();
    context.arc( 0, 0, 0.5, 0, PI2, true );
    context.fill();

}

var programStroke = function ( context ) {

    context.lineWidth = 0.025;
    context.beginPath();
    context.arc( 0, 0, 0.5, 0, PI2, true );
    context.stroke();

}

var mouse = { x: 0, y: 0 }, INTERSECTED;

init();
animate();

function init() {

    container = document.getElementById( 'intro-video' );
    //container = $("#intro-video");
    //document.body.appendChild( container );

    width = $("#intro-video").width();
    height = $("#intro-video").height();

    camera = new THREE.PerspectiveCamera( 70, width / height, 1, 10000 );
    camera.position.set( 0, 300, 500 );

    scene = new THREE.Scene();

    for ( var i = 0; i < 100; i ++ ) {

        var particle = new THREE.Sprite( new THREE.SpriteCanvasMaterial( { color: Math.random() * 0x808080 + 0x808080, program: programStroke } ) );
        particle.position.x = Math.random() * 800;
        particle.position.y = Math.random() * 800;
        particle.position.z = Math.random() * 800;
        particle.scale.x = particle.scale.y = Math.random() * 100 + 50;
        scene.add( particle );

    }

    projector = new THREE.Projector();

    renderer = new THREE.CanvasRenderer();
    renderer.setSize( width, height );

    container.appendChild( renderer.domElement );

    document.addEventListener( 'mousemove', onDocumentMouseMove, false );

    //

    window.addEventListener( 'resize', onWindowResize, false );

}

function onWindowResize() {

    camera.aspect = width / height;
    camera.updateProjectionMatrix();

    renderer.setSize( width, height );

}

function onDocumentMouseMove( event ) {

    event.preventDefault();

    mouse.x = ( event.clientX / width ) * 2 - 1;
    mouse.y = - ( event.clientY / height ) * 2 + 1;

}

//

function animate() {

    requestAnimationFrame( animate );

    render();
}

var radius = 600;
var theta = 0;

function render() {

    // rotate camera

    theta += 0.1;

    camera.position.x = radius * Math.sin( THREE.Math.degToRad( theta ) );
    camera.position.y = radius * Math.sin( THREE.Math.degToRad( theta ) );
    camera.position.z = radius * Math.cos( THREE.Math.degToRad( theta ) );
    camera.lookAt( scene.position );

    // find intersections

    camera.updateMatrixWorld();

    var vector = new THREE.Vector3( mouse.x, mouse.y, 0.5 );
    projector.unprojectVector( vector, camera );

    var raycaster = new THREE.Raycaster( camera.position, vector.sub( camera.position ).normalize() );

    var intersects = raycaster.intersectObjects( scene.children );

    if ( intersects.length > 0 ) {

        if ( INTERSECTED != intersects[ 0 ].object ) {

            if ( INTERSECTED ) INTERSECTED.material.program = programStroke;

            INTERSECTED = intersects[ 0 ].object;
            INTERSECTED.material.program = programFill;

        }

    } else {

        if ( INTERSECTED ) INTERSECTED.material.program = programStroke;

        INTERSECTED = null;

    }

    renderer.render( scene, camera );

}