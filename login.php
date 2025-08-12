<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: back-office/dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Connexion - Resto</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-body">
            <h3 class="card-title mb-4">Se connecter</h3>
            <form id="loginForm">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" required>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-primary">Se connecter</button>
                <a href="#" id="forgotLink">Mot de passe oublié</a>
              </div>
              <div id="loginError" class="text-danger mt-2" style="display:none;"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('loginForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.target;
    const data = {
        email: form.email.value,
        password: form.password.value
    };
    try {
        const res = await axios.post('/api/login.php', data);
        if (res.data && res.data.success) {
            window.location = '/back-office/dashboard.php';
        }
    } catch (err) {
        document.getElementById('loginError').style.display = 'block';
        document.getElementById('loginError').innerText = err.response?.data?.error || 'Erreur';
    }
});
</script>
</body>
</html>
