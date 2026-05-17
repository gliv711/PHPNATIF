<?php
// public/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/contact_controller.php';
require_once __DIR__ . '/../controllers/DashboardController.php';

$action = $_GET['action'] ?? 'list';

// Actions publiques (pas besoin d'être connecté)
$publicActions = ['login', 'doLogin', 'logout', 'register', 'doRegister'];

if (!in_array($action, $publicActions)) {
    requireAuth();
}

switch ($action) {
    case 'login': showLoginForm(); break;
    case 'doLogin': login(); break;
    case 'logout': logout(); break;
    case 'register': showRegisterForm(); break;
    case 'doRegister': register(); break;
    case 'profile': showProfileForm(); break;
    case 'updateProfile': updateProfile(); break;
    
    case 'dashboard': dashboardIndex(); break;
    
    case 'list': listContacts(); break;
    case 'create': showCreateForm(); break;
    case 'store': storeContact(); break;
    case 'edit': showEditForm(); break;
    case 'update': updateContactAction(); break;
    case 'delete': requireAdmin(); deleteContactAction(); break;
    
    default: listContacts(); break;
}
?>