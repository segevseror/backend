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
                'act' => 'false',
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
        $email = $_POST['email'];
        $passAdmin = $_POST['passAdmin'];
        $errors = [];
        if($passAdmin != '8889'){
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
            $checkUserName = pg_query_params($conn , 'SELECT id FROM users WHERE username = $1 OR mail = $2 ' , [$userName , $email]);
            $checkUserName = pg_fetch_assoc($checkUserName);

            if ($checkUserName > 0) {
                echo json_encode(['act' => 'false', 'message' => 'the username is exists']);
                return false;
            } else {
                $adduser = pg_query_params($conn , 'INSERT INTO users (username,pass,mail,cdate)
                VALUES ($1, $2 , $3 ,$4)' , [$userName , $pass , $email , date("d/m/Y")]);
                $adduserRow = pg_affected_rows($adduser);
            
                if ($adduserRow > 0) {
                    $getuser = pg_query_params($conn , 'SELECT id,username FROM users WHERE mail = $1' , [$email]);
                    $getuser = pg_fetch_assoc( $getuser );
                    echo json_encode(['act' => 'true', 'data' => [
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

    public function AddMovies(){
        global $conn;
        if(! $_SESSION['login'] || ! $_SESSION['login']['id']){
            echo json_encode([
                'act'=> 'false',
                'err' => '300',
                'message' => 'user not connected'
            ]);
            return false;
        }else{
            $idUser = $_SESSION['login']['id'];
            $idMovies = $_POST['movies_id'];
            $img = $_POST['img'];
            $title = $_POST['title'];
            $origin_date = $_POST['origin_date'];
            $act = $_POST['act'];
            if(!$idUser && !$idMovies){
                echo json_encode([
                    'act'=> 'false',
                    'message' => 'error data'
                ]);
                return false;
            }
            if($act == 'add'){
                $checkIfUserRefer = pg_query_params($conn , 'SELECT * FROM refer WHERE  user_id = $1 AND movies_id = $2  ' , [ $idUser , $idMovies ]);
                if(pg_affected_rows($checkIfUserRefer) > 0  ){
                    echo json_encode([
                        'act'=> 'false',
                        'err'=> '302' ,
                        'message'=> 'you refer  this movies'
                    ]);
                    return false;
                }
                pg_query_params($conn , "INSERT INTO refer( user_id , movies_id , cdate) VALUES ( $1 , $2 , $3 )" , [ $idUser , $idMovies , date("d/m/Y")]);
                $checkMoviesExi = pg_query_params($conn , 'SELECT * FROM movies WHERE  movies_id = $1  ' , [ $idMovies ]);
                if(pg_affected_rows($checkMoviesExi) > 0 ){
                    pg_query_params($conn , "UPDATE movies
                    SET udate = $1
                    WHERE movies_id = $2" , [date("d/m/Y") , $idMovies ] );
                    echo json_encode([
                        'act'=> 'true',
                        'err' => '303',
                        'message'=> 'movies is Exists'
                    ]);
                    return false;
                }else{
                    $addMovies = pg_query_params($conn , "INSERT INTO movies( movies_id , img , title , cdate , origin_date  ) VALUES ( $1 , $2 , $3 , $4 , $5 )" , [ $idMovies , $img , $title ,  date('d/m/Y') , date("d/m/Y", strtotime( $origin_date)) ]);
                    if(pg_affected_rows($addMovies) > 0){
                            echo json_encode([
                                'act'=> 'true',
                                'message' => 'movies is add'
                            ]);
                        }else{
                            echo json_encode([
                                'act'=> 'false',
                                'err' => '404',
                                'message' => 'worng'
                            ]);
                            return;
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
            $moviesForUser = pg_query_params($conn , 'SELECT m.img , m.title , m.cdate , u.username  from movies AS M
            INNER JOIN refer AS r
            ON r.movies_id = m.movies_id
            INNER JOIN users AS u
            ON CAST( u.id AS varchar(25)) =  r.user_id
            WHERE u.id = $1' , [$userId]);
            $movies = [];
            while($m = pg_fetch_assoc($moviesForUser)){
                $movies[] = $m;
                $username = $m['username'];
            }
            $user = [
                'username'=> $username,
                'movies' => $movies
            ];
            if( $moviesForUser){
                echo json_encode([
                    'act' => 'true',
                    'user' =>  $user
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
                'err'=> '300',
                'message' => 'user not conneced'
            ]);
            return false;
        }
        $moviesArr = [];
        $getMovies = pg_query_params($conn , "
        SELECT m.movies_id ,u.username , m.cdate , m.title , m.img , m.origin_date
        FROM
            movies AS m
        left JOIN 
        refer AS r ON m.movies_id = r.movies_id
        left JOIN 
        users AS u ON CAST(r.user_id AS INT) = u.id
        ORDER BY m.movies_id" , [] );      

        while($movies = pg_fetch_assoc($getMovies)){
            if(!array_key_exists($movies['movies_id'] , $moviesArr)){
                $moviesArr[$movies['movies_id']] = [
                    'img' => $movies['img'],
                    'title' => $movies['title'],
                    'cdate' => $movies['cdate'],
                    'sharing' => [$movies['username']],
                    'origin_date' => $movies['origin_date']
                ];
            }else{
                array_push($moviesArr[$movies['movies_id']]['sharing'] , $movies['username']); 
            }
        };
        echo json_encode([
            'act'=>'true',
            'allmovies' => $moviesArr
        ]);
        return true;

    }
}
