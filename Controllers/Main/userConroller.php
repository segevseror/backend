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
            $login = pg_query($conn , "SELECT * FROM users WHERE username = '$userName' AND pass = '$pass'");
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
                    'username' => $user['username'],
                    'level' => $user['lvl']
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
        if(isset($_SESSION['login']) && $_SESSION['login']['id']){
            echo json_encode([
                'act' => 'true',
                'username' => $_SESSION['login']['username'],
                'level' => $_SESSION['login']['lvl']
            ]);
            return true;
        }else{
            echo 'false';
            return false;
        }
    }

    public function RegisterUser()
    {
        global $conn;
        $userName = $_POST['username'];
        $pass = $_POST['password'];
        $phone = $_POST['phone'];
        $isAdmin = $_POST['isAdmin'];
        $errors = [];
        if (!$userName) {
            $errors['usename'] = 'you most give the username';
        }
        if (!$pass) {
            $errors['password'] = 'you most give the password';
        }
        if ($errors) {
            echo json_encode(['act' => false, 'message' => $errors]);
            return false;
        } else {

            //init phone test
            if (!$phone) {
                $phone = '00';
            };

            //check if username is exists;
            $checkUserName = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $checkUserName->execute([':username' => $userName]);
            if (!empty($checkUserName->fetch())) {
                echo json_encode(['act' => false, 'message' => 'the username is exists']);
                return false;
            } else {
                $adduser = $conn->prepare("INSERT INTO users(username , pass , phone , lvl ) VALUES(:username , :pass , :phone , :lvl)");
                $adduser->execute([':username' => $userName, ':pass' => $pass, ':phone' => $phone, ':lvl' => ($isAdmin ? 1 : 0)]);
                if ($adduser->rowCount() > 0) {
                    $getuser = $conn->prepare("SELECT id,username FROM users WHERE username = :username ");
                    $getuser->execute([':username' => $userName]);
                    $getuser = $getuser->fetch();
                    echo json_encode(['act' => true, 'data' => [
                        'id' => $getuser['id'],
                        'username' => $getuser['username'],
                        'level' => $getuser['lvl']
                    ]]);
                    $_SESSION['login'] = [
                        'id' => $getuser['id'],
                        'username' => $getuser['username'],
                        'level' => $getuser['lvl']
                    ];
                    return true;
                } else {
                    echo json_encode(['act' => false]);
                    return false;
                }
            }
            echo 'error 0000';
            return false;
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
