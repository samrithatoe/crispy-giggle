<?php
declare(strict_types= 1);
function respond(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    exit;
}
function fail(string $message, int $status ,array $extra = []): void {
    respond(['error'=>$message] + $extra,$status);
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="'.htmlspecialchars(csrf_token()).'">';
}
function csrf_valid(): bool {
    $sent = $_POST['csrf'] ?? '';
    return is_string($sent) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $sent);
}
function readJson():array{
    if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') === false) {
        fail('Content-Type ត្រូវជា application/json។',415);
    }
    $input=json_decode((string)file_get_contents('php://input'),true);
    if(!is_array($input)){
        fail('Json មិនត្រឹមត្រូវទេ។',400);
    }
    return $input;
}