<?php
define('API', true);
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
header('Content-Type: application/json');
echo json_encode(['success'=>true]);
