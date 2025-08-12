<?php
require_once __DIR__.'/../inc/auth.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Clients - Resto</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-white">
  <div class="container py-4">
    <h3>Clients</h3>
    <button id="newClient" class="btn btn-success mb-3">Nouveau client</button>
    <div id="clientsList"></div>
  </div>

<script src="../assets/js/clients.js"></script>
</body>
</html>
