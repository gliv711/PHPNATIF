<?php 
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Ajouter un contact</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form action="index.php?action=store" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom :</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($old['nom'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom :</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone :</label>
            <input type="text" id="telephone" name="telephone" class="form-control" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse :</label>
            <textarea id="adresse" name="adresse" rows="3" class="form-control" required><?= htmlspecialchars($old['adresse'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Photo :</label>
            <input type="file" id="photo" name="photo" class="form-control" accept="image/jpeg,image/png,image/gif">
            <small class="text-muted">Formats acceptés : JPG, PNG, GIF (max 2 Mo)</small>
        </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="index.php?action=list" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>