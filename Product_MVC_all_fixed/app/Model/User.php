<?php
class User {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function findByUsername(string $username): ?array {
       $stmt = $this->pdo->prepare("
       select id,username,password from users where username=?
       ");
       $stmt->execute([$username]);
       $user=$stmt->fetch();
       return $user ?: null;
    }
}