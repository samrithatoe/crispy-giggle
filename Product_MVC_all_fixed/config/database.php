<?php
$host='localhost';
$dbname='product_mvc';
$username='root';
$password='';
// $debug=true shows the real connection error. Set to false once it works.
$debug=true;
try{
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
}catch(PDOException $e){
    error_log($e->getMessage());
    http_response_code(500);
    die($debug ? "Database connection failed: ".htmlspecialchars($e->getMessage()) : "Database connection failed.");
}
