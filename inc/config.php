<?php
// inc/config.php - mettre à jour selon votre environnement
define('APP_ENV', 'prod'); // 'dev' ou 'prod'

// DB settings
define('DB_HOST', 'lasauvruser.mysql.db');
define('DB_NAME', 'lasauvruser');
define('DB_USER', 'lasauvruser');
define('DB_PASS', 'Mariex');

// SMTP (mettre vos identifiants en prod)
define('SMTP_HOST', 'ssl0.ovh.net');
define('SMTP_USER', 'noreply@la-sauvagine.fr');
define('SMTP_PASS', 'Arthurx');
define('SMTP_PORT', 456);
define('SMTP_FROM', 'noreply@la-sauvagine.fr');
define('SMTP_FROM_NAME', 'La sauvagine');
