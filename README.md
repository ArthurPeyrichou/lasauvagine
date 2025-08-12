# Resto Dashboard - Starter (PHP + MySQL + Bootstrap)

**Contenu**: projet PHP compatible OVH mutualisé (Apache + PHP + MySQL) avec frontend Bootstrap et JavaScript pour un tableau "Excel-like".
Inclus: endpoints PHP "API-like", pages protégées sous `back-office/`, scripts SQL, et un environment npm pour dev local (php server + BrowserSync).

## Prérequis
- PHP 8+ (local)
- MySQL / MariaDB
- Composer (optionnel, pour PHPMailer)
- Node.js + npm (pour dev local seulement)

## Installation locale (rapide)
1. Importer la base:
   - Créer la base MySQL `resto_db`
   - Importer `sql/schema.sql` puis `sql/seed.sql` (ex: via phpMyAdmin ou `mysql` CLI)

2. Configurer `inc/config.php`:
   - Mettre les identifiants MySQL (DB_HOST, DB_NAME, DB_USER, DB_PASS)
   - Configurer SMTP si vous souhaitez tester l'envoi d'e-mails.

3. Installer les dépendances dev (facultatif):
```bash
npm install
composer install
```

4. Lancer en local:
```bash
npm run dev
```
- Le script lance un serveur PHP embarqué (`php -S localhost:8000`) et BrowserSync sur `http://localhost:3000`.
- Ou accéder directement à `http://localhost:8000` si vous n'utilisez pas BrowserSync.

## Déploiement sur OVH (mutualisé)
- Copier tous les fichiers sur le site OVH (FTP).
- Importer les SQL sur la base MySQL OVH.
- Mettre à jour `inc/config.php` avec les credentials OVH.
- S'assurer d'activer HTTPS (Let's Encrypt) sur l'espace OVH.

## Points importants & sécurité
- Les mots de passe sont stockés hashés (PHP `password_hash`).
- Les routes sous `api/` vérifient la session via `inc/auth.php`.
- Ne stockez pas d'identifiants sensibles dans le repo (utilisez `inc/config.php` qui est déjà paramétré).

## Structure
Voir l'arborescence du projet. Les pages protégées sont dans `back-office/`.

---

Bonne utilisation ! Si tu veux, je peux te guider pour l'import SQL et la mise en ligne sur OVH.
