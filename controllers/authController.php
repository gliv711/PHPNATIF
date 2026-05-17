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

// Fonctions de vérification
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