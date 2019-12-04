<?php

namespace Controllers;

abstract class Controller {
    protected $set;
    protected function __construct($params = []){
       
    }



    public static function Load($controller,$params = []) {
        $t = explode("/",$controller);
        array_unshift($t,'Controllers');
        $controllerPath = implode("\\",$t);
        if ($controllerPath::$inst == null) {
            $controllerPath::$inst = new $controllerPath($params);
        }
        return $controllerPath::$inst;
    }

    public static function RunRouter($routeInfo) :bool {

     

        $splitUp = static::determineControllerAndMethod($routeInfo[1]);

        if (!static::CheckController($splitUp)) {
            return false;
        }

        $controller = new $splitUp->fullClass(null);
        if (!method_exists($controller,$splitUp->method)) {
            return false;
        }
        
        $params = static::manageParams($routeInfo[2],$controller,$splitUp);
        if ($params === null) {
            return false;
        }
        call_user_func_array([$controller,$splitUp->method],$params);
        return true;
    }

    private static function manageParams($params,&$controller,$info) :?array{
        $params = array_values($params);

        foreach($params as $key=>&$param) {
            $tmp = new \ReflectionParameter([$controller,$info->method],$key);
            $className = $tmp->getClass()->name; 
            if ($className !== null) {
                
                $param = (int)$param;
                if(in_array('Modules\Model',class_implements($className))) {
                    $paramObject = $className::_CreateObjectByID_($param);
                    if ($paramObject === null) {
                        return null;
                    }
                    $param = $paramObject;
                } else {
                    return null;
                }
            }
        }
        return $params;
    }

    private static function CheckController($info) :bool{
        if (!file_exists($info->physicalPath)) {
            return false;
        }
        require_once $info->physicalPath;
        if (!class_exists($info->fullClass)) {
            return false;
        }
        return true;
    }

    private static function determineControllerAndMethod($info) {
        $class = $info;
        $parts = explode("@", $class);
        $fullPath ="Controllers/".$parts[0];
        $classFullPath = str_replace("/","\\",$fullPath);
        $fullPath.='.php';

        $result = new \stdClass;
        $result->fullClass = $classFullPath;
        $result->physicalPath = $fullPath;
        if ($parts[1]) {
            $result->method = $parts[1];
        } else {
            $result->method = 'Index';
        }
        return $result;
    }
}