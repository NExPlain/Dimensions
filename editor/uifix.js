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

/* 'Nothing-To-Show' Panel */

var NTSPanel = document.createElement('div');
NTSPanel.classList.add('Panel');
NTSPanel.classList.add('Nothing');
NTSPanel.innerText = 'Nothing here :)';
document.getElementById('sidebar').appendChild(NTSPanel);

$("#sidebar > .Panel").wrapAll("<div class='sidebar-panels'></div>")

/* Control Enhancements */
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