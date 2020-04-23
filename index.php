<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
   header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
session_start ();
error_reporting(0);
require 'dbase.php';
require 'route.php';



if (Controllers\Controller::RunRouter($routeInfo) === true) {
   exit;
} 

