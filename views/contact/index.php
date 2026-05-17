<?php 
$flash = getFlashMessage(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Carnet - Liste des contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --danger: #f72585;
            --success: #4cc9f0;
            --dark: #1a1a2e;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        .navbar-custom {
            background: linear-gradient(135deg, var(--dark) 0%, #16213e 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-custom .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }
        
        .navbar-custom .navbar-brand i {
            margin-right: 10px;
            color: var(--primary);
        }
        
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
            margin: 0 5px;
        }
        
        .navbar-custom .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .main-container {
            padding: 2rem 0;
        }
        
        .card-modern {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .table-modern {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .table-modern thead {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .table-modern thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        
        .table-modern tbody tr {
            transition: background 0.3s ease;
        }
        
        .table-modern tbody tr:hover {
            background: rgba(67, 97, 238, 0.05);
        }
        
        .table-modern tbody td {
            padding: 1rem;
            vertical-align: middle;
        }
        
        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .avatar-placeholder {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .btn-outline-gradient {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 25px;
            padding: 0.3rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-color: transparent;
            color: white;
        }
        
        .badge-role {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-admin {
            background: linear-gradient(135deg, var(--danger), #c9184a);
            color: white;
        }
        
        .badge-user {
            background: linear-gradient(135deg, var(--success), #0096c7);
            color: white;
        }
        
        .alert-modern {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .pagination-modern .page-item .page-link {
            border-radius: 10px;
            margin: 0 3px;
            color: var(--primary);
            border: none;
            transition: all 0.3s ease;
        }
        
        .pagination-modern .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }
        
        .pagination-modern .page-item .page-link:hover {
            transform: translateY(-2px);
            background: var(--primary);
            color: white;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .footer {
            background: var(--dark);
            color: white;
            padding: 1.5rem 0;
            margin-top: 3rem;
            text-align: center;
        }
        
        .search-input {
            border-radius: 25px 0 0 25px;
            border: 2px solid #e0e0e0;
            border-right: none;
            padding: 0.75rem 1rem;
        }
        
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: none;
        }
        
        .search-btn {
            border-radius: 0 25px 25px 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
        }
        
        .search-btn:hover {
            background: linear-gradient(135deg, var(--secondary), var(--primary));
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-custom navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=list">
                <i class="fas fa-address-book"></i>
                Mon Carnet
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=list">
                            <i class="fas fa-users"></i> Contacts
                        </a>
                    </li>
                    <?php if ($isAdmin): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=dashboard">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=profile">
                            <i class="fas fa-user-circle"></i> <?= htmlspecialchars($_SESSION['user_nom']) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=logout">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <div class="container">
            <div class="animate-fadeInUp">
                <!-- En-tête -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h1 class="display-5 fw-bold">
                            <i class="fas fa-address-book" style="color: #ffffff;"></i> Mes contacts
                        </h1>
                        <p class="text-white-50">
                            <i class="fas fa-users"></i> <?= $totalContacts ?> contact(s) au total
                        </p>
                    </div>
                    <div>
                        <span class="badge-role <?= $_SESSION['user_role'] === 'admin' ? 'badge-admin' : 'badge-user' ?> me-2">
                            <?= ucfirst($_SESSION['user_role']) ?>
                        </span>
                        <a href="index.php?action=create" class="btn btn-gradient">
                            <i class="fas fa-plus-circle"></i> Nouveau contact
                        </a>
                    </div>
                </div>

                <!-- Messages flash -->
                <?php if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?> alert-modern alert-dismissible fade show" role="alert">
                        <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($flash['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Barre de recherche -->
                <div class="card-modern mb-4">
                    <div class="p-4">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="action" value="list">
                            <div class="col-md-10">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0" style="border-radius: 25px 0 0 25px;">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control search-input" 
                                           placeholder="Rechercher par nom, prénom, téléphone ou email..."
                                           value="<?= htmlspecialchars($search ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn search-btn w-100">
                                    <i class="fas fa-search"></i> Rechercher
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tableau des contacts -->
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th><i class="fas fa-image"></i> Photo</th>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-user"></i> Nom</th>
                                <th><i class="fas fa-user"></i> Prénom</th>
                                <th><i class="fas fa-phone"></i> Téléphone</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($contacts)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">Aucun contact trouvé</p>
                                        <a href="index.php?action=create" class="btn btn-gradient btn-sm">
                                            <i class="fas fa-plus"></i> Ajouter votre premier contact
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($contacts as $contact): ?>
                                    <tr>
                                        <td>
                                            <?php if ($contact['photo']): ?>
                                                <img src="<?= htmlspecialchars($contact['photo']) ?>" 
                                                     class="avatar-circle" alt="photo">
                                            <?php else: ?>
                                                <div class="avatar-placeholder">
                                                    <?= strtoupper(substr($contact['prenom'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold"><?= $contact['id'] ?></td>
                                        <td><?= htmlspecialchars($contact['nom']) ?></td>
                                        <td><?= htmlspecialchars($contact['prenom']) ?></td>
                                        <td>
                                            <a href="tel:<?= $contact['telephone'] ?>" class="text-decoration-none">
                                                <i class="fas fa-phone-alt text-success"></i> <?= htmlspecialchars($contact['telephone']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="mailto:<?= $contact['email'] ?>" class="text-decoration-none">
                                                <i class="fas fa-envelope text-primary"></i> <?= htmlspecialchars($contact['email']) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="index.php?action=edit&id=<?= $contact['id'] ?>" 
                                               class="btn btn-outline-gradient btn-sm me-1" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($isAdmin): ?>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                                        data-id="<?= $contact['id'] ?>" 
                                                        data-nom="<?= htmlspecialchars($contact['nom'] . ' ' . $contact['prenom']) ?>"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="d-flex justify-content-center mt-4">
                        <ul class="pagination pagination-modern">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="index.php?action=list&page=<?= $page-1 ?>&search=<?= urlencode($search ?? '') ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php 
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            for ($i = $start; $i <= $end; $i++): ?>
                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                    <a class="page-link" href="index.php?action=list&page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="index.php?action=list&page=<?= $page+1 ?>&search=<?= urlencode($search ?? '') ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-code"></i> Développé avec PHP MVC | 
                <i class="fas fa-heart text-danger"></i> Carnet de contacts
            </p>
        </div>
    </footer>

    <!-- Modal de confirmation suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirmation de suppression
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <p>Êtes-vous sûr de vouloir supprimer le contact</p>
                    <h5 class="fw-bold" id="contactNom"></h5>
                    <p class="text-danger mt-3">
                        <i class="fas fa-ban"></i> Cette action est irréversible !
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Supprimer définitivement
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const contactId = button.getAttribute('data-id');
                const contactNom = button.getAttribute('data-nom');
                document.getElementById('contactNom').textContent = contactNom;
                document.getElementById('confirmDeleteBtn').href = 'index.php?action=delete&id=' + contactId;
            })
        }
    </script>
</body>
</html>