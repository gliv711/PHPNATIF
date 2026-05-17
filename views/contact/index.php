<?php 
$flash = getFlashMessage(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <!-- En-tête avec nom de l'utilisateur et déconnexion -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Liste des contacts</h1>
        <div>
            <span class="me-3">Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?> (<?= $_SESSION['user_role'] ?>)</span>
            <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" class="d-flex">
                <input type="hidden" name="action" value="list">
                <input type="text" name="search" class="form-control me-2" placeholder="Rechercher..." value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit" class="btn btn-info">Rechercher</button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="index.php?action=create" class="btn btn-primary">Ajouter un contact</a>
        </div>
    </div>
    
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th><th>Nom</th><th>Prénom</th><th>Téléphone</th><th>Email</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($contacts as $contact): ?>
        <tr>
            <td><?= htmlspecialchars($contact['id']) ?></td>
            <td><?= htmlspecialchars($contact['nom']) ?></td>
            <td><?= htmlspecialchars($contact['prenom']) ?></td>
            <td><?= htmlspecialchars($contact['telephone']) ?></td>
            <td><?= htmlspecialchars($contact['email']) ?></td>
            <td>
                <a href="index.php?action=edit&id=<?= $contact['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                <?php if ($isAdmin): ?>
                    <a href="index.php?action=delete&id=<?= $contact['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce contact ?')">Supprimer</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?action=list&page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>