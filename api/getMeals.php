<?php
define('API', true);
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/db.php';
header('Content-Type: application/json');

$zoneId = isset($_GET['zoneId']) ? (int)$_GET['zoneId'] : 0;
$periodId = isset($_GET['periodId']) ? (int)$_GET['periodId'] : 0;
if (!$zoneId || !$periodId) { echo json_encode(['error'=>'missing']); exit; }

// get clients
$stmt = $pdo->prepare('SELECT client_id, firstname, lastname FROM clients WHERE zone_id = ? ORDER BY lastname, firstname');
$stmt->execute([$zoneId]);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get period dates
$stmt = $pdo->prepare('SELECT month, year FROM periods WHERE period_id = ?');
$stmt->execute([$periodId]);
$period = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$period) { echo json_encode(['error'=>'period not found']); exit; }

$month = (int)$period['month'];
$year = (int)$period['year'];

// build days array for the month
$days = [];
$start = new DateTime("$year-$month-01");
$end = new DateTime($start->format('Y-m-t'));
for ($d = clone $start; $d <= $end; $d->modify('+1 day')) {
    $days[] = $d->format('Y-m-d');
}

// get meals for clients in that period
$in = implode(',', array_map('intval', array_column($clients,'client_id')));
$meals = [];
if ($in) {
    $stmt = $pdo->query("SELECT client_id, date, count FROM meals WHERE client_id IN ($in) AND date BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $meals[$r['client_id']][$r['date']] = (int)$r['count'];
    }
}

echo json_encode(['clients'=>$clients,'days'=>$days,'meals'=>$meals]);
