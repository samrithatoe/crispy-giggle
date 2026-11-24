<?php   
require_once __DIR__ . '/../Model/Product.php';
class DashboardController {
    private Product $productModel;
    public function __construct(PDO $pdo) {
        $this->productModel = new Product($pdo);
    }
    public function index(): void {
        $summary = $this->productModel->getsummary();
        require __DIR__ . '/../Views/dashboard/index.php';
    }
}