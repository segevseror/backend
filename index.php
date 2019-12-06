<?php
session_start ();
require 'dbase.php';
require 'route.php';
error_reporting(0);
if (Controllers\Controller::RunRouter($routeInfo) === true) {
   exit;
} 

