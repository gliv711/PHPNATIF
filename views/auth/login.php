<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Carnet d'adresses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border-radius: 1rem;
            backdrop-filter: blur(10px);
            background-color: rgba(255,255,255,0.9);
        }
        .btn-primary {
            background: linear-gradient(90deg, #667eea, #764ba2);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #5a67d8, #6b46a0);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent text-center pt-4">
                    <i class="fas fa-address-book fa-3x text-primary"></i>
                    <h3 class="mt-2">Carnet d'adresses</h3>
                    <p class="text-muted">Connectez-vous à votre espace</p>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_SESSION['login_error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['login_error']); ?>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['register_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['register_success']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['register_success']); ?>
                    <?php endif; ?>
                    
                    <form action="index.php?action=doLogin" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="exemple@domaine.com" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </button>
                    </form>
                    <div class="mt-3 text-center">
                        <small>Pas encore de compte ? <a href="index.php?action=register">Inscrivez-vous</a></small>
                        <br>
                        <small class="text-muted">Admin: admin@example.com / password</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>