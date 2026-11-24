<?php
declare(strict_types=1);
require_once __DIR__ .'/../Model/Product.php';
class ProductApiController {
    private const FIELDS=['product_code','product_name','category','price','quantity'];
    private PDO $pdo;
    private Product $products;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->products = new Product($this->pdo);
    }
    public function handle(string $method,?int $id):void{
        if($id===null){
            match($method){
                'GET'=> $this->index(),
                'POST'=> $this->store(),
                default=> $this->methodNotAllowed('GET, POST'),
            };
            return;
        }
        match($method){
            'GET'=> $this->show($id),
            'PATCH'=> $this->update($id),
            'DELETE'=> $this->destroy($id),
            default=> $this->methodNotAllowed('GET, PATCH, DELETE'),
        };
    }
    private function index(): void {
        $q =is_string ($_GET['q'] ?? null) ? trim($_GET['q']) : '';
        $page=max(1,(int)($_GET['page'] ?? 1));
        $limit=min(100,max(1,(int)($_GET['limit'] ?? 10)));
        $total=$this->products->count($q);
        $rows =$this->products->search($q,$limit,($page-1)*$limit);
        respond([
            'data'=>array_map([$this,'format'], $rows),
            'meta'=>[
                'page'=>$page,
                'limit'=>$limit,
                'total'=>$total,
                'pages'=>(int)ceil($total/$limit),
            ],
        ]);
    }
    private function show(int $id): void {
        respond($this->format($this->findOr404($id)));
    }
    private function store(): void {
        $this->requireLogin();
        $data=$this->clean(readJson());
        $errors=$this->validate($data);
        if($errors) {
            fail('ទិន្នន័យមិនត្រឹមត្រូវទេ។',422,['errors'=>$errors]);
        }
        try{
            $this->products->create($data);
        }catch(PDOException $e){
            $this->dbError($e);
        }
        $id=(int)$this->pdo->lastInsertId();
        header('Location:'.$_SERVER['SCRIPT_NAME'].'?path=products/'.$id);
        respond($this->format($this->findOr404($id)),201);
    }
    private function update(int $id): void {
        $this->requireLogin();
        $current=$this->findOr404($id);
        $input=readJson();
        if(!array_intersect_key($input,array_flip(self::FIELDS))){
            fail('មិនមានទិន្នន័យសម្រាប់កែប្រែ។',422);
        }
        $data =$this ->clean($input,$current);
        $errors=$this->validate($data);
        if($errors) {
            fail('ទិន្នន័យមិនត្រឹមត្រូវទេ។',422,['errors'=>$errors]);
        }
        try{
            $this->products->update($id,$data);
        }catch(PDOException $e){
            $this->dbError($e);
        }
        respond($this->format($this->findOr404($id)));
    }
    private function destroy(int $id): void {
        $this->requireLogin();
        $this->findOr404($id);
        try{
            $this->products->delete($id);
        }catch(PDOException $e){
            $this->dbError($e);
        }
        respond(['message'=>'លុបផលិតផលដោយជោគជ័យ។']);
    }
    private function requireLogin(): void {
        if(!isset($_SESSION['user_id'])){
            fail('សូម login ជាមុន',401);
        }
    } 
    private function findOr404(int $id): array {
        $row=$this->products->getById($id);
        if($row===null){
            fail('រកមិនឃើញផលិតផលទេ។',404);
        }return $row;
    }
    private function methodNotAllowed(string $allowed): void {
        header('Allow: '.$allowed);
        fail('method មិនត្រូវបានអនុញ្ញាតទេ។',405);
    }   
    private function dbError(PDOException $e): void {
        if($e->getCode()==='23000'){
            fail('Product Code មានរួចហើយ។',409);
        }
        error_log($e->getMessage());
        fail('មានបញ្ហាផ្នែក server។',500);
    }
    private function clean(array $input,array $base=[]): array {
        $data=[];
        foreach(self::FIELDS as $field){
            $value=array_key_exists($field,$input) ? $input[$field]
             : ($base[$field] ?? '');
            $data[$field]=is_scalar($value) ?trim((string)$value) : '';
        }
        return $data;
    }
    private function validate(array $d): array {
        $e=[];
        foreach([['product_code',50],['product_name',150],['category',100]]
        as [$field,$max]){
            if($d[$field]===''){
                $e[$field]='បញ្ចូលព័ត៌មាននេះ។';
            }elseif(mb_strlen($d[$field])>$max){
                $e[$field]='វែងពេក(អតិបរមា '.$max.' តួអក្សរ)។';
            }
        }
        if(!is_numeric($d['price']) || (float)$d['price']<0 ||
         (float)$d['price']>999999.99){
            $e['price']='តម្លៃត្រូវជាលេខពី០​ ដល់ ៩៩៩៩៩៩.៩៩។';
        }
        $qty=filter_var($d['quantity'],FILTER_VALIDATE_INT,
        ['options'=>['min_range'=>0,'max_range'=>4294967295]]);
        if($qty === false){
            $e['quantity']='ចំនួនត្រូវជាលេខពី 0 ។';
        }
        return $e;
    }
    private function format(array $row ): array {
        return[
            'id'=>(int)$row['id'],
            'product_code'=>$row['product_code'],
            'product_name'=> $row['product_name'],
            'category'=>$row['category'],
            'price'=>(float)$row['price'],
            'quantity'=>(int)$row['quantity'],
            'created_at'=>$row['created_at']?? null,
        ];
    }
    }