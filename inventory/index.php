<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*
/inventory endpoint is used to get quantity of beverages
*/
require "../inc/bootstrap.php";

//Separate the URI into an array
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//echo $uri;
$uri = explode( '/', $uri );

//Test for '/inventory' endpoint
if ((isset($uri[1]) && $uri[1] != 'inventory')) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

require PROJECT_ROOT_PATH . "/Controller/Api/BeverageController.php";

//Set $strMethodName for BeverageController
$objFeedController = new BeverageController();
if ((isset($uri[1]) && $uri[1] == 'inventory')) 
    $strMethodName = $uri[1];
else 
    $strMethodName = "coins";
$objFeedController->{$strMethodName}();
?>