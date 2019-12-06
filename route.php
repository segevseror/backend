<?php

require 'vendor/autoload.php';

require_once 'Controllers/Controller.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'Main/HomeController@Index');
    $r->addGroup('/user', function (FastRoute\RouteCollector $r) {
        $r->addRoute('POST', '/adduser', 'Main/userConroller@RegisterUser');
        $r->addRoute('POST', '/login', 'Main/userConroller@LogIn');
        $r->addRoute('POST', '/addmovies', 'Main/userConroller@addMovies');

        $r->addRoute('GET', '/getuser', 'Main/userConroller@getUser');
        $r->addRoute('GET', '/getusers', 'Main/userConroller@getUsers');
        $r->addRoute('GET', '/getmovies', 'Main/userConroller@GetMovies');
        $r->addRoute('GET', '/cheackuser', 'Main/userConroller@CheckUser');
        $r->addRoute('GET', '/logout', 'Main/userConroller@Logout');
     
    });

 
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

// if($_SERVER['HTTP_HOST'] != "ball-app.x"){
//     $uri = '/ben-api/api-golball/';
// }

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);


switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo 'not found';
        return false;
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
   
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        break;
}
