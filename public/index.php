<?php


require_once('../config/config.php');
session_start();

$url_array = explode("/", $_SERVER['REQUEST_URI']);

$page_name = "index";
if($url_array[1] != "")
	$page_name = $url_array[1];

$action = "";
if($url_array[2] != "")
    $action = $url_array[2];


$variables = prepareVariables($page_name, $action);

$isAjax = ($action == "") ? false : true;

echo renderPage($page_name, $variables, $isAjax);

