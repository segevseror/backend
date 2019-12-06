<?php
header('Access-Control-Allow-Origin: chrome-extension://jkigokhdddmikaghkoeledahofdmndkb');
session_start ();
error_reporting(0);
require 'dbase.php';
require 'route.php';

if (Controllers\Controller::RunRouter($routeInfo) === true) {
   exit;
} 

