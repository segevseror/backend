<?php

namespace Controllers\Main;

class HomeController extends \Controllers\Controller
{
    public function __construct($parma)
    {
    }

    public function Index()
    {
        echo 'successfully completed';
        return true;
    }

    public function Register(){

        $html ='
        <form action="user/adduser" method="POST">
            <div>
                <input type="text" name="username">
            </div>
            <div>
                <input type="text" name="password">
            </div>
            <div>
                <input type="text" name="passAdmin">
            </div>
            <input type="submit" value="submit">
        </form>
        
        ';

        echo $html;
        die('s');
    }
}