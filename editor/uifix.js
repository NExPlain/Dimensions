/**
 * UI Elements
 * -----------------------------------------------------------------------------
 */

/* Sidebar Toggle Button */

var sidebarToggleButtonInner = "<div class='Panel'></div>";
var sidebarToggleButton = document.createElement('div');
sidebarToggleButton.classList.add('menu');
sidebarToggleButton.classList.add('toggle');
sidebarToggleButton.classList.add('button');
sidebarToggleButton.innerHTML = sidebarToggleButtonInner;
document.getElementById('menubar').appendChild(sidebarToggleButton);

/* Navigation Bar (in Sidebar) */

var navigationBarInner =
    "<ul>" +
        "<li class='bar-item basic current' data-section='basic'>Basic</li>" +
        "<li class='bar-item mesh' data-section='mesh'>Mesh</li>" +
        "<li class='bar-item geometry' data-section='geometry'>Geometry</li>" +
        "<li class='bar-item material' data-section='material'>Material</li>" +
    "</ul>";
var navigationBar = document.createElement('div');
navigationBar.id = 'navigation-bar';
navigationBar.classList.add('navigation-bar');
navigationBar.innerHTML = navigationBarInner;
var firstPanel = $("#sidebar .Panel:first-child")[0];
document.getElementById('sidebar').insertBefore(navigationBar, firstPanel);

// 'Nothing-To-Show' Panel

var NTSPanel = document.createElement('div');
NTSPanel.classList.add('Panel');
NTSPanel.classList.add('Nothing');
NTSPanel.innerText = 'Nothing here :)';
document.getElementById('sidebar').appendChild(NTSPanel);

$("#sidebar > .Panel").wrapAll("<div class='sidebar-panels'></div>")

/* Sidebar Control Enhancements */

// select

$(".Select").wrap("<label class='select-wrapper'></label>");

// checkbox

$("#sidebar .Checkbox").wrap("<div class='checkbox-wrapper'></div>");
var checkboxId = 0;
$("#sidebar .Checkbox").each(function() {
    $(this).attr('id', 'checkbox-' + checkboxId);
    $('<label class="checkbox-label" for="checkbox-' + checkboxId + '"></label>').insertAfter($(this));
    checkboxId++;
});
$('<br class="after-checkbox">').insertAfter('.checkbox-wrapper');

// color

setTimeout(function() {
    $('#sidebar canvas + input').val('Choose an image...');
},1000);

/* 'Add' Modal Box */

// Build Modal

var addModalInner =
    '<div class="modal-header">' +
        '<button class="close" data-dismiss="modal">&times;</button>' +
        '<h3>Adding stuff</h3>' +
    '</div>' +
    '<div class="modal-body">' +
    '</div>' +
    '<div class="modal-footer">' +
        '<button class="close-button" data-dismiss="modal">关闭窗口</button>' +
    '</div>';
var addModal = document.createElement('div');
addModal.id = "add-modal";
addModal.classList.add('modal');
addModal.classList.add('hide');
addModal.classList.add('fade');
addModal.innerHTML = addModalInner;
document.body.appendChild(addModal);

// Add Content

var addContent = '<div class="category shapes"><div class="title">Shapes</div><div class="option plane"><div class="image"></div><div class="text">Plane</div></div><div class="option cube"><div class="image"></div><div class="text">Cube</div></div><div class="option circle"><div class="image"></div><div class="text">Circle</div></div><div class="option cylinder"><div class="image"></div><div class="text">Cylinder</div></div><div class="option sphere"><div class="image"></div><div class="text">Sphere</div></div><div class="option icosahedron"><div class="image"></div><div class="text">Icosahe-dron</div></div><div class="option torus"><div class="image"></div><div class="text">Torus</div></div><div class="option torusknot"><div class="image"></div><div class="text">Torus Knot</div></div></div><div class="category lights"><div class="title">Lights</div><div class="option point-light"><div class="image"></div><div class="text">Point light</div></div><div class="option spot-light"><div class="image"></div><div class="text">Spot light</div></div><div class="option directional-light"><div class="image"></div><div class="text">Directional light</div></div><div class="option hemisphere-light"><div class="image"></div><div class="text">Hemisphere light</div></div><div class="option ambient-light"><div class="image"></div><div class="text">Ambient light</div></div></div><div class="category others"><div class="title">Miscellaneous</div><div class="option sprite"><div class="image"></div><div class="text">Sprite</div></div></div>';
$('#add-modal .modal-body').html(addContent);

// Add Activation Button

$('#menubar .add .Panel').attr('data-toggle','modal');
$('#menubar .add .Panel').attr('data-target','#add-modal');

/**
 * Action / Event Hooks
 * -----------------------------------------------------------------------------
 */

/* Sidebar Toggle Button */

$('.menu.toggle').click(function() {
    $('#sidebar').toggleClass('hidden');
    $('body').toggleClass('sidebar-hidden');
    for (var i = 0; i <= 220; i += 20) {
        setTimeout(onWindowResize, i);
    }
});

/* Navigation Bar */

$('#sidebar .Panel.Renderer').removeClass('hidden');
$('#sidebar .Panel.Scene').removeClass('hidden');
$('#sidebar .Panel.Mesh').addClass('hidden');
$('#sidebar .Panel.Geometry').addClass('hidden');
$('#sidebar .Panel.Material').addClass('hidden');
$('#sidebar .Panel.Animation').removeClass('hidden');

$('#navigation-bar .bar-item').click(function() {
    $('#navigation-bar .bar-item.current').removeClass('current');
    $(this).addClass('current');
    switch ($(this).data('section')) {
        case 'basic':
            $('#sidebar .Panel.Renderer').removeClass('hidden');
            $('#sidebar .Panel.Scene').removeClass('hidden');
            $('#sidebar .Panel.Mesh').addClass('hidden');
            $('#sidebar .Panel.Geometry').addClass('hidden');
            $('#sidebar .Panel.Material').addClass('hidden');
            $('#sidebar .Panel.Animation').removeClass('hidden');
            break;
        case 'mesh':
            $('#sidebar .Panel.Renderer').addClass('hidden');
            $('#sidebar .Panel.Scene').addClass('hidden');
            $('#sidebar .Panel.Mesh').removeClass('hidden');
            $('#sidebar .Panel.Geometry').addClass('hidden');
            $('#sidebar .Panel.Material').addClass('hidden');
            $('#sidebar .Panel.Animation').addClass('hidden');
            break;
        case 'geometry':
            $('#sidebar .Panel.Renderer').addClass('hidden');
            $('#sidebar .Panel.Scene').addClass('hidden');
            $('#sidebar .Panel.Mesh').addClass('hidden');
            $('#sidebar .Panel.Geometry').removeClass('hidden');
            $('#sidebar .Panel.Material').addClass('hidden');
            $('#sidebar .Panel.Animation').addClass('hidden');
            break;
        case 'material':
            $('#sidebar .Panel.Renderer').addClass('hidden');
            $('#sidebar .Panel.Scene').addClass('hidden');
            $('#sidebar .Panel.Mesh').addClass('hidden');
            $('#sidebar .Panel.Geometry').addClass('hidden');
            $('#sidebar .Panel.Material').removeClass('hidden');
            $('#sidebar .Panel.Animation').addClass('hidden');
            break;
        default:
            break;
    }
});

/* 'Add' Modal */

$options = $('.menu.add > .options > .option');
$plane = $($options[0]);
$cube = $($options[1]);
$circle = $($options[2]);
$cylinder = $($options[3]);
$sphere = $($options[4]);
$icosahedron = $($options[5]);
$torus = $($options[6]);
$torusKnot = $($options[7]);
$sprite = $($options[8]);
$pointLight = $($options[9]);
$spotLight = $($options[10]);
$directionalLight = $($options[11]);
$hemisphereLight = $($options[12]);
$ambientLight = $($options[13]);
$('.modal .plane').click(function() {$('#add-modal').modal('hide');$plane.trigger('click')});
$('.modal .cube').click(function() {$('#add-modal').modal('hide');$cube.trigger('click')});
$('.modal .circle').click(function() {$('#add-modal').modal('hide');$circle.trigger('click')});
$('.modal .cylinder').click(function() {$('#add-modal').modal('hide');$cylinder.trigger('click')});
$('.modal .sphere').click(function() {$('#add-modal').modal('hide');$sphere.trigger('click')});
$('.modal .icosahedron').click(function() {$('#add-modal').modal('hide');$icosahedron.trigger('click')});
$('.modal .torus').click(function() {$('#add-modal').modal('hide');$torus.trigger('click')});
$('.modal .torusknot').click(function() {$('#add-modal').modal('hide');$torusKnot.trigger('click')});
$('.modal .sprite').click(function() {$('#add-modal').modal('hide');$sprite.trigger('click')});
$('.modal .point-light').click(function() {$('#add-modal').modal('hide');$pointLight.trigger('click')});
$('.modal .spot-light').click(function() {$('#add-modal').modal('hide');$spotLight.trigger('click')});
$('.modal .directional-light').click(function() {$('#add-modal').modal('hide');$directionalLight.trigger('click')});
$('.modal .hemisphere-light').click(function() {$('#add-modal').modal('hide');$hemisphereLight.trigger('click')});
$('.modal .ambient-light').click(function() {$('#add-modal').modal('hide');$ambientLight.trigger('click')});