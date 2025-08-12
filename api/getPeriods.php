<?php
define('API', true);
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/db.php';
header('Content-Type: application/json');

$stmt = $pdo->query('SELECT period_id, month, year FROM periods ORDER BY year DESC, month DESC');
$periods = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($periods);
