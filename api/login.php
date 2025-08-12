<?php
define('API', true);
require_once __DIR__.'/../inc/db.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND status = "activated"');
try {
    $stmt->execute([$email]);
} catch (Exception $e) {
    echo json_encode(['error'=>'DB error']);
    exit;
}
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user && password_verify($password, $user['password'])) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    echo json_encode(['success'=>true]);
    exit;
}
http_response_code(401);
echo json_encode(['error'=>'Email ou mot de passe invalide']);
