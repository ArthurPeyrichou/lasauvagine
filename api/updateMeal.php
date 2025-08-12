<?php
define('API', true);
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/db.php';
header('Content-Type: application/json');

// expected payload: client_id, date (Y-m-d), count, period_id
$data = json_decode(file_get_contents('php://input'), true);
$client_id = isset($data['client_id']) ? (int)$data['client_id'] : 0;
$date = $data['date'] ?? '';
$count = isset($data['count']) ? (int)$data['count'] : 0;
$period_id = isset($data['period_id']) ? (int)$data['period_id'] : 0;

if (!$client_id || !$date || !$period_id) {
    http_response_code(400);
    echo json_encode(['error'=>'missing']);
    exit;
}

// check period restriction: if period is past and user role != god, deny
$stmt = $pdo->prepare('SELECT month, year FROM periods WHERE period_id = ?');
$stmt->execute([$period_id]);
$period = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$period) { http_response_code(404); echo json_encode(['error'=>'period not found']); exit; }

$now = new DateTime();
$periodEnd = new DateTime($period['year'] . '-' . sprintf('%02d',$period['month']) . '-01');
$periodEnd->modify('last day of this month');

// allow modification only if periodEnd >= now OR role==god
session_start();
$role = $_SESSION['role'] ?? 'user';
if ($periodEnd < $now && $role !== 'god') {
    http_response_code(403);
    echo json_encode(['error'=>'period locked']);
    exit;
}

// upsert meal (no history)
$stmt = $pdo->prepare('SELECT meals_id FROM meals WHERE client_id = ? AND date = ?');
$stmt->execute([$client_id, $date]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);
if ($existing) {
    $stmt = $pdo->prepare('UPDATE meals SET count = ? WHERE meals_id = ?');
    $stmt->execute([$count, $existing['meals_id']]);
    echo json_encode(['success'=>true,'updated'=>true]);
    exit;
} else {
    $stmt = $pdo->prepare('INSERT INTO meals (client_id, period_id, date, count) VALUES (?,?,?,?)');
    $stmt->execute([$client_id, $period_id, $date, $count]);
    echo json_encode(['success'=>true,'inserted'=>true]);
    exit;
}
