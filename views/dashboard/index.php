<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php?action=list');
    exit();
}
$flash = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>📊 Dashboard Administrateur</h1>
        <div>
            <span class="me-3">Bonjour <?= htmlspecialchars($_SESSION['user_nom']) ?></span>
            <a href="index.php?action=list" class="btn btn-primary btn-sm">← Retour aux contacts</a>
            <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">Déconnexion</a>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endif; ?>

    <!-- Cartes statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total contacts</h5>
                    <h2><?= $totalContacts ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <h2><?= $totalUsers ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Avec photo</h5>
                    <h2><?= $totalPhotos ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Sans photo</h5>
                    <h2><?= $totalContacts - $totalPhotos ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Évolution des contacts (6 derniers mois)</div>
                <div class="card-body">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Top des premières lettres des noms</div>
                <div class="card-body">
                    <canvas id="lettersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers logs -->
    <div class="card">
        <div class="card-header">📋 Dernières activités (logs)</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr><th>Date</th><th>Utilisateur</th><th>Action</th><th>Détails</th><th>IP</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['created_at']) ?></td>
                            <td><?= htmlspecialchars($log['user_nom']) ?> (ID: <?= $log['user_id'] ?>)</td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($log['action']) ?></span></td>
                            <td><?= htmlspecialchars($log['details'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($log['ip_address']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx1 = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: <?= $moisLabels ?: '[]' ?>,
            datasets: [{
                label: 'Contacts ajoutés',
                data: <?= $moisData ?: '[]' ?>,
                borderColor: 'blue',
                fill: false
            }]
        }
    });

    const ctx2 = document.getElementById('lettersChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: <?= $lettresLabels ?: '[]' ?>,
            datasets: [{
                label: 'Nombre de contacts',
                data: <?= $lettresData ?: '[]' ?>,
                backgroundColor: 'green'
            }]
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>