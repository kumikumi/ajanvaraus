<?php

/*
  APPLICATION_LIVE will be used in process to tell if we are in a development or production environment.  It's generally set as early as possible (often the first code to run), before any config, url routing, etc.
 */

//if (preg_match("%^(www.)?livedomain.com$%", $_SERVER["HTTP_HOST"])) {
//    define('APPLICATION_LIVE', true);
//} elseif (preg_match("%^(www.)?devdomain.net$%", $_SERVER["HTTP_HOST"])) {
//    define('APPLICATION_LIVE', false);
//} else {
//    die("INVALID HOST REQUEST (" . $_SERVER["HTTP_HOST"] . ")");
//    // Log or take other appropriate action.
//}

define('APPLICATION_LIVE', false);


/*
  --------------------------------------------------------------------
  DEFAULT ERROR HANDLING
  --------------------------------------------------------------------
  Default error logging.  Some of these may be changed later based on APPLICATION_LIVE.
 */
error_reporting(E_ALL & ~E_STRICT);
ini_set("display_errors", "0");
ini_set("display_startup_errors", "0");
ini_set("log_errors", 1);
ini_set("log_errors_max_len", 0);
ini_set("error_log", APPLICATION_ROOT . "logs/php_error_log.txt");
ini_set("display_errors", "0");
ini_set("display_startup_errors", "0");

if (!APPLICATION_LIVE) {
    // A few changes to error handling for development.
    // We will want errors to be visible during development.
    ini_set("display_errors", "1");
    ini_set("display_startup_errors", "1");
    ini_set("html_errors", "0");
    ini_set("docref_root", "http://www.php.net/");
    ini_set("error_prepend_string", "<div style='color:red; font-family:verdana; border:1px solid red; padding:5px;'>");
    ini_set("error_append_string", "</div>");
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
