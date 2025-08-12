<?php
define('API', true);
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/db.php';
header('Content-Type: application/json');

$zoneId = isset($_GET['zoneId']) ? (int)$_GET['zoneId'] : 0;
if (!$zoneId) { echo json_encode([]); exit; }

$stmt = $pdo->prepare('SELECT client_id, firstname, lastname, title, address, city FROM clients WHERE zone_id = ? ORDER BY lastname, firstname');
$stmt->execute([$zoneId]);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($clients);
