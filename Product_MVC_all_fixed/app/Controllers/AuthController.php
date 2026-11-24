<?php
require_once __DIR__ . '/../Model/User.php';
class AuthController {
    private User $userModel;
    public function __construct(PDO $pdo) {
        $this->userModel = new User($pdo);
    }
    public function login(): void{
       $error='';
       if($_SERVER['REQUEST_METHOD'] ==='POST'){
        if(!csrf_valid()){
            http_response_code(400);
            exit('Invalid request.');
        }
        $username = trim($_POST['username']??'');
        $password =$_POST['password']??'';
        $user=$this->userModel->findByUsername($username);
        if($user && password_verify($password, $user['password'])){
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php?action=dashboard');
            exit;
        }
        $error = 'Username ឬ Password មិនត្រឹមត្រូវទេ។';
       }
        require __DIR__ . '/../Views/auth/login.php';
    }
    public function logout(): void {
        $_SESSION=[];
        if(ini_get('session.use_cookies')){
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header('Location: index.php?action=login');
    }
}   