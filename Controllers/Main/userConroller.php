<?php

namespace Controllers\Main;

class userConroller extends \Controllers\Controller
{
    public function __construct($parma)
    { 

        

    }

    public function LogIn()
    {
        
        global $conn;
        $userName = $_POST['username'];
        $pass = $_POST['password'];
        if(!$userName && !$pass){
            echo json_encode([
                'act' => 'false',
                'massage' => 'User name or Password is not available'
            ]);
            return false;
        }

        if (!$_SESSION['login']) {
            $login = pg_query_params($conn , "SELECT * FROM users WHERE username = $1 AND pass = $2" , [$userName , $pass]);
            $user = pg_fetch_assoc($login);
            if ($user && $user['id']) {
                unset($_SESSION['login']);
                unset($_SESSION);
                $_SESSION['login'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'level' => $user['lvl']
                ];
                setcookie("testLogin", 'test' , time()+3600);
                echo json_encode([
                    'act' => 'true',
                    'id' => $user['id'],
                    'username' => $user['username']
                ]);
                return true;
            }
        }else{
            echo json_encode([
                'act' => 'fasle',
                'message'=>'This user is connected you can Logout from /logout'
            ]);
            return false;
        }
    }

    public function Logout(){
        $_SESSION['login'] = '';
        unset($_SESSION['login']);
        echo 'true';
        return true;
    }

    public function CheckUser(){
    
        var_dump($_COOKIE['testLogin'] , $_SESSION);
        die('s');
    
        if(isset($_SESSION['login']) && $_SESSION['login']['id']){
            echo json_encode([
                'act' => 'true',
                'username' => $_SESSION['login']['username']
            ]);
            return true;
        }else{
            echo json_encode([
                'act'=> 'fasle',
                'message' => [$_COOKIE , $_SESSION]
            ]);
            return false;
        }

        echo 'false';
        return false;
    }

    public function RegisterUser()
    {
        global $conn;
        $userName = $_POST['username'];
        $pass = $_POST['password'];
        $errors = [];
        if (!$userName) {
            $errors['usename'] = 'you most give the username';
        }
        if (!$pass) {
            $errors['password'] = 'you most give the password';
        }
        if ($errors) {
            echo json_encode(['act' => 'false', 'message' => $errors]);
            return false;
        } else {
            //check if username is exists;
            $checkUserName = pg_query_params($conn , 'SELECT id FROM users WHERE username = $1 ' , [$userName]);
        
            $checkUserName = pg_fetch_assoc($checkUserName);
         
            if ($checkUserName > 0) {
                echo json_encode(['act' => false, 'message' => 'the username is exists']);
                return false;
            } else {
                $adduser = pg_query_params($conn , 'INSERT INTO users (username,pass)
                VALUES ($1, $2)' , [$userName , $pass]);
                $adduserRow = pg_affected_rows($adduser);
            
                if ($adduserRow > 0) {
                    $getuser = pg_query_params($conn , 'SELECT id,username FROM users WHERE username = $1' , [$userName]);
                    $getuser = pg_fetch_assoc( $getuser );
                    echo json_encode(['act' => true, 'data' => [
                        'id' => $getuser['id'],
                        'username' => $getuser['username']
                    ]]);
                    $_SESSION['login'] = [
                        'id' => $getuser['id'],
                        'username' => $getuser['username']
                    ];
                    return true;
                } else {
                    echo json_encode(['act' => 'fasle']);
                    return false;
                }
            }
            echo 'error 0000';
            return false;
        }
    }

    public function addMovies(){
        global $conn;
        if(! $_SESSION['login'] || ! $_SESSION['login']['id']){
            echo json_encode([
                'act'=> 'false',
                'message' => 'user not connected'
            ]);
            return false;
        }else{
            $idUser = $_SESSION['login']['id'];
            $idMovies = $_POST['moviesId'];
            if(!$idUser && !$idMovies){
                echo json_encode([
                    'act'=> 'false',
                    'message' => 'error data'
                ]);
                return false;
            }

            
            $checkMoviesExi = pg_query_params($conn , 'SELECT id FROM movies WHERE user_id = $1 AND movies_id = $2 ' , [$idUser , $idMovies]);
            if(pg_affected_rows($checkMoviesExi) > 0 || !$checkMoviesExi){
                echo json_encode([
                    'act'=> 'fasle',
                    'message'=> 'movies is Exists'
                ]);
                return false;
            }else{
                $addMovies = pg_query_params($conn , "INSERT INTO movies( user_id , movies_id ) VALUES ( $1 , $2 )" , [intval($idUser) , $idMovies]);
                    if(pg_affected_rows($addMovies)){
                        echo json_encode([
                            'act'=> 'true',
                            'message' => 'movies is add'
                        ]);
                        return true;
                    }else{
                        echo json_encode([
                            'act'=> 'false',
                            'message' => 'movies not add'
                        ]);
                        return false;
                    }
            }

        }
    }

    public function getUser(){
        global $conn;
        if(!$_SESSION['login'] && !$_SESSION['login']['id']){
            echo json_encode([
                'act'=>'false',
                'message' => 'not connected'
            ]);
            return false;
        }else{
            $userId = $_SESSION['login']['id'];
            $moviesForUser = pg_query_params($conn , 'SELECT * FROM movies WHERE user_id = $1 ' , [$userId]);
            if(pg_affected_rows($moviesForUser) > 0){
                $moviesArr = [];
                while($movies = pg_fetch_assoc($moviesForUser)){
                    array_push($moviesArr , $movies['movies_id']);
                }
                echo json_encode([
                    'act' => 'true',
                    'movies' => $moviesArr
                ]);
                return true;
            }else{
                echo json_encode([
                    'act' => 'fasle',
                    'message' => 'no movies for the user id '.$userId
                ]);
                return false;
            }
        }
    }

    public function getUsers()
    {
        global $conn;

        if (!$_SESSION['login']) {
            echo 'false';
            return false;
        }

        $users = $conn->prepare('SELECT id,username,lvl FROM users');
        $users->execute();
        $userArr = array();
        while ($user = $users->fetchAll()) {
            $userArr += $user;
        };
        echo json_encode($userArr);
        return true;
    }
    public function Index()
    { }
}
