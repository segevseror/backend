<?php

namespace Controllers\Main;

class HomeController extends \Controllers\Controller
{
    public function __construct($parma)
    {
    }

    public function Index()
    {

        $_SESSIO['login'] = '';
        unset($_SESSIO['login']);
        unset($_SESSIO);
        echo 'successfully completed';
        return true;
    }
}