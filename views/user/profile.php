<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
$user = $_SESSION['user']; // passé par le contrôleur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="text-center">Mon profil</h3>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['profile_success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['profile_success']) ?></div>
                <?php unset($_SESSION['profile_success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['profile_error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['profile_error']) ?></div>
                <?php unset($_SESSION['profile_error']); ?>
            <?php endif; ?>
            <form action="index.php?action=updateProfile" method="POST">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <hr>
                <h5>Changer le mot de passe (optionnel)</h5>
                <div class="mb-3">
                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="index.php?action=list" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>