<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h3 class="text-center">Inscription</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['register_error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['register_error']) ?></div>
                <?php unset($_SESSION['register_error']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['register_success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['register_success']) ?></div>
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>
            <form action="index.php?action=doRegister" method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom complet</label>
                    <input type="text" name="nom" id="nom" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">S'inscrire</button>
            </form>
            <div class="mt-3 text-center">
                <a href="index.php?action=login">Déjà un compte ? Connectez-vous</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>