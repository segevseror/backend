<?php

namespace Controllers\Main;

class HomeController extends \Controllers\Controller
{
    public function __construct($parma)
    {
    }

    public function Index()
    {

        var_dump($_SESSION , $_COOKIE);
        echo 'successfully completed';
        return true;
    }
}