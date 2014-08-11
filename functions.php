<?php
/**
 * Dimensions functions and definitions
 *
 * Set up the site and provide some helper functions.
 * Should be included in every Dimensions presentation layer files.
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

function is_page($page_name)
{
    return basename($_SERVER['SCRIPT_NAME'], ".php") == $page_name;
}