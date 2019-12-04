<?php
error_reporting(0);
require_once('dbase.php');

session_start();

switch($_GET['act']){

    

    case "getuser":
    if($_SESSION['user']){


       $sql = 'SELECT * FROM user_movies WHERE `user_id` = '.$_SESSION['user']['id'].' ';
       $res = $conn->query($sql);
       if($res->num_rows){
         
           $moviesarr = [];
           while($movie = $res->fetch_assoc()){
            array_push($moviesarr , $movie['movies_id']);
           }

           $user = [
               'id' => $_SESSION['user']['id'],
               'movies' => $moviesarr
           ];
           
          echo  '|'.json_encode($user);
          
       }else{
        echo 'elsem row';
       }
       
    }else{
        return false;
    }
    break;

    case "addnovies":
        if(checkUser()){
            $sql = 'INSERT INTO `user_movies`(`id`, `user_id`, `movies_id`) VALUES ("",'.$_SESSION['user']['id'].','.$_POST['id'].')';
            $conn->query($sql);
            if($conn->num_ruws){
                echo 'secussess';
            }else{
                echo 'error';
            }
        }else{
            echo 'no connect';
        }

    break;
    

    case "login":

    $username = mysqli_real_escape_string($conn ,$_POST['user']);
    $password = mysqli_real_escape_string($conn ,$_POST['pass']);

    $sql = 'SELECT * FROM `users` WHERE `username` = "'. $username . '" AND `pass`= "'.$password.'" ';
    $res = $conn->query($sql);

    if($res->num_rows){
        $user = $res->fetch_assoc();
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username']
        ];
        echo 'seccusses';
    }else{
        echo 'errorlogin';
    }

    break;


    default: 

    echo 'default';
    break;
}

function checkUser(){
    if($_SESSION['user']){
        return true;
    }else{
        return false;
    }
}


