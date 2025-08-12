<?php
require_once __DIR__.'/../inc/auth.php';
require_once __DIR__.'/../inc/db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? null;
$stmt = $pdo->prepare("SELECT user_id, nickname, email, role, phone, birthdate, creation_date FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Profile - Resto</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-white">
  <div class="container py-4">
    <h3>Mon profil</h3>
    <form id="profileForm">
      <div class="mb-3">
        <label class="form-label">Pseudo</label>
        <input class="form-control" name="nickname" value="<?php echo htmlspecialchars($user['nickname'] ?? ''); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input class="form-control" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
      </div>
      <div class="mb-3">
        <label class="form-label">Téléphone</label>
        <input class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Changer le mot de passe</label>
        <input type="password" class="form-control" name="new_password" placeholder="Laisser vide pour ne pas changer">
      </div>
      <button class="btn btn-primary">Enregistrer</button>
    </form>
    <div id="msg" class="mt-3"></div>
  </div>

<script>
document.getElementById('profileForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const fd = new FormData(e.target);
  const res = await fetch('/api/updateClient.php', { method: 'POST', body: fd });
  const data = await res.json();
  document.getElementById('msg').innerText = data.success ? 'Enregistré' : (data.error || 'Erreur');
});
</script>
</body>
</html>
