<?php

namespace Controllers\Main;

class HomeController extends \Controllers\Controller
{
    public function __construct($parma)
    {
    }

    public function Index()
    {

        unset($_SESSIO['login']);
        unset($_SESSIO['test']);
        echo 'successfully completed';
        return true;
    }
}