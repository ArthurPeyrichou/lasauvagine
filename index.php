<?php
// index.php - simple vitrine
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Resto - Plateaux repas livrés</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#">Resto <span class="text-muted">Plateaux</span></a>
      <div>
        <a href="/login.php" class="btn btn-outline-primary">Se connecter</a>
      </div>
    </div>
  </nav>
  <main class="container py-5">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h1>Plateaux repas livrés à domicile</h1>
        <p class="lead">Service simple et fiable de livraison de plateaux repas pour les collectivités et particuliers.</p>
        <ul>
          <li>Livraison quotidienne</li>
          <li>Menus équilibrés</li>
          <li>Service personnalisé</li>
        </ul>
      </div>
      <div class="col-md-6 text-center">
        <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center" style="width:160px;height:160px;">
          <!-- placeholder logo -->
          <span class="text-white">Logo</span>
        </div>
      </div>
    </div>
  </main>
  <footer class="text-center py-4 text-muted">Mention légale: défaut - à compléter</footer>
</body>
</html>
