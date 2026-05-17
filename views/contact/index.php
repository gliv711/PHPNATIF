<?php 
$flash = getFlashMessage(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container mt-5">
    <!-- En-tête avec menu utilisateur -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Liste des contacts</h1>
        <div>
            <span class="me-3">Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?> (<?= $_SESSION['user_role'] ?>)</span>
            <a href="index.php?action=profile" class="btn btn-outline-primary btn-sm"><i class="fas fa-user"></i> Mon profil</a>
            <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        </div>
    </div>

    <!-- Affichage du nombre total de contacts -->
    <p class="text-muted">Nombre total de contacts : <?= $totalContacts ?></p>

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
                <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Rechercher</button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="index.php?action=create" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter un contact</a>
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
                <a href="index.php?action=edit&id=<?= $contact['id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Modifier</a>
                <?php if ($isAdmin): ?>
                    <!-- Bouton qui ouvre le modal -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $contact['id'] ?>">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
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

<!-- Modal de confirmation de suppression (Bootstrap) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer ce contact ? Cette action est irréversible.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Récupérer l'ID du contact quand on clique sur le bouton supprimer
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var confirmLink = deleteModal.querySelector('#confirmDeleteBtn');
        confirmLink.href = 'index.php?action=delete&id=' + id;
    });
</script>
</body>
</html>