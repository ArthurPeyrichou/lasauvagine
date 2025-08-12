<?php
// inc/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    // For pages: redirect to login
    if (!defined('API')) {
        header('Location: /login.php');
        exit;
    } else {
        http_response_code(401);
        echo json_encode(['error'=>'Unauthorized']);
        exit;
    }
}
