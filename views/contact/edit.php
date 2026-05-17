<?php 
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Carnet - Modifier un contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --danger: #f72585;
            --success: #4cc9f0;
            --warning: #f8961e;
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
        
        .form-modern {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeInUp 0.6s ease-out;
        }
        
        .form-modern .form-control,
        .form-modern .form-select {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-modern .form-control:focus,
        .form-modern .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        
        .form-modern label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .form-modern label i {
            margin-right: 8px;
            color: var(--primary);
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
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-color: transparent;
            color: white;
        }
        
        .alert-modern {
            border-radius: 10px;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }
        
        .upload-zone {
            border: 2px dashed #e0e0e0;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-zone:hover {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .current-photo {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }
        
        .current-photo img {
            border-radius: 10px;
            border: 2px solid var(--primary);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        
        .footer {
            background: var(--dark);
            color: white;
            padding: 1.5rem 0;
            margin-top: 3rem;
            text-align: center;
        }
        
        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 2px solid #e0e0e0;
            border-right: none;
            background: white;
        }
        
        .form-control.border-start-0 {
            border-radius: 0 10px 10px 0;
        }
        
        .photo-preview {
            max-width: 100px;
            max-height: 100px;
            border-radius: 10px;
            margin-top: 10px;
            display: none;
        }
        
        .badge-photo {
            background: linear-gradient(135deg, var(--success), #0096c7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.7rem;
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
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7">
                    <div class="form-modern">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-edit" style="font-size: 3rem; color: var(--warning);"></i>
                            <h2 class="mt-3">Modifier un contact</h2>
                            <p class="text-muted">Modifiez les informations du contact</p>
                        </div>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-modern">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Erreur(s) :</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?action=update" method="POST" enctype="multipart/form-data" id="contactForm">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($contact['id']) ?>">
                            
                            <div class="mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-user"></i> Nom <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" id="nom" name="nom" class="form-control border-start-0" 
                                           value="<?= htmlspecialchars($old['nom'] ?? $contact['nom']) ?>" 
                                           placeholder="Dupont" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="form-label">
                                    <i class="fas fa-user"></i> Prénom <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" id="prenom" name="prenom" class="form-control border-start-0" 
                                           value="<?= htmlspecialchars($old['prenom'] ?? $contact['prenom']) ?>" 
                                           placeholder="Jean" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone"></i> Téléphone <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone text-muted"></i>
                                    </span>
                                    <input type="tel" id="telephone" name="telephone" class="form-control border-start-0" 
                                           value="<?= htmlspecialchars($old['telephone'] ?? $contact['telephone']) ?>" 
                                           placeholder="06 12 34 56 78" required>
                                </div>
                                <small class="text-muted">Format: 8 à 20 chiffres, espaces, + et - autorisés</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" id="email" name="email" class="form-control border-start-0" 
                                           value="<?= htmlspecialchars($old['email'] ?? $contact['email']) ?>" 
                                           placeholder="jean.dupont@email.com" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="adresse" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Adresse <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-location-dot text-muted"></i>
                                    </span>
                                    <textarea id="adresse" name="adresse" rows="3" class="form-control border-start-0" 
                                              placeholder="12 rue de Paris, 75001 Paris" required><?= htmlspecialchars($old['adresse'] ?? $contact['adresse']) ?></textarea>
                                </div>
                            </div>

                            <!-- Photo actuelle -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-image"></i> Photo actuelle
                                </label>
                                <div class="current-photo">
                                    <?php if ($contact['photo']): ?>
                                        <img src="<?= htmlspecialchars($contact['photo']) ?>" alt="Photo" style="max-width: 100px; max-height: 100px;">
                                        <div class="mt-2">
                                            <span class="badge-photo">
                                                <i class="fas fa-check-circle"></i> <?= basename($contact['photo']) ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="py-3">
                                            <i class="fas fa-user-circle fa-3x text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">Aucune photo</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Nouvelle photo -->
                            <div class="mb-4">
                                <label for="photo" class="form-label">
                                    <i class="fas fa-camera"></i> Nouvelle photo
                                </label>
                                <div class="upload-zone" onclick="document.getElementById('photo').click()">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                    <p class="mb-0 text-muted">Cliquez pour changer la photo</p>
                                    <small class="text-muted d-block">Laissez vide pour garder la photo actuelle</small>
                                    <input type="file" id="photo" name="photo" class="d-none" accept="image/jpeg,image/png,image/gif" onchange="previewImage(event)">
                                </div>
                                <img id="photoPreview" class="photo-preview" alt="Aperçu">
                                <small class="text-muted d-block mt-2">Formats acceptés : JPG, PNG, GIF (max 2 Mo)</small>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="index.php?action=list" class="btn btn-outline-gradient">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                                <button type="submit" class="btn btn-gradient">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('photoPreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
        
        // Validation supplémentaire côté client
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            const telephone = document.getElementById('telephone').value;
            const phoneRegex = /^[0-9+\-\s]{8,20}$/;
            if (!phoneRegex.test(telephone)) {
                e.preventDefault();
                alert('Format de téléphone invalide. Utilisez 8 à 20 chiffres, espaces, + et -');
            }
        });
    </script>
</body>
</html>