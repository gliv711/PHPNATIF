<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../models/UserModel.php';

function showLoginForm() {
    include __DIR__ . '/../views/auth/login.php';
}

function login() {
    global $pdo;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        $user = findUserByEmail($pdo, $email);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: index.php?action=list');
            exit();
        } else {
            $_SESSION['login_error'] = 'Email ou mot de passe incorrect.';
            header('Location: index.php?action=login');
            exit();
        }
    }
}

function logout() {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?action=login');
    exit();
}

// Inscription
function showRegisterForm() {
    include __DIR__ . '/../views/auth/register.php';
}

function register() {
    global $pdo;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        
        // Validation
        if (empty($nom) || empty($email) || empty($password)) {
            $_SESSION['register_error'] = 'Tous les champs sont requis.';
            header('Location: index.php?action=register');
            exit();
        }
        if ($password !== $password_confirm) {
            $_SESSION['register_error'] = 'Les mots de passe ne correspondent pas.';
            header('Location: index.php?action=register');
            exit();
        }
        if (strlen($password) < 4) {
            $_SESSION['register_error'] = 'Le mot de passe doit contenir au moins 4 caractères.';
            header('Location: index.php?action=register');
            exit();
        }
        // Vérifier si email existe déjà
        $existing = findUserByEmail($pdo, $email);
        if ($existing) {
            $_SESSION['register_error'] = 'Cet email est déjà utilisé.';
            header('Location: index.php?action=register');
            exit();
        }
        
        if (createUser($pdo, $nom, $email, $password, 'user')) {
            $_SESSION['register_success'] = 'Inscription réussie. Vous pouvez vous connecter.';
            header('Location: index.php?action=login');
            exit();
        } else {
            $_SESSION['register_error'] = 'Erreur lors de l\'inscription.';
            header('Location: index.php?action=register');
            exit();
        }
    }
}

// Profil
function showProfileForm() {
    global $pdo;
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit();
    }
    $user = findUserById($pdo, $_SESSION['user_id']);
    $_SESSION['user'] = $user;
    include __DIR__ . '/../views/user/profile.php';
}

function updateProfile() {
    global $pdo;
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_SESSION['user_id'];
        $nom = trim($_POST['nom']);
        $email = trim($_POST['email']);
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        // Vérifier l'unicité de l'email (si changé)
        $user = findUserById($pdo, $id);
        if ($email !== $user['email']) {
            $existing = findUserByEmail($pdo, $email);
            if ($existing) {
                $_SESSION['profile_error'] = 'Cet email est déjà utilisé par un autre compte.';
                header('Location: index.php?action=profile');
                exit();
            }
        }
        
        // Mise à jour des infos de base
        updateUserProfile($pdo, $id, $nom, $email);
        $_SESSION['user_nom'] = $nom;
        $_SESSION['user_email'] = $email;
        
        // Changer le mot de passe si demandé
        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['profile_error'] = 'Pour changer le mot de passe, remplissez tous les champs.';
                header('Location: index.php?action=profile');
                exit();
            }
            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['profile_error'] = 'Mot de passe actuel incorrect.';
                header('Location: index.php?action=profile');
                exit();
            }
            if ($newPassword !== $confirmPassword) {
                $_SESSION['profile_error'] = 'Les nouveaux mots de passe ne correspondent pas.';
                header('Location: index.php?action=profile');
                exit();
            }
            if (strlen($newPassword) < 4) {
                $_SESSION['profile_error'] = 'Le nouveau mot de passe doit contenir au moins 4 caractères.';
                header('Location: index.php?action=profile');
                exit();
            }
            updateUserPassword($pdo, $id, $newPassword);
        }
        
        $_SESSION['profile_success'] = 'Profil mis à jour.';
        header('Location: index.php?action=profile');
        exit();
    }
}

// Fonctions de vérification déjà existantes (isLoggedIn, isAdmin, requireAuth, requireAdmin)
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: index.php?action=login');
        exit();
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        die('Accès interdit : vous n’êtes pas administrateur.');
    }
}
?>