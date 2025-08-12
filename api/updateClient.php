<?php
define('API', true);
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/db.php';
header('Content-Type: application/json');

// Detect if profile update (user) or client (client_id param)
$fields = $_POST;
if (isset($_SESSION) === false) session_start();
$user_id = $_SESSION['user_id'] ?? null;

// Simple profile update: if user sent nickname or new_password
if (isset($fields['nickname']) || isset($fields['new_password'])) {
    if (!$user_id) { http_response_code(401); echo json_encode(['error'=>'Unauthorized']); exit; }
    $nickname = $fields['nickname'] ?? null;
    $phone = $fields['phone'] ?? null;
    if ($nickname !== null) {
        $stmt = $pdo->prepare('UPDATE users SET nickname = ?, phone = ? WHERE user_id = ?');
        $stmt->execute([$nickname, $phone, $user_id]);
    }
    if (!empty($fields['new_password'])) {
        $hash = password_hash($fields['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE user_id = ?');
        $stmt->execute([$hash, $user_id]);
    }
    echo json_encode(['success'=>true]);
    exit;
}

// client create/update (basic)
$client_id = isset($fields['client_id']) ? (int)$fields['client_id'] : 0;
$firstname = $fields['firstname'] ?? '';
$lastname = $fields['lastname'] ?? '';
$phone = $fields['phone'] ?? '';
$email = $fields['email'] ?? '';
$address = $fields['address'] ?? '';
$city = $fields['city'] ?? '';
$zone_id = isset($fields['zone_id']) ? (int)$fields['zone_id'] : null;
$title = $fields['title'] ?? 'Mr';
$indications = $fields['indications'] ?? '';

if ($client_id) {
    $stmt = $pdo->prepare('UPDATE clients SET firstname=?, lastname=?, phone=?, email=?, address=?, city=?, zone_id=?, title=?, indications=? WHERE client_id = ?');
    $stmt->execute([$firstname,$lastname,$phone,$email,$address,$city,$zone_id,$title,$indications,$client_id]);
    echo json_encode(['success'=>true,'updated'=>true]);
    exit;
} else {
    $stmt = $pdo->prepare('INSERT INTO clients (birthdate, firstname, lastname, title, phone, email, address, city, zone_id, indications) VALUES (NULL,?,?,?,?,?,?,?,?,?)');
    $stmt->execute([$firstname,$lastname,$title,$phone,$email,$address,$city,$zone_id,$indications]);
    echo json_encode(['success'=>true,'inserted'=>true,'client_id'=>$pdo->lastInsertId()]);
    exit;
}
