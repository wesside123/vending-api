<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . "/inc/bootstrap.php";

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

/*if ($uri[2] != 'inventory' || array_key_exists(2, $uri) != false) {
    header("HTTP/1.1 404 Not Found");
    exit();
}*/

if ((isset($uri[2]) && $uri[2] != 'inventory')) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
 
require PROJECT_ROOT_PATH . "/Controller/Api/BeverageController.php";
 
$objFeedController = new BeverageController();
if ((isset($uri[2]) && $uri[2] == 'inventory')) 
    $strMethodName = $uri[2];
else 
    $strMethodName = "coins";
$objFeedController->{$strMethodName}();
?>