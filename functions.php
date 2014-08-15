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

date_default_timezone_set("Asia/Shanghai");

function is_page($page_name)
{
    return basename($_SERVER['SCRIPT_NAME'], ".php") == $page_name;
}

function get_header($title)
{
    if (!is_page('header')) {
        $__page_title = $title;
        require_once "header.php";
    }
}

function get_footer()
{
    if (!is_page('footer')) {
        require_once "footer.php";
    }
}