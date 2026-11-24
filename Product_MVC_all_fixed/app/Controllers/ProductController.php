<?php
require_once __DIR__.'/../Model/Product.php';
class ProductController{
    private Product $productModel;
    public function __construct(PDO $pdo){
        $this->productModel=new Product($pdo);
    }
   public function index(): void {
        $products = $this->productModel->getall();
        require __DIR__ . '/../Views/Products/index.php';
    }
    public function create(): void {
        $error = '';
        $data=[
            'product_code' => '',
            'product_name' => '',
            'category' => 'General',
            'price' => '',
            'quantity' => ''
        ];
        require __DIR__ . '/../Views/Products/create.php';
    }
    public function store():void{
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
           header('location:index.php?action=products');
           exit;
        }
        $this->checkCsrf();
        $data=  $this->formdata();
        $error= $this->validate($data);
        if($error!==''){
            require __DIR__ . '/../Views/Products/create.php';
            return;
        }
        try{
            $this->productModel->create($data);
            header('location:index.php?action=products&success=created');
            exit;
        } catch (Exception $e) {
            if($e->getCode() === '23000'){
                $error = 'Product Code មានរួចហើយ។';
            } else {
                error_log($e->getMessage());
                $error = 'មិនអាចរក្សាទុកផលិតផលបានទេ។ សូមព្យាយាមម្តងទៀត។';
            }
            require __DIR__ . '/../Views/Products/create.php';
        }

    }
    public function edit(): void {
        $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
        if(!$id||$id<1){
            http_response_code(404);
            exit('product not found.');
        }
        $product=$this->productModel->getbyid($id);
        if(!$product){
            http_response_code(404);
            exit('product not found.');
        } 
        $data=$product;
        $error='';
        require __DIR__ . '/../Views/Products/edit.php';
    }
    public function update(): void {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('location:index.php?action=products');
            exit;
        }
        $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
        if(!$id||$id<1){
            http_response_code(404);
            exit('Invalid product ID.');
        }
        $this->checkCsrf();
        if(!$this->productModel->getbyid($id)){
            http_response_code(404);
            exit('product not found.');
        }
        $data=$this->formdata();
        $product=['id'=>$id];
        $error=$this->validate($data);
        if($error!==''){
            require __DIR__ . '/../Views/Products/edit.php';
            return;
        }
        try{
            $this->productModel->update($id,$data);
            header('location:index.php?action=products&success=updated');
            exit;
        } catch (Exception $e) {
            if($e->getCode() === '23000'){
                $error = 'Product Code មានរួចហើយ។';
            } else {
                error_log($e->getMessage());
                $error = 'មិនអាចកែប្រែផលិតផលបានទេ។ សូមព្យាយាមម្តងទៀត។';
            }
            require __DIR__ . '/../Views/Products/edit.php';
        }
    }
    public function delete(): void {
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('location:index.php?action=products');
            exit;
        }
        $this->checkCsrf();
        $id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
        if(!$id||$id<1){
           header('location:index.php?action=products');
           exit;  
        }
        try{
            $this->productModel->delete($id);
            header('location:index.php?action=products&success=deleted');
            exit;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            header('location:index.php?action=products&error=delete_failed');
            exit;
        }
    }
    private function post(string $key): string {
        $v = $_POST[$key] ?? '';
        return is_string($v) ? trim($v) : '';
    }
    private function checkCsrf(): void {
        if(!csrf_valid()){
            http_response_code(400);
            exit('Invalid request.');
        }
    }
    private function formdata(): array {
        return [
            'product_code' => $this->post('product_code'),
            'product_name' => $this->post('product_name'),
            'category' => $this->post('category'),
            'price' => $this->post('price'),
            'quantity' => $this->post('quantity')
        ];
    }
    private function validate(array $data): string {
        if(
            $data['product_code']===''||
            $data['product_name']===''||
            $data['category']===''){
            return 'សូមបញ្ចូលពត៌មានទាំងអស់។';
        }
        if(
            mb_strlen($data['product_code'])>50||
            mb_strlen($data['product_name'])>150||
            mb_strlen($data['category'])>100){ 
            return 'ព័ត៌មានដែលបញ្ចូលវែងពេក។';
        }
        if(
            !is_numeric($data['price'])||
            (float)$data['price']<0||
            (float)$data['price']>999999.99){
            return 'តម្លៃមិនត្រឹមត្រូវ។';
        }
        if(filter_var($data['quantity'], FILTER_VALIDATE_INT,
            ['options'=>['min_range'=>0,'max_range'=>4294967295]])===false){
            return 'ចំនួនស្តុកមិនត្រឹមត្រូវ។';
        }
        return '';    
    }
}      