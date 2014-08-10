<?php
/**
 * Dimensions functions and definitions
 *
 * Set up the site and provide some helper functions.
 *
 * @author Renfei Song
 */

require_once "define.php";

function get_header($title)
{
    $__page_title = $title;
    require_once "header.php";
}

function get_footer()
{
    require_once "footer.php";
}