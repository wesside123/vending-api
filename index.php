<?php
/*
index.php is the main endpoint for the service
*/
require __DIR__ . "/inc/bootstrap.php";

//Separate the URI into an array
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

//Test for '/inventory' endpoint
if ((isset($uri[2]) && $uri[2] != 'inventory')) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

require PROJECT_ROOT_PATH . "/Controller/Api/BeverageController.php";

//Set $strMethodName for BeverageController
$objFeedController = new BeverageController();
if ((isset($uri[2]) && $uri[2] == 'inventory')) 
    $strMethodName = $uri[2];
else 
    $strMethodName = "coins";
$objFeedController->{$strMethodName}();
?>