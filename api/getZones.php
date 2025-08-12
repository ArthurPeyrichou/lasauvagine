<?php
define('API', true);
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/db.php';
header('Content-Type: application/json');

$stmt = $pdo->query('SELECT zone_id, zone_name FROM zones ORDER BY zone_name');
$zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($zones);
