<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-type: application/json');
session_start ();
error_reporting(0);
require 'dbase.php';
require 'route.php';

if (Controllers\Controller::RunRouter($routeInfo) === true) {
   exit;
} 

