<?php
declare(strict_types= 1);
session_start(['cookie_lifetime'=>86400,'read_and_close'=>true]);
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../app/helpers.php';
require_once __DIR__.'/../app/Controllers/ProductApiController.php';
set_exception_handler(function(Throwable $e): void{
    error_log($e->getMessage());
    fail('មានបញ្ហាក្នុងserver។',500);
});
$path= is_string($_GET['path']??null)
? trim($_GET['path']) : ltrim($_SERVER['PATH_INFO'] ?? '','/');
$parts=array_values(array_filter(explode('/',$path),fn($p)=>$p!==''));
if(($parts[0]??'')!=='products'|| count($parts)>2){
    fail('រកមិនឃើញទំព័រទេ។',404);
}
$id =null;
if(isset($parts[1])){
   if(!ctype_digit($parts[1]) || (int)$parts[1]<1){
        fail('id មិនត្រឹមត្រូវទេ។',400);
   }
   $id=(int)$parts[1];
}
(new ProductApiController($pdo))->handle($_SERVER['REQUEST_METHOD'],$id);
