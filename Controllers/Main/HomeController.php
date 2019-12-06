<?php

namespace Controllers\Main;

class HomeController extends \Controllers\Controller
{
    public function __construct($parma)
    {
    }

    public function Index()
    {
        $_SESSION['test'] = 'segev';
        echo 'successfully completed';
        return true;
    }
}