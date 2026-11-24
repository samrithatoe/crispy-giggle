<?php
class Product {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function getall(): array {
        $stmt = $this->pdo->query('Select * from products order by id desc');
        return $stmt->fetchAll();
    }
    public function getbyid(int $id): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        return $product ?: null;
    }
    public function create(array $data): bool {
        $stmt = $this->pdo->prepare('
        INSERT INTO products 
        (product_code,product_name, category,price,quantity)
        VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([
            $data['product_code'],
            $data['product_name'],
            $data['category'],
            $data['price'],
            $data['quantity']
        ]);
    }
    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare('
        UPDATE products SET 
        product_code = ?, 
        product_name = ?, 
        category = ?, 
        price = ?, 
        quantity = ? 
        WHERE id = ?');
        return $stmt->execute([
            $data['product_code'],
            $data['product_name'],
            $data['category'],
            $data['price'],
            $data['quantity'],
            $id
        ]);
    }
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$id]);
    }
    public function getsummary(): array {
        $stmt = $this->pdo->query('
        SELECT 
            COUNT(*) AS total_products, 
            COALESCE(SUM(quantity), 0) AS total_stock
        FROM products');
        return $stmt->fetch();
    }
    public function search(string $q,int $limit, int $offset): array {
        [$where,$params]=$this->searchClause($q);
        $stmt = $this->pdo->prepare("
        SELECT * FROM products
        $where order by id desc limit :limit offset :offset");
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    public function count(string $q=''): int {
        [$where,$params]=$this->searchClause($q);
        $stmt = $this->pdo->prepare("
        SELECT COUNT(*) AS total FROM products
        $where");
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }
    public function searchClause(string $q): array {
        if($q===''){
            return['',[]];
        }
        $like='%'.addcslashes($q,'%_\\').'%';
        return ['WHERE product_code LIKE :q1 OR product_name LIKE :q2 OR category LIKE :q3', 
        [':q1' => $like, ':q2' =>$like, ':q3' => $like],
        ];
    }
}
