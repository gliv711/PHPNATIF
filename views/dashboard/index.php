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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Mon Carnet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --danger: #f72585;
            --success: #4cc9f0;
            --warning: #f8961e;
            --dark: #1a1a2e;
            --info: #4895ef;
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
        
        .dashboard-header {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeInUp 0.6s ease-out;
        }
        
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
            position: relative;
            z-index: 1;
        }
        
        .stat-card .stat-icon {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 2.5rem;
            opacity: 0.2;
            z-index: 0;
        }
        
        .stat-card .card-title {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .stat-card .card-value {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }
        
        .card-modern {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }
        
        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        
        .card-modern .card-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }
        
        .card-modern .card-header i {
            margin-right: 10px;
        }
        
        .table-modern {
            margin-bottom: 0;
        }
        
        .table-modern thead th {
            background: #f8f9fa;
            border-bottom: 2px solid var(--primary);
            color: var(--dark);
            font-weight: 600;
        }
        
        .badge-action {
            padding: 0.3rem 0.6rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .badge-CREATE { background: #4cc9f0; color: white; }
        .badge-UPDATE { background: #f8961e; color: white; }
        .badge-DELETE { background: #f72585; color: white; }
        .badge-LOGIN { background: #4361ee; color: white; }
        .badge-LOGOUT { background: #6c757d; color: white; }
        
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
        
        .chart-container {
            padding: 1rem;
        }
        
        .welcome-text {
            color: var(--primary);
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .stat-card .card-value {
                font-size: 1.8rem;
            }
            
            .card-modern .card-header {
                padding: 0.75rem 1rem;
            }
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
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?action=dashboard">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=profile">
                            <i class="fas fa-user-circle"></i> Mon profil
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
                <!-- En-tête du dashboard -->
                <div class="dashboard-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h1 class="display-5 fw-bold mb-0">
                                <i class="fas fa-chart-line" style="color: var(--primary);"></i> Dashboard
                            </h1>
                            <p class="text-muted mt-2 mb-0">
                                <i class="fas fa-calendar-alt"></i> Vue d'ensemble de votre carnet de contacts
                            </p>
                        </div>
                        <div>
                            <span class="badge-role badge-admin me-2" style="padding: 0.5rem 1rem;">
                                <i class="fas fa-crown"></i> Administrateur
                            </span>
                            <a href="index.php?action=list" class="btn btn-outline-gradient">
                                <i class="fas fa-arrow-left"></i> Retour aux contacts
                            </a>
                        </div>
                    </div>
                </div>

                <?php if ($flash): ?>
                    <div class="alert alert-<?= $flash['type'] ?> alert-modern alert-dismissible fade show" role="alert">
                        <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($flash['message']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Cartes statistiques -->
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <div class="stat-card text-white" style="background: linear-gradient(135deg, #4361ee, #3f37c9);">
                            <div class="card-body">
                                <div class="stat-icon"><i class="fas fa-address-book"></i></div>
                                <h6 class="card-title"><i class="fas fa-users"></i> Total contacts</h6>
                                <h2 class="card-value"><?= $totalContacts ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-white" style="background: linear-gradient(135deg, #4cc9f0, #0096c7);">
                            <div class="card-body">
                                <div class="stat-icon"><i class="fas fa-users"></i></div>
                                <h6 class="card-title"><i class="fas fa-user-plus"></i> Utilisateurs</h6>
                                <h2 class="card-value"><?= $totalUsers ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-white" style="background: linear-gradient(135deg, #f8961e, #e85d04);">
                            <div class="card-body">
                                <div class="stat-icon"><i class="fas fa-camera"></i></div>
                                <h6 class="card-title"><i class="fas fa-image"></i> Avec photo</h6>
                                <h2 class="card-value"><?= $totalPhotos ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card text-white" style="background: linear-gradient(135deg, #6c757d, #495057);">
                            <div class="card-body">
                                <div class="stat-icon"><i class="fas fa-user-slash"></i></div>
                                <h6 class="card-title"><i class="fas fa-ban"></i> Sans photo</h6>
                                <h2 class="card-value"><?= $totalContacts - $totalPhotos ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card-modern">
                            <div class="card-header">
                                <i class="fas fa-chart-line"></i> Évolution des contacts
                                <small class="float-end">6 derniers mois</small>
                            </div>
                            <div class="chart-container">
                                <canvas id="monthlyChart" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-modern">
                            <div class="card-header">
                                <i class="fas fa-chart-bar"></i> Top des premières lettres
                                <small class="float-end">Distribution par nom</small>
                            </div>
                            <div class="chart-container">
                                <canvas id="lettersChart" style="height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Derniers logs -->
                <div class="card-modern">
                    <div class="card-header">
                        <i class="fas fa-history"></i> Dernières activités
                        <span class="badge bg-light text-dark ms-2"><?= count($recentLogs) ?> événements récents</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-calendar"></i> Date</th>
                                        <th><i class="fas fa-user"></i> Utilisateur</th>
                                        <th><i class="fas fa-tag"></i> Action</th>
                                        <th><i class="fas fa-info-circle"></i> Détails</th>
                                        <th><i class="fas fa-network-wired"></i> IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentLogs)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                                <p class="text-muted mb-0">Aucune activité enregistrée</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recentLogs as $log): ?>
                                        <tr>
                                            <td style="white-space: nowrap;">
                                                <i class="fas fa-clock text-muted small"></i>
                                                <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($log['user_nom']) ?></strong>
                                                <small class="text-muted d-block">ID: <?= $log['user_id'] ?></small>
                                            </td>
                                            <td>
                                                <span class="badge-action badge-<?= $log['action'] ?>">
                                                    <i class="fas fa-<?= $log['action'] === 'CREATE' ? 'plus' : ($log['action'] === 'UPDATE' ? 'edit' : ($log['action'] === 'DELETE' ? 'trash' : 'sign-in-alt')) ?>"></i>
                                                    <?= htmlspecialchars($log['action']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <small><?= htmlspecialchars($log['details'] ?? '-') ?></small>
                                            </td>
                                            <td>
                                                <code class="small"><?= htmlspecialchars($log['ip_address']) ?></code>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">
                <i class="fas fa-code"></i> Développé avec PHP MVC | 
                <i class="fas fa-chart-line"></i> Dashboard Admin |
                <i class="fas fa-heart text-danger"></i> Carnet de contacts
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Graphique mensuel
        const ctx1 = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: <?= $moisLabels ?: '[]' ?>,
                datasets: [{
                    label: 'Contacts ajoutés',
                    data: <?= $moisData ?: '[]' ?>,
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#4361ee',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Graphique des lettres
        const ctx2 = document.getElementById('lettersChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?= $lettresLabels ?: '[]' ?>,
                datasets: [{
                    label: 'Nombre de contacts',
                    data: <?= $lettresData ?: '[]' ?>,
                    backgroundColor: 'rgba(67, 97, 238, 0.7)',
                    borderColor: '#4361ee',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(67, 97, 238, 0.9)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>