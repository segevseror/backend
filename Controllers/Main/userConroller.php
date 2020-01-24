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
                $_SESSION['login'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'level' => $user['lvl']
                ];
                
                echo json_encode([
                    'act' => 'true',
                    'id' => $user['id'],
                    'username' => $user['username']
                ]);
                return true;
            }else{
                echo json_encode([
                    'act' => 'false',
                    'message' => 'details error'
                ]);
                return false;
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
    
        if($_SESSION['login'] && $_SESSION['login']['id']){
            echo json_encode([
                'act' => 'true',
                'username' => $_SESSION['login']['username']
            ]);
            return true;
        }else{
            echo json_encode([
                'act'=> 'fasle',
                'message' => 'user not connected'
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
        if(!$_SESSION['login'] || $_SESSION['login']['id'] != '25'){
            echo json_encode(['act' => false, 'message' => 'this request not have permission']);
            return false;
        }
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

    
    // public function RemoveMovies(){
    //     global $conn;
    //     if(! $_SESSION['login'] || ! $_SESSION['login']['id']){
    //         echo json_encode([
    //             'act'=> 'false',
    //             'err' => '1561',
    //             'message' => 'user not connected'
    //         ]);
    //         die('fasslse');
    //         return false;
    //     }else{
    //         $idMovies = $_GET['id'];
    //         $userId = $_SESSION['login']['id'];
    //         if(!is_numeric($idMovies)){
    //             echo 'false';
    //             return false;
    //         }
           
    //         //'SELECT id FROM movies WHERE user_id = $1 AND movies_id = $2  '
    //         $removeMovies = pg_query_params($conn , 'DELETE FROM movies WHERE user_id = $1 AND movies_id = $2 '  , [$userId , $idMovies ]);

    //         var_dump(pg_affected_rows($removeMovies));
    //         die('s');
    //         if(pg_affected_rows($removeMovies)>0){
    //             die('true');
    //         }
    //         die('false');
    //     }
    // }

    public function AddMovies(){
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
            $img = $_POST['img'];
            $title = $_POST['title'];
            $act = $_POST['act'];
            if(!$idUser && !$idMovies){
                echo json_encode([
                    'act'=> 'false',
                    'message' => 'error data'
                ]);
                return false;
            }
            if($act == 'add'){
                $checkMoviesExi = pg_query_params($conn , 'SELECT id FROM movies WHERE user_id = $1 AND movies_id = $2  ' , [$idUser , $idMovies ]);
                if(pg_affected_rows($checkMoviesExi) > 0 || !$checkMoviesExi){
                    echo json_encode([
                        'act'=> 'fasle',
                        'message'=> 'movies is Exists'
                    ]);
                    return false;
                }else{
                    $addMovies = pg_query_params($conn , "INSERT INTO movies( user_id , movies_id , img , title ) VALUES ( $1 , $2 , $3 , $4 )" , [intval($idUser) , $idMovies , $img , $title]);
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

            }elseif($act == 'remove'){
                $removeMovie = pg_query_params($conn , "DELETE FROM movies WHERE user_id = $1 AND movies_id = $2 " , [$idUser , $idMovies]);
                if(pg_affected_rows($removeMovie) > 0){
                    echo json_encode([
                        'act'=>'true',
                        'message'=> 'movies is removied'
                    ]);
                    return true;
                }else{
                    echo json_encode([
                        'act'=>'false',
                        'message'=> 'movies not found'
                    ]);
                    return false;
                }

            }
          
      

        }
    }

    public function GetUser(){
        global $conn;

        if(!$_SESSION['login'] || !$_SESSION['login']['id']){
            echo json_encode([
                'act'=>'false',
                'message' => 'not connected'
            ]);
            return false;
        }else{
            $userId = $_SESSION['login']['id'];
            $moviesForUser = pg_query_params($conn , 'SELECT * FROM movies WHERE user_id = $1 ' , [$userId]);
            if( $moviesForUser){
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
                    'act' => 'false',
                    'movies' => null,
                    'message' => 'disconnect'
                ]);
                return false;
            }
        }
    }

    public function GetUsers()
    {
        global $conn;
        if (!$_SESSION['login'] || $_SESSION['login']['id'] != '25') {
            echo json_encode([
                'act'=>'false',
                'message' => ( $_SESSION['login']['id'] != '25' ? 'this user no have permission' : 'user not conneced')
            ]);
            return false;
        }
   

        $users = pg_query( $conn , 'SELECT * FROM users');
        $usersArr = array();
        while($user =  pg_fetch_assoc($users)){
            array_push($usersArr , $user);
        }
        echo json_encode($usersArr);
        return true;
    }

    public function GetMovies(){ 
        global $conn;
        if(! $_SESSION['login'] || ! $_SESSION['login']['id']){
            echo json_encode([
                'act'=>'false',
                'message' => 'user not conneced'
            ]);
            return false;
        }
        $moviesArr = [];
        $getMovies = pg_query($conn , 'SELECT users.username , movies.movies_id , movies.img ,movies.title
        FROM public.users
        INNER JOIN movies ON users.id = movies.user_id
        group by users.id , movies.id');      

        while($movies = pg_fetch_assoc($getMovies)){
            $moviesArr[$movies['username']][] = $movies;
        };
      
     
        echo json_encode([
            'act'=>'true',
            'allmovies' => $moviesArr
        ]);
        return true;

    }
}
