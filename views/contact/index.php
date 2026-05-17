<?php 
$flash = getFlashMessage(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Liste des contacts</h1>
        <div>
            <span class="me-3">Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?> (<?= $_SESSION['user_role'] ?>)</span>
            <a href="index.php?action=profile" class="btn btn-outline-primary btn-sm">Mon profil</a>
            <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        </div>
    </div>

    <p class="text-muted">Nombre total de contacts : <?= $totalContacts ?></p>
    
    <?php if ($isAdmin): ?>
        <a href="index.php?action=dashboard" class="btn btn-info btn-sm mb-3">📊 Dashboard admin</a>
    <?php endif; ?>

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
            <th>Photo</th>
            <th>ID</th><th>Nom</th><th>Prénom</th><th>Téléphone</th><th>Email</th><th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($contacts as $contact): ?>
        <tr>
            <td>
                <?php if ($contact['photo']): ?>
                    <img src="<?= htmlspecialchars($contact['photo']) ?>" alt="photo" style="width: 50px; height: 50px; object-fit: cover;" class="rounded-circle">
                <?php else: ?>
                    <span class="text-muted">📷</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($contact['id']) ?></td>
            <td><?= htmlspecialchars($contact['nom']) ?></td>
            <td><?= htmlspecialchars($contact['prenom']) ?></td>
            <td><?= htmlspecialchars($contact['telephone']) ?></td>
            <td><?= htmlspecialchars($contact['email']) ?></td>
            <td>
                <a href="index.php?action=edit&id=<?= $contact['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                <?php if ($isAdmin): ?>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $contact['id'] ?>" data-nom="<?= htmlspecialchars($contact['nom']) ?> <?= htmlspecialchars($contact['prenom']) ?>">
                        Supprimer
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

<!-- Modal de confirmation suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer le contact <strong id="contactNom"></strong> ?
                <br><span class="text-danger">Cette action est irréversible.</span>
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
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const contactId = button.getAttribute('data-id');
        const contactNom = button.getAttribute('data-nom');
        document.getElementById('contactNom').textContent = contactNom;
        document.getElementById('confirmDeleteBtn').href = 'index.php?action=delete&id=' + contactId;
    });
</script>
</body>
</html>