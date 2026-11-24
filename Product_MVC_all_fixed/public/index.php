<?php
ini_set("display_errors","0");
error_reporting(E_ALL);
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);
session_start();
require_once __DIR__.'/../config/database.php';
ini_set('display_errors', !empty($debug) ? '1' : '0');
require_once __DIR__.'/../app/helpers.php';
require_once __DIR__.'/../app/Controllers/AuthController.php';
require_once __DIR__.'/../app/Controllers/DashboardController.php';
require_once __DIR__.'/../app/Controllers/ProductController.php';
$authController= new AuthController($pdo);
$dashboardController= new DashboardController($pdo);
$productController= new ProductController($pdo);
$action= $_GET['action'] ?? 'dashboard';
if ($action==='login'){
    $authController->login();
    exit;
}
if ($action==='logout'){
    $authController->logout();
    exit;
} 
if (!isset($_SESSION['user_id'])){
    header('location:index.php?action=login');
    exit;
}
switch($action){
    case 'dashboard':
        $dashboardController->index();
        break;
    case 'products':
        $productController->index();
        break;
    case 'create':
        $productController->create();
        break;
    case 'store':
        $productController->store();
        break;
    case 'edit':
        $productController->edit();
        break;
    case 'update':
        $productController->update();
        break;
    case 'delete':
        $productController->delete();
        break;

    default:
        http_response_code(404);
        echo "404 - Page not found.";
        break;
}